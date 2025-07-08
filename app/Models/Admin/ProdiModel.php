<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ProdiModel extends Model
{
    protected $table = 'prodi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode_prodi', 'nama_prodi'];

    public function simpanData($data)
    {
        return $this->insert($data);
    }

    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }
}
