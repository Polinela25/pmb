<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class CmshBaruModel extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
    'nisn',
    'nama',
    'tgl_lahir',
    'nama_sekolah',
    'tipe_sekolah',
    'jurusan_asal',
    'tahun_lulus',
    'email',
    'no_hp',
    'created_at',
    'updated_at',
    'users_id',       // tambahkan ini
    'jurusan_id',     // tambahkan ini
    'prodi_id',       // tambahkan ini
    'tahun_id'        // tambahkan ini
];

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
        ->join('jurusan', 'jurusan.id = mahasiswa.jurusan_id', 'left')
        ->join('prodi', 'prodi.id = mahasiswa.prodi_id', 'left')
        ->join('tahun', 'tahun.id = mahasiswa.tahun_id', 'left')
        ->join('users', 'users.id = mahasiswa.users_id', 'left')
        ->where('mahasiswa.users_id', $usersId)
        ->first();
}

}
