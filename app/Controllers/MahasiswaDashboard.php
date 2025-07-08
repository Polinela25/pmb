<?php

namespace App\Controllers;

use App\Models\Admin\CmshBaruModel;

class MahasiswaDashboard extends BaseController
{
    public function index()
{
    $model = new \App\Models\Admin\CmshBaruModel();
    $userId = session()->get('data')['id']; // asumsi user login

    $mahasiswa = $model->where('users_id', $userId)->first();

    // Cek apakah profil belum lengkap
    $profilTidakLengkap = !$mahasiswa || empty($mahasiswa['jurusan_id']) || empty($mahasiswa['prodi_id']) || empty($mahasiswa['tahun_id']) || empty($mahasiswa['tgl_lahir']);

    return view('konten/mahasiswa/dashboard/index', [
        'disableForm' => $profilTidakLengkap,
        'mahasiswa' => $mahasiswa
    ]);
}


    // AJAX untuk Select2 autocomplete
    public function searchNama()
{
    $query = $this->request->getGet('query');
    $model = new CmshBaruModel();

    $data = $model
        ->like('nama', $query)
        ->orderBy('nama', 'asc')
        ->limit(10)
        ->findAll();

    $results = array_map(function ($row) {
        return [
            'id' => $row['id'],       // ini angka, ID asli
            'text' => $row['nama']    // ini yang ditampilkan di select
        ];
    }, $data);

    return $this->response->setJSON(['results' => $results]);
}


    // Proses generate NPM berdasarkan nama
   public function generateNpm()
{
    $id = $this->request->getPost('nama'); // id mahasiswa
    $inputTanggal = $this->request->getPost('tgllahir');

    $npmModel = new \App\Models\Admin\NpmModel();
    $existing = $npmModel->where('mahasiswa_id', $id)->first();

    // Jika sudah ada, tolak pembuatan ulang
    if ($existing) {
        return $this->response->setJSON(['npm' => 'NPM sudah digenerate: ' . $existing['npm']]);
    }

    $model = new CmshBaruModel();
    $allMahasiswa = $model->orderBy('nama', 'asc')->findAll();

    $index = array_search($id, array_column($allMahasiswa, 'id'));
    if ($index === false) {
        return $this->response->setJSON(['npm' => 'Mahasiswa tidak ditemukan']);
    }

    $mhs = $model
        ->select('mahasiswa.*, tahun.kode_tahun, jurusan.kode_jurusan, prodi.kode_prodi')
        ->join('tahun', 'tahun.id = mahasiswa.tahun_id')
        ->join('jurusan', 'jurusan.id = mahasiswa.jurusan_id')
        ->join('prodi', 'prodi.id = mahasiswa.prodi_id')
        ->where('mahasiswa.id', $id)
        ->first();

    if (!$mhs) {
        return $this->response->setJSON(['npm' => 'Mahasiswa tidak ditemukan']);
    }

    if ($mhs['tgl_lahir'] != $inputTanggal) {
        return $this->response->setJSON(['npm' => 'Tanggal lahir tidak cocok']);
    }

    $urutan = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
    $npm = $mhs['kode_tahun'] . $mhs['kode_jurusan'] . $mhs['kode_prodi'] . $urutan;

    // Simpan ke tabel npm (karena sudah dicek, tidak mungkin dobel)
    $npmModel->insert([
        'mahasiswa_id' => $id,
        'npm' => $npm
    ]);

    return $this->response->setJSON(['npm' => $npm]);
}

}
