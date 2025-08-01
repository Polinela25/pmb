<?php

namespace App\Controllers;

use App\Models\Admin\CmshBaruModel;
// use App\Models\Admin\JurusanModel;
// use App\Models\Admin\ProdiModel;
// use App\Models\Admin\TahunModel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Models\Admin\UsersModel;
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
        return view('konten/admin/cmshbaru/import_excel');
    }

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $reader = new Xlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray();

            $userModel = new UsersModel();

            for ($i = 1; $i < count($sheet); $i++) {
                $row = $sheet[$i];

                $kodePeserta  = trim($row[1]);
                $nama         = trim($row[2]);
                $nisn         = trim($row[3]);
                $tglLahir     = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[4])->format('Y-m-d');
                $namaSekolah  = trim($row[5]);
                $tipeSekolah  = trim($row[6]);
                $jurusanAsal  = trim($row[7]);
                $tahunLulus   = trim($row[8]);
                $email        = trim($row[9]);
                $noHp         = trim($row[10]);

                // Simpan ke tabel users
                $username = $kodePeserta;
                $password = date('Ymd', strtotime($tglLahir)); // password: YYYYMMDD
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $userId = $userModel->insert([
                    'username' => $username,
                    'password' => $hashedPassword,
                    'role_id'  => 2, // mahasiswa
                    'created_at' => Time::now(),
                    'updated_at' => Time::now(),
                ], true); // dapatkan inserted ID

                // Simpan ke tabel mahasiswa
                $this->CmshBaruModel->insert([
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'tgl_lahir' => $tglLahir,
                    'nama_sekolah' => $namaSekolah,
                    'tipe_sekolah' => $tipeSekolah,
                    'jurusan_asal' => $jurusanAsal,
                    'tahun_lulus' => $tahunLulus,
                    'email' => $email,
                    'no_hp' => $noHp,
                    'created_at' => Time::now(),
                    'updated_at' => Time::now(),
                    'users_id' => $userId
                ]);
            }

            return redirect()->to('/admin/cmshbaru')->with('success', 'Data berhasil diimport dari Excel!');
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }
}
