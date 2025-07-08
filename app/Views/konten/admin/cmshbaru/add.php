<?= $this->extend('layout/page') ?>

<?= $this->section('content') ?>

<div class="row">

    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="card-header">Tambah Data Kategori</h5>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="/admin/kategori" class="btn btn-dark me-3 mt-3"><i class='bx bx-arrow-back'></i> Kembali</a>
                </div>
                <div class="col-lg-12 p-5">
                    <form method="POST" action="/admin/kategori/save">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-lg-6">
                                <label for="nama_kategori" class="form-label">Kategori</label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
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

</script>

<?= $this->endSection() ?>