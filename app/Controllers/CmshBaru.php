<?php

namespace App\Controllers;

use App\Models\Admin\CmshBaruModel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Models\Admin\UsersModel;
use App\Models\Admin\ProdiModel; // Untuk mapping prodi_id
use CodeIgniter\I18n\Time;

class CmshBaru extends BaseController
{
    protected $CmshBaruModel;

    public function __construct()
    {
        $this->CmshBaruModel = new CmshBaruModel();
    }

    public function cmshbaru()
    {
        $data = [
            'cmshbaru' => $this->CmshBaruModel->getAllCmshBaru()
        ];
        return view('konten/admin/cmshbaru/index.php', $data);
    }

    public function formImport()
    {
        return view('konten/admin/cmshbaru/import.php');
    }

    public function importExcel()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 300); // 5 menit
        
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            try {
                $reader = new Xlsx();

                // Konfigurasi reader untuk menghindari masalah dengan structured references
                $reader->setReadDataOnly(true);
                $reader->setReadEmptyCells(false);

                $spreadsheet = $reader->load($file->getTempName());
                $sheet = $spreadsheet->getActiveSheet();

                // Ambil data sebagai array dengan range yang lebih aman
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Convert ke array dengan cara yang lebih aman
                $sheetData = [];
                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = [];
                    for ($col = 'A'; $col <= $highestColumn; $col++) {
                        $cellValue = $sheet->getCell($col . $row)->getCalculatedValue();
                        $rowData[] = $cellValue;
                    }
                    $sheetData[] = $rowData;
                }

                $userModel = new UsersModel();
                $prodiModel = new ProdiModel();
                $successCount = 0;
                $errorCount = 0;
                $errors = [];

                // Debug: tampilkan beberapa baris pertama
                log_message('debug', 'Total rows: ' . count($sheetData));
                if (count($sheetData) > 1) {
                    log_message('debug', 'Sample row: ' . json_encode($sheetData[1]));
                }

                // Mulai dari baris kedua (skip header)
                for ($i = 1; $i < count($sheetData); $i++) {
                    try {
                        $row = $sheetData[$i];

                        // Pastikan row memiliki data
                        if (empty($row) || count($row) < 10) {
                            continue; // Skip baris kosong
                        }

                        // Mapping kolom sesuai Excel (index dimulai dari 0)
                        $kodePeserta = isset($row[4]) ? trim($row[4]) : '';
                        $namaPeserta = isset($row[5]) ? trim($row[5]) : '';
                        $nisn = isset($row[2]) ? trim($row[2]) : '';
                        $namaProdiTerima = isset($row[7]) ? trim($row[7]) : '';
                        $telepon = isset($row[11]) ? trim($row[11]) : '';
                        $email = isset($row[12]) ? trim($row[12]) : '';
                        $agama = isset($row[13]) ? trim($row[13]) : '';
                        
                        // Tangani tanggal lahir dengan lebih hati-hati
                        $tglLahir = isset($row[42]) ? $row[42] : '';
                        $namaSlta = isset($row[44]) ? trim($row[44]) : '';
                        $kabSlta = isset($row[45]) ? trim($row[45]) : '';
                        $provSlta = isset($row[46]) ? trim($row[46]) : '';
                        $tahunLulusSlta = isset($row[47]) ? trim($row[47]) : '';
                        $jurusanSlta = isset($row[48]) ? trim($row[48]) : '';

                        // Validasi data wajib
                        if (empty($kodePeserta) || empty($namaPeserta) || empty($nisn)) {
                            $errors[] = "Baris " . ($i + 1) . ": Data wajib tidak lengkap (Kode: '$kodePeserta', Nama: '$namaPeserta', NISN: '$nisn')";
                            $errorCount++;
                            continue;
                        }

                        // Parsing tanggal lahir dengan error handling yang lebih baik
                        $tglLahirFormatted = '';
                        if (!empty($tglLahir)) {
                            try {
                                if (is_numeric($tglLahir)) {
                                    // Jika format Excel date serial
                                    $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tglLahir);
                                    $tglLahirFormatted = $dateObj->format('Y-m-d');
                                } else {
                                    // Jika sudah dalam format string, coba parse
                                    $dateFormats = ['Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'Y/m/d'];
                                    foreach ($dateFormats as $format) {
                                        $date = \DateTime::createFromFormat($format, $tglLahir);
                                        if ($date) {
                                            $tglLahirFormatted = $date->format('Y-m-d');
                                            break;
                                        }
                                    }
                                }
                            } catch (\Exception $e) {
                                log_message('error', "Error parsing date for row " . ($i + 1) . ": " . $e->getMessage());
                            }
                        }

                        // Validasi email
                        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Baris " . ($i + 1) . ": Format email tidak valid: '$email'";
                            $errorCount++;
                            continue;
                        }

                        // Cari prodi_id berdasarkan nama prodi yang diterima
                        $prodiId = null;
                        if (!empty($namaProdiTerima)) {
                            // Coba exact match dulu
                            $prodi = $prodiModel->where('nama_prodi', $namaProdiTerima)->first();
                            if (!$prodi) {
                                // Coba dengan LIKE untuk matching yang lebih fleksibel
                                $prodi = $prodiModel->like('nama_prodi', $namaProdiTerima, 'both')->first();
                            }
                            
                            if ($prodi) {
                                $prodiId = $prodi['id'];
                            } else {
                                // Log untuk debugging
                                log_message('info', "Prodi tidak ditemukan: '$namaProdiTerima' pada baris " . ($i + 1));
                                // Jangan skip, biarkan prodi_id null
                            }
                        }

                        // Cek apakah user sudah ada
                        $existingUser = $userModel->where('username', $kodePeserta)->first();
                        if ($existingUser) {
                            $errors[] = "Baris " . ($i + 1) . ": Username '$kodePeserta' sudah ada";
                            $errorCount++;
                            continue;
                        }

                        // Cek apakah NISN sudah ada
                        $existingNisn = $this->CmshBaruModel->where('nisn', $nisn)->first();
                        if ($existingNisn) {
                            $errors[] = "Baris " . ($i + 1) . ": NISN '$nisn' sudah ada";
                            $errorCount++;
                            continue;
                        }

                        // Mulai database transaction
                        $db = \Config\Database::connect();
                        $db->transStart();

                        try {
                            // Simpan ke tabel users
                            $username = $kodePeserta;
                            $passwordDate = !empty($tglLahirFormatted) ? date('dmY', strtotime($tglLahirFormatted)) : date('dmY');
                            $hashedPassword = hash('sha256', sha1($passwordDate));

                            $userId = $userModel->insert([
                                'username' => $username,
                                'password' => $hashedPassword,
                                'role_id' => 2, // mahasiswa
                                'created_at' => Time::now(),
                                'updated_at' => Time::now(),
                            ], true);

                            if (!$userId) {
                                throw new \Exception("Gagal menyimpan user");
                            }

                            // Gabungkan nama kabupaten dan provinsi untuk tipe sekolah
                            $tipeSekolah = '';
                            if (!empty($kabSlta) && !empty($provSlta)) {
                                $tipeSekolah = $kabSlta . ', ' . $provSlta;
                            } elseif (!empty($kabSlta)) {
                                $tipeSekolah = $kabSlta;
                            } elseif (!empty($provSlta)) {
                                $tipeSekolah = $provSlta;
                            }

                            // Prepare data untuk insert
                            $mahasiswaData = [
                                'nisn' => $nisn,
                                'nama' => $namaPeserta,
                                'tgl_lahir' => $tglLahirFormatted ?: null,
                                'nama_sekolah' => $namaSlta,
                                'tipe_sekolah' => $tipeSekolah,
                                'jurusan_asal' => $jurusanSlta,
                                'tahun_lulus' => $tahunLulusSlta,
                                'email' => $email,
                                'no_hp' => $telepon,
                                'agama' => $agama,
                                'created_at' => Time::now(),
                                'updated_at' => Time::now(),
                                'users_id' => $userId,
                                'prodi_id' => $prodiId,
                                // Field tambahan
                                'nik' => isset($row[1]) ? trim($row[1]) : null,
                                'kode_peserta' => $kodePeserta,
                                'nomor_kip_k' => isset($row[6]) ? trim($row[6]) : null,
                                'nama_prodi_terima' => $namaProdiTerima,
                                'jenjang_prodi_terima' => isset($row[8]) ? trim($row[8]) : null,
                                'pilihan_terima' => isset($row[9]) ? trim($row[9]) : null,
                                'alamat' => isset($row[10]) ? trim($row[10]) : null,
                                'tempat_lahir' => isset($row[41]) ? trim($row[41]) : null,
                                'telepon' => $telepon,
                                'nama_ayah' => isset($row[14]) ? trim($row[14]) : null,
                                'pendidikan_ayah' => isset($row[15]) ? trim($row[15]) : null,
                                'pekerjaan_ayah' => isset($row[16]) ? trim($row[16]) : null,
                                'penghasilan_ayah' => isset($row[17]) ? trim($row[17]) : null,
                                'nama_ibu' => isset($row[18]) ? trim($row[18]) : null,
                                'pendidikan_ibu' => isset($row[19]) ? trim($row[19]) : null,
                                'pekerjaan_ibu' => isset($row[20]) ? trim($row[20]) : null,
                                'penghasilan_ibu' => isset($row[21]) ? trim($row[21]) : null,
                                'jumlah_tanggungan' => isset($row[22]) ? (int)$row[22] : null,
                                'npsn' => isset($row[43]) ? trim($row[43]) : null,
                                'kab_sekolah' => $kabSlta,
                                'prov_sekolah' => $provSlta,
                                'hasil_eligible' => isset($row[50]) ? trim($row[50]) : null,
                            ];

                            // Remove null/empty values untuk menghindari error
                            $mahasiswaData = array_filter($mahasiswaData, function($value) {
                                return $value !== null && $value !== '';
                            });

                            $insertResult = $this->CmshBaruModel->insert($mahasiswaData);

                            if (!$insertResult) {
                                throw new \Exception("Gagal menyimpan data mahasiswa");
                            }

                            // Commit transaction
                            $db->transCommit();
                            $successCount++;

                        } catch (\Exception $e) {
                            // Rollback transaction
                            $db->transRollback();
                            $errorCount++;
                            $errors[] = "Baris " . ($i + 1) . ": " . $e->getMessage();
                            log_message('error', "Error inserting data for row " . ($i + 1) . ": " . $e->getMessage());
                        }

                    } catch (\Exception $e) {
                        $errorCount++;
                        $errors[] = "Baris " . ($i + 1) . ": Error processing - " . $e->getMessage();
                        log_message('error', "Error processing row " . ($i + 1) . ": " . $e->getMessage());
                    }
                }

                // Buat pesan hasil import
                $message = "📊 Import selesai!<br>";
                $message .= "✅ Berhasil: <strong>{$successCount}</strong> data<br>";
                $message .= "❌ Gagal: <strong>{$errorCount}</strong> data";
                
                if (!empty($errors)) {
                    $message .= "<br><br>📋 Detail Error (10 teratas):<br>";
                    $errorSample = array_slice($errors, 0, 10);
                    foreach ($errorSample as $error) {
                        $message .= "• " . htmlspecialchars($error) . "<br>";
                    }
                    
                    if (count($errors) > 10) {
                        $message .= "• ... dan " . (count($errors) - 10) . " error lainnya<br>";
                    }
                }

                if ($successCount > 0) {
                    return redirect()->to('/admin/cmshbaru')->with('success', $message);
                } else {
                    return redirect()->to('/admin/cmshbaru/formImport')->with('error', $message);
                }

            } catch (\Exception $e) {
                log_message('error', 'Excel import error: ' . $e->getMessage());
                return redirect()->back()->with('error', '🚫 Error membaca file Excel: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', '🚫 File tidak valid atau tidak dapat diproses.');
    }
}