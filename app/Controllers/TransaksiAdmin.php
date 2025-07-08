<?php

namespace App\Controllers;

use App\Models\Admin\KategoriModel;
use App\Models\Admin\ProductModel;
use App\Models\Admin\UsersModel;
use App\Models\Admin\TransaksiModel;

class TransaksiAdmin extends BaseController
{
    protected $userModel;
    protected $db;
    protected $productModel;
    protected $kategoriModel;
    protected $transaksiModel;
    public function __construct()
    {
        $this->userModel = new UsersModel();
        $this->productModel = new ProductModel();
        $this->kategoriModel = new KategoriModel();
        $this->transaksiModel = new TransaksiModel();
    }
    public function index()
    {
        
        // $user_id = session()->get('user_id');
        // dd($user_id); // Pastikan user_id ada dalam session
        $data = [
            'transaksi' => $this->transaksiModel->getAllTransaksiAdmin()
        ];
        
        // dd($data); // Debug untuk melihat data yang diambil
        echo view('konten/admin/transaksi/index.php', $data);
    }

    // Controller
    public function add($id)
    {
        $product_id = decrypt_url($id); // Dekripsi ID produk
        $product = $this->productModel->find($product_id); // Ambil data produk berdasarkan ID

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Pastikan kategori_id ada di data produk
        if (!isset($product['kategori_id'])) {
            return redirect()->back()->with('error', 'Produk tidak memiliki kategori.');
        }

        $user_id = session()->get('data')['id']; // Mengambil ID pengguna dari session
        $user = $this->userModel->find($user_id); // Ambil data pengguna berdasarkan ID

        $kategori = $this->kategoriModel->find($product['kategori_id']); // Ambil kategori berdasarkan kategori_id produk

        $data = [
            'product' => $product,
            'user' => $user,
            'kategori' => $kategori, // Kirim data kategori ke view
        ];

        return view('konten/admin/transaksi/add', $data);
    }public function store()
    {
        // Ambil product_id dari request
        $product_id = $this->request->getPost('product_id');
        $user_id = session()->get('data')['id']; // Mengambil user_id dari session
    
        // Ambil data produk berdasarkan product_id
        $product = $this->productModel->find($product_id); // Mendapatkan data produk berdasarkan ID
    
        // Pastikan produk ditemukan
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }
    
        // Ambil jumlah produk yang dipesan
        $jumlah_product = $this->request->getPost('jumlah_product');
        
        // Periksa apakah stok mencukupi
        if ($product['stok'] < $jumlah_product) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }
    
        // Siapkan data untuk transaksi
        $data = [
            'product_id' => $product_id,
            'kategori_id' => $this->request->getPost('kategori_id'),
            'user_id' => $user_id,
            'harga' => $this->request->getPost('harga'),
            'jumlah_product' => $jumlah_product,
            'total_harga' => $this->request->getPost('total_harga'),
            'gambar' => $product['gambar'],
        ];
    
        // Simpan data transaksi ke database
        $this->transaksiModel->save($data);
    
        // Kurangi stok produk
        $new_stok = $product['stok'] - $jumlah_product;
        $this->productModel->update($product_id, ['stok' => $new_stok]);
    
        // Redirect ke halaman transaksi admin
        return redirect()->to('/admin/transaksi')->with('success', 'Transaksi berhasil disimpan.');
    }
    
}
