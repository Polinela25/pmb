<?= $this->extend('layout/page') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="card-header">Tambah Data Product</h5>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="/admin/product" class="btn btn-dark me-3 mt-3"><i class='bx bx-arrow-back'></i> Kembali</a>
                </div>
                <div class="col-lg-12 p-5">
                    <!-- Form dengan enctype untuk file upload -->
                    <form action="/pelanggan/transaksi/add" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="product_id">Nama Product</label>
                                <!-- Menampilkan nama produk pada input teks -->
                                <input type="text" class="form-control" id="nama_product" name="nama_product" value="<?= $product['nama_product'] ?>" readonly>

                                <!-- Input tersembunyi untuk mengirimkan product_id -->
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label" for="kategori_id">Kategori</label>
                                <input type="text" class="form-control" id="kategori_id" name="kategori_id" value="<?= $kategori['nama_kategori'] ?? 'Tidak tersedia' ?>" readonly>
                            </div>

                            <div class="col-lg-6">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="number" class="form-control" id="harga" name="harga" value="<?= $product['harga'] ?>" readonly>
                            </div>

                            <div class="col-lg-6">
                                <label for="jumlah_product" class="form-label">Jumlah Product</label>
                                <input type="number" class="form-control" id="jumlah_product" name="jumlah_product" oninput="updateTotalHarga()">
                            </div>

                            <div class="col-lg-6">
                                <label for="total_harga" class="form-label">Total</label>
                                <input type="number" class="form-control" id="total_harga" name="total_harga" readonly>
                            </div>

                        </div>

                        <div class="col-lg-6 mt-5">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTotalHarga() {
        const harga = document.getElementById('harga').value;
        const jumlah = document.getElementById('jumlah_product').value;
        const total = harga * jumlah;
        document.getElementById('total_harga').value = total;
    }
</script>

<?= $this->endSection() ?>