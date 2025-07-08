<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_product', 'kategori_id', 'stok', 'harga', 'gambar'];

    public function getAllProductWithKategori()
    {
        // Tabel utama
        $this->table('product');
        // Kolom yang akan ditampilkan
        $this->select('product.*, kategori.nama_kategori, kategori.nama_kategori');
        // Join ke tabel jenjang

        $this->join('kategori', 'product.kategori_id = kategori.id');
        // Lakukan query
        $query = $this->get();
        // Ambil hasil query
        return $query->getResultArray();
    }
    public function getAllProduk()
    {
        return $this->findAll();
    }
    public function insertData($data)
    {
        try {
            $this->insert($data);
            return true;  // Berhasil
        } catch (\Exception $e) {
            return false; // Gagal, tangani exception jika diperlukan
        }
    }
    public function getProductById($id)
    {
        // Pastikan Anda menggunakan builder yang tepat
        $builder = $this->db->table('product'); // Ganti 'product' dengan nama tabel sebenarnya jika berbeda
    
        // Pilih kolom dari tabel product dan kategori
        $builder->select('product.*, kategori.nama_kategori');
    
        // Lakukan join dengan tabel kategori
        $builder->join('kategori', 'product.kategori_id = kategori.id', 'left');
    
        // Tambahkan filter berdasarkan ID produk
        $builder->where('product.id', $id);
    
        // Eksekusi query dan ambil satu baris data
        return $builder->get()->getRow();
    }
    

    public function updateProduct($id, $data)
    {

        // Update data berdasarkan ID
        $this->set($data)->where('id', $id)->update();
    }
}
