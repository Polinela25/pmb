<?php

namespace App\Controllers;

use App\Models\Admin\CmshBaruModel;

class Pendaftar extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function save()
    {
        $data = $this->request->getPost();

        // Validasi email cocok
        if ($data['email'] !== $data['konfirmasi_email']) {
            return redirect()->back()->with('error', 'Email dan konfirmasi tidak cocok.');
        }

        $model = new CmshBaruModel();

        $model->insert([
            'nisn'        => $data['nisn'],
            'nama'        => $data['nama'],
            'tgl_lahir'   => $data['tgl_lahir'],
            'nama_sekolah'=> $data['nama_sekolah'],
            'tipe_sekolah'=> $data['tipe_sekolah'],
            'jurusan_asal'=> $data['jurusan'],
            'tahun_lulus' => $data['tahun_lulus'],
            'email'       => $data['email'],
            'no_hp'       => $data['no_hp'],
            'created_at'  => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/')->with('success', 'Registrasi berhasil, silakan cek email Anda.');
    }
}
