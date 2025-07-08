<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi'; // Nama tabel di database
    protected $primaryKey = 'id'; // Primary key tabel

    // Kolom yang diizinkan untuk diisi secara massal
    protected $allowedFields = [
        'product_id',
        'kategori_id',
        'user_id',
        'harga',
        'jumlah_product',
        'total_harga',
        'gambar'
    ];
    // public function getAllTransaksiWithProduct()
    // {
    //     // Tabel utama
    //     $this->table('transaksi');
    //     // Kolom yang akan ditampilkan
    //     $this->select('transaksi.*, kategori.nama_kategori, users.nama, product.nama_product');
    //     // Join ke tabel
    //     $this->join('kategori', 'transaksi.kategori_id = kategori.id');
    //     $this->join('product', 'transaksi.product_id = product.id');
    //     $this->join('users', 'transaksi.user_id = users.id');

    //     // Filter berdasarkan user_id yang login
    //     $user_id = session()->get('data')['id']; // Ambil user_id dari session
    //     $this->where('transaksi.user_id', $user_id); // Kondisi untuk menampilkan transaksi berdasarkan user_id

    //     // Lakukan query
    //     $query = $this->get();
    //     // Debug untuk memastikan hasil query
    //     return $query->getResultArray();
    // }

    public function getTransaksiHarian()
    {
        return $this->select("DATE(created_at) as tanggal, COUNT(*) as jumlah_transaksi")
                    ->groupBy("DATE(created_at)")
                    ->orderBy("tanggal", "ASC")
                    ->findAll();
    }
    public function getAllTransaksi()
    {
        return $this->db->table('transaksi')
            ->join('product', 'product.id = transaksi.product_id') // misalnya 'product_id' adalah kolom yang menghubungkan transaksi dengan product
            ->join('kategori', 'kategori.id = product.kategori_id') // misalnya 'kategori_id' adalah kolom yang menghubungkan produk dengan kategori
            ->select('transaksi.*, product.nama_product, kategori.nama_kategori')
            ->get()->getResultArray();
    }
    public function getAllTransaksiAdmin()
    {
        return $this->db->table('transaksi')
            ->join('product', 'product.id = transaksi.product_id') // misalnya 'product_id' adalah kolom yang menghubungkan transaksi dengan product
            ->join('users', 'users.id = transaksi.user_id') // misalnya 'product_id' adalah kolom yang menghubungkan transaksi dengan product
            ->join('kategori', 'kategori.id = product.kategori_id') // misalnya 'kategori_id' adalah kolom yang menghubungkan produk dengan kategori
            ->select('transaksi.*, product.nama_product, kategori.nama_kategori,  users.nama')
            ->get()->getResultArray();
    }
}
