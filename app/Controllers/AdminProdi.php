<?php

namespace App\Controllers;

use App\Models\Admin\ProdiModel;

class AdminProdi extends BaseController
{
    public function index()
    {
        $model = new ProdiModel();
        $data['prodi'] = $model->findAll();
        return view('konten/admin/prodi/index', $data);
    }

    public function add()
    {
        return view('konten/admin/prodi/add');
    }

    public function save()
    {
        $model = new ProdiModel();
        $data = [
            'kode_prodi' => $this->request->getPost('kode_prodi'),
            'nama_prodi' => $this->request->getPost('nama_prodi'),
        ];
        $model->simpanData($data);
        return redirect()->to('/admin/kode/prodi');
    }

    public function edit($id)
    {
        $model = new ProdiModel();
        $data['prodi'] = $model->find($id);
        return view('konten/admin/prodi/edit', $data);
    }

    public function update($id)
    {
        $model = new ProdiModel();
        $data = [
            'kode_prodi' => $this->request->getPost('kode_prodi'),
            'nama_prodi' => $this->request->getPost('nama_prodi'),
        ];
        $model->updateData($id, $data);
        return redirect()->to('/admin/kode/prodi');
    }

    public function delete($id)
    {
        $model = new ProdiModel();
        $model->delete($id);
        return redirect()->to('/admin/kode/prodi');
    }
}
