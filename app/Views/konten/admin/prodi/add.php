<?= $this->extend('layout/page') ?>
<?= $this->section('content') ?>

<h4 class="py-3 mb-4">Tambah Prodi</h4>

<div class="card">
    <div class="card-body">
        <form action="/admin/kode/prodi/save" method="post">
            <div class="mb-3">
                <label for="kode_prodi" class="form-label">Kode Prodi</label>
                <input type="text" class="form-control" name="kode_prodi" id="kode_prodi" required>
            </div>
            <div class="mb-3">
                <label for="nama_prodi" class="form-label">Nama Prodi</label>
                <input type="text" class="form-control" name="nama_prodi" id="nama_prodi" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/admin/kode/prodi" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
