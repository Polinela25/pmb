<?php

namespace App\Controllers;

use App\Models\Admin\ProductModel;
use App\Models\Admin\KategoriModel;
use Ramsey\Uuid\Uuid;

class ProductController extends BaseController
{
    protected $productModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function product()
    {
        $data = [
            'product' => $this->productModel->getAllProductWithKategori()
        ];
        echo view('konten/admin/product/index.php', $data);
    }

    public function add()
    {
        $data = [
            'product'     => $this->productModel->getAllProduk(),
            'kategori'   => $this->kategoriModel->getAllKategori(),

            'errors'    => session('errors'), // Tambahkan validation ke data
        ];
        echo view('konten/admin/product/add.php', $data);
    }

    public function save()
    {
        $validation = $this->validate([
            'nama_product' => 'required',
            'kategori_id' => 'required',
            'stok'         => 'required',
            'harga'        => 'required',
            'gambar'       => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/png,image/jpg,image/jpeg]',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil file gambar
        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/gambarProduct', $namaGambar);
        }

        // Simpan data ke database
        $this->productModel->save([
            'nama_product' => $this->request->getPost('nama_product'),
            'kategori_id'  => $this->request->getPost('kategori_id'),
            'stok'         => $this->request->getPost('stok'),
            'harga'        => $this->request->getPost('harga'),
            'gambar'       => $namaGambar ?? null,
        ]);

        // Redirect ke halaman sukses
        return redirect()->to('/admin/product')->with('success', 'Data produk berhasil disimpan.');
    }


    public function deleteProduct($id)
    {
        $this->productModel->delete(decrypt_url($id));
        session()->setFlashdata('error', 'Berhasil menghapus data.'); // tambahkan ini
        return redirect()->to('/admin/product');
    }
    public function editProduct($id)
    {
        $data = [
            'product'     => $this->productModel->getProductById(decrypt_url($id)),
            'kategori'   => $this->kategoriModel->getAllKategori(),
            'errors'    => session('errors'), // Tambahkan validation ke data
        ];
        echo view('konten/admin/product/edit.php', $data);
    }
    public function updateProduct($encryptedId)
    {
        // Dekripsi ID produk dari URL
        $id = decrypt_url($encryptedId);

        // Validasi input
        $validation = $this->validate([
            'nama_product' => 'required',
            'kategori_id'  => 'required',
            'stok'         => 'required|numeric',
            'harga'        => 'required|numeric',
            'gambar'       => 'if_exist|uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/png,image/jpg,image/jpeg]',
        ]);

        // Jika validasi gagal
        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data produk lama dari database
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        // Ambil file gambar jika ada
        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            // Hapus gambar lama jika ada dan file baru valid
            if (!empty($product['gambar']) && file_exists('uploads/gambarProduct/' . $product['gambar'])) {
                unlink('uploads/gambarProduct/' . $product['gambar']);
            }

            // Simpan gambar baru
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/gambarProduct', $namaGambar);
        } else {
            // Jika tidak ada gambar baru, gunakan gambar lama yang ada
            $namaGambar = $product['gambar'];
        }

        // Simpan data produk yang telah diperbarui ke database
        $this->productModel->update($id, [
            'nama_product' => $this->request->getPost('nama_product'),
            'kategori_id'  => $this->request->getPost('kategori_id'),
            'stok'         => $this->request->getPost('stok'),
            'harga'        => $this->request->getPost('harga'),
            'gambar'       => $namaGambar,
        ]);

        // Redirect ke halaman sukses
        return redirect()->to('/admin/product')->with('success', 'Data produk berhasil diperbarui.');
    }
}
