<?= $this->extend('layout/page') ?>
<?= $this->section('content') ?>

<h4 class="py-3 mb-4"><span class="fw-light">Profil Mahasiswa</span></h4>


<?php if ($profilTidakLengkap): ?>
    <div class="alert alert-warning">
        Profil Anda belum lengkap. Silakan hubungi admin untuk melengkapi data seperti jurusan, prodi, tahun akademik, atau tanggal lahir.
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-10 mb-4 order-0">
        <form action="<?= base_url('/mahasiswa/profil/update') ?>" method="post">
    <div class="card mb-4">
        <h5 class="card-header">Data Mahasiswa</h5>
        <div class="card-body">
            <div class="row">

                <div class="mb-3 col-md-6">
                    <label class="form-label">Username</label>
                    <input class="form-control" type="text" value="<?= esc($mahasiswa['username']) ?>" readonly>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="passwordField">
                        <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer">
                            <i class="bx bx-show" id="eyeIcon"></i>
                        </span>
                    </div>
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Nama</label>
                    <input class="form-control" type="text" name="nama" value="<?= esc($mahasiswa['nama']) ?>">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Tanggal Lahir</label>
                    <input class="form-control" type="date" name="tgl_lahir" value="<?= esc($mahasiswa['tgl_lahir']) ?>">
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Jurusan</label>
                    <select class="form-control" name="jurusan_id">
                        <option value="">-- Pilih Jurusan --</option>
                        <?php foreach ($jurusan as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= $item['id'] == $mahasiswa['jurusan_id'] ? 'selected' : '' ?>>
                                <?= $item['nama_jurusan'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Program Studi</label>
                    <select class="form-control" name="prodi_id">
                        <option value="">-- Pilih Prodi --</option>
                        <?php foreach ($prodi as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= $item['id'] == $mahasiswa['prodi_id'] ? 'selected' : '' ?>>
                                <?= $item['nama_prodi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3 col-md-6">
                    <label class="form-label">Tahun Akademik</label>
                    <select class="form-control" name="tahun_id">
                        <option value="">-- Pilih Tahun Akademik --</option>
                        <?php foreach ($tahun as $item): ?>
                            <option value="<?= $item['id'] ?>" <?= $item['id'] == $mahasiswa['tahun_id'] ? 'selected' : '' ?>>
                                <?= $item['kode_tahun'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>
    </div>
</div>

<!-- Script toggle password visibility -->
<script>
function togglePassword() {
    const passwordField = document.getElementById('passwordField');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('bx-show');
        eyeIcon.classList.add('bx-hide');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('bx-hide');
        eyeIcon.classList.add('bx-show');
    }
}
</script>

<?= $this->endSection() ?>
