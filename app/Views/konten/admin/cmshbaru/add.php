<?= $this->extend('layout/page') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="card-header">Import Data Mahasiswa dari Excel</h5>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="/admin/cmshbaru" class="btn btn-dark me-3 mt-3"><i class='bx bx-arrow-back'></i> Kembali</a>
                </div>

                <div class="col-lg-12 p-5">
                    <form method="POST" action="/admin/cmshbaru/importExcel" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="file_excel" class="form-label">File Excel (.xlsx)</label>
                                <input type="file" class="form-control" name="file_excel" id="file_excel" accept=".xlsx" required>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-5">
                            <button type="submit" class="btn btn-primary"><i class='bx bx-upload'></i> Import</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
