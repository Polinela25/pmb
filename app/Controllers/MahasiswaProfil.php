<?php

namespace App\Controllers;

use App\Models\Admin\UsersModel;
use App\Models\Admin\CmshBaruModel;
use App\Models\Admin\JurusanModel;
use App\Models\Admin\ProdiModel;
use App\Models\Admin\TahunModel;

class MahasiswaProfil extends BaseController
{
    protected $userModel;
    public function __construct()
    {
        $this->userModel = new UsersModel();
    }

    public function index()
    {
        $usersId = session()->get('data')['id'];
// dd($usersId);
        $mhsModel = new CmshBaruModel();
        $mahasiswa = $mhsModel->getByUsersId($usersId);

        $profilTidakLengkap = !$mahasiswa
    || empty($mahasiswa['jurusan_id'])
    || empty($mahasiswa['prodi_id'])
    || empty($mahasiswa['tahun_id'])
    || empty($mahasiswa['tgl_lahir'])
    || empty($mahasiswa['nisn'])
    || empty($mahasiswa['no_hp'])
    || empty($mahasiswa['nama_sekolah'])
    || empty($mahasiswa['tipe_sekolah'])
    || empty($mahasiswa['jurusan_asal'])
    || empty($mahasiswa['tahun_lulus']);


        $jurusanModel = new JurusanModel();
        $prodiModel = new ProdiModel();
        $tahunModel = new TahunModel();

        $data = [
            'mahasiswa' => $mahasiswa,
            'profilTidakLengkap' => $profilTidakLengkap,
            'jurusan' => $jurusanModel->findAll(),
            'prodi' => $prodiModel->findAll(),
            'tahun' => $tahunModel->findAll(),
            'errors' => session('errors')
        ];
// dd($data);
        return view('konten/mahasiswa/profil/index', $data);
    }

    public function updateProfil()
    {
        $usersId = session()->get('data')['id'];

        $password = $this->request->getPost('password');
        $nama = $this->request->getPost('nama');
        $tglLahir = $this->request->getPost('tgl_lahir');
        $jurusanId = $this->request->getPost('jurusan_id');
        $prodiId = $this->request->getPost('prodi_id');
        $tahunId = $this->request->getPost('tahun_id');
        $nisn         = $this->request->getPost('nisn');
        $namaSekolah  = $this->request->getPost('nama_sekolah');
        $tipeSekolah  = $this->request->getPost('tipe_sekolah');
        $jurusanAsal  = $this->request->getPost('jurusan_asal');
        $tahunLulus   = $this->request->getPost('tahun_lulus');
        $noHp         = $this->request->getPost('no_hp');

        $userData = [];
        $mhsData = [];

        if (!empty($password)) {
            $userData['password'] = hash('sha256', sha1($password));
        }
        if (!empty($nama)) {
            $userData['nama'] = $nama;
             $mhsData['nama'] = $nama;
        }

        if (!empty($tglLahir)) {
            $mhsData['tgl_lahir'] = $tglLahir;
        }
        if (!empty($jurusanId)) {
            $mhsData['jurusan_id'] = $jurusanId;
        }
        if (!empty($prodiId)) {
            $mhsData['prodi_id'] = $prodiId;
        }
        if (!empty($tahunId)) {
            $mhsData['tahun_id'] = $tahunId;
        }
        if (!empty($nisn)) {
            $mhsData['nisn'] = $nisn;
        }
        if (!empty($namaSekolah)) {
            $mhsData['nama_sekolah'] = $namaSekolah;
        }
        if (!empty($tipeSekolah)) {
            $mhsData['tipe_sekolah'] = $tipeSekolah;
        }
        if (!empty($jurusanAsal)) {
            $mhsData['jurusan_asal'] = $jurusanAsal;
        }
        if (!empty($tahunLulus)) {
            $mhsData['tahun_lulus'] = $tahunLulus;
        }
        if (!empty($noHp)) {
            $mhsData['no_hp'] = $noHp;
        }
        if (!empty($userData)) {
            $this->userModel->update($usersId, $userData);
        }

        if (!empty($mhsData)) {
            $mhsModel = new CmshBaruModel();
            $mahasiswa = $mhsModel->where('users_id', $usersId)->first();
            if ($mahasiswa) {
                $mhsModel->update($mahasiswa['id'], $mhsData);
            }
        }

        session()->setFlashdata('success', 'Profil berhasil diperbarui.');
        return redirect()->to('/mahasiswa/profile');
    }
}
