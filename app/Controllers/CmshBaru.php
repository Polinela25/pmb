<?php

namespace App\Controllers;

use App\Models\Admin\CmshBaruModel;
use App\Models\Admin\JurusanModel;
use App\Models\Admin\ProdiModel;
use App\Models\Admin\TahunModel;

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

    public function add()
    {
        $jurusanModel = new JurusanModel();
        $prodiModel = new ProdiModel();
        $tahunModel = new TahunModel();

        $data = [
            'jurusan' => $jurusanModel->findAll(),
            'prodi'   => $prodiModel->findAll(),
            'tahun'   => $tahunModel->findAll()
        ];

        return view('konten/admin/cmshbaru/add.php', $data);
    }

    public function save()
    {
        $data = [
            'nama'       => $this->request->getPost('nama'),
            'jurusan_id' => $this->request->getPost('jurusan_id'),
            'prodi_id'   => $this->request->getPost('prodi_id'),
            'tahun_id'   => $this->request->getPost('tahun_id'),
        ];

        $this->CmshBaruModel->save($data);
        return redirect()->to('/admin/cmshbaru');
    }

    public function editcmshbaru($id)
    {
        $jurusanModel = new JurusanModel();
        $prodiModel = new ProdiModel();
        $tahunModel = new TahunModel();

        $data = [
            'mahasiswa' => $this->CmshBaruModel->getByIdWithJoin(decrypt_url($id)),
            'jurusan'   => $jurusanModel->findAll(),
            'prodi'     => $prodiModel->findAll(),
            'tahun'     => $tahunModel->findAll(),
            'errors'    => session('errors'),
        ];

        return view('konten/admin/cmshbaru/edit.php', $data);
    }

    public function editCmshBaruPost($id)
    {
        $validation = $this->validate([
            'nama' => 'required',
            'jurusan_id' => 'required',
            'prodi_id' => 'required',
            'tahun_id' => 'required',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'       => $this->request->getPost('nama'),
            'jurusan_id' => $this->request->getPost('jurusan_id'),
            'prodi_id'   => $this->request->getPost('prodi_id'),
            'tahun_id'   => $this->request->getPost('tahun_id'),
        ];

        $this->CmshBaruModel->updateData(decrypt_url($id), $data);
        session()->setFlashdata('success', 'Berhasil mengubah data.');
        return redirect()->to('/admin/cmshbaru');
    }

    public function deleteKategori($id)
    {
        $this->CmshBaruModel->delete(decrypt_url($id));
        session()->setFlashdata('success', 'Berhasil menghapus data.');
        return redirect()->to('/admin/cmshbaru');
    }
}
