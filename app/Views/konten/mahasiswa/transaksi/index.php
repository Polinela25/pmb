<?= $this->extend('layout/page') ?>

<?= $this->section('content') ?>
<h4 class="py-3 mb-4"><a href="/admin/product"><span class="text-muted fw-light">Product Ari Andika</span></a></h4>
<div class="row">
    <div class="col-lg-12 col-sm-12 mb-3">
        <div class="card">
            <div class="card-body">
                <h4 class="text-warning">Informasi Toko Ari Andika</h4>
                
                <ol>
                    <li>Barang yang sudah di beli tidak bisa di ubah atau pun di kembalikan pada Toko Ari Andika.</li>

                </ol>
                <p>Oleh sebab itu jika ingin membeli harus mengisi dengam benar</p>
            </div>
        </div>
    </div>
</div>


<div class="row">

    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="row ">
                <div class="col-lg-6">
                    <h5 class="card-header">Transaksi Ari Andika</h5>
                </div>

                <div class="col-lg-12">

                    <div class="table-responsive">

                        <table class="table p-4" id="example" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama Product</th>
                                    <th>Kategori</th>
                                    
                                    <th>Harga</th>
                                    <th>Jumlah Product</th>
                                    <th>Total</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($transaksi as $row) : ?>

                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><img src="/uploads/gambarProduct/<?= $row['gambar'] ?>" alt="gambar" width="50"></td>
                                        <td><?= $row['nama_product'] ?></td>
                                        <td><?= $row['nama_kategori'] ?></td>
                                     
                                        <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                                        <td><?= $row['jumlah_product'] ?></td>
                                        <td><?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                      

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



<?= $this->endSection() ?>