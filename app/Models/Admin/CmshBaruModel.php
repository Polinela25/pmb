<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class CmshBaruModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama','tgl_lahir', 'jurusan_id', 'prodi_id', 'tahun_id', 'users_id'];
    protected $useTimestamps = true;

    public function getAllCmshBaru()
    {
        return $this->select('mahasiswa.*, jurusan.nama_jurusan, prodi.nama_prodi, tahun.kode_tahun')
            ->join('jurusan', 'jurusan.id = mahasiswa.jurusan_id')
            ->join('prodi', 'prodi.id = mahasiswa.prodi_id')
            ->join('tahun', 'tahun.id = mahasiswa.tahun_id')
            ->findAll();
    }

    public function getByIdWithJoin($id)
    {
        return $this->select('mahasiswa.*, jurusan.nama_jurusan, prodi.nama_prodi, tahun.kode_tahun')
            ->join('jurusan', 'jurusan.id = mahasiswa.jurusan_id')
            ->join('prodi', 'prodi.id = mahasiswa.prodi_id')
            ->join('tahun', 'tahun.id = mahasiswa.tahun_id')
            ->where('mahasiswa.id', $id)
            ->first();
    }

    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }
    public function getByUsersId($usersId)
{
    return $this->select('mahasiswa.*, jurusan.nama_jurusan, prodi.nama_prodi, tahun.kode_tahun, users.username, users.password')
        ->join('jurusan', 'jurusan.id = mahasiswa.jurusan_id')
        ->join('prodi', 'prodi.id = mahasiswa.prodi_id')
        ->join('tahun', 'tahun.id = mahasiswa.tahun_id')
        ->join('users', 'users.id = mahasiswa.users_id') // JOIN users
        ->where('mahasiswa.users_id', $usersId)
        ->first();
}


}
