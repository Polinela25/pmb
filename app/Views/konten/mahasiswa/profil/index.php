<?= $this->extend('layout/page') ?>
<?= $this->section('content') ?>

<h4 class="py-3 mb-4"><span class="fw-light">Profil Mahasiswa</span></h4>


<?php if ($profilTidakLengkap): ?>
    <div class="alert alert-warning">
        Profil Anda belum lengkap. Silakan anda melengkapi data-data yang belum terisi.
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-10 mb-4 order-0">
        <form action="<?= base_url('/mahasiswa/profile/update') ?>" method="post">
    <div class="card mb-4">
        <h5 class="card-header">Data Mahasiswa</h5>
        <div class="card-body">
            <div class="row">

                <div class="mb-3 col-md-6">
                    <label class="form-label">Username</label>
                    <input class="form-control" type="text" value="<?= esc($mahasiswa['username'] ?? '') ?>" readonly>
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
                    <label class="form-label">NISN</label>
                    <input class="form-control" type="text" name="nisn" value="<?= esc($mahasiswa['nisn'] ?? '') ?>">
                </div>

                <!-- Sudah Ada: Nama -->

                <!-- Sudah Ada: Tanggal Lahir -->

                <!-- Tambahan: Nama Sekolah -->
                <div class="mb-3 col-md-6">
                    <label class="form-label">Nama Sekolah</label>
                    <input class="form-control" type="text" name="nama_sekolah" value="<?= esc($mahasiswa['nama_sekolah'] ?? '') ?>">
                </div>

                <!-- Tambahan: Tipe Sekolah -->
                <!-- Tipe Sekolah -->
                <div class="mb-3 col-md-6">
                    <label class="form-label">Tipe Sekolah *</label>
                    <select name="tipe_sekolah" id="tipe_sekolah" class="form-select" required>
                        <option value="">-</option>
                        <option value="SMA" <?= $mahasiswa['tipe_sekolah'] == 'SMA' ? 'selected' : '' ?>>SMA</option>
                        <option value="SMK" <?= $mahasiswa['tipe_sekolah'] == 'SMK' ? 'selected' : '' ?>>SMK</option>
                        <option value="MA" <?= $mahasiswa['tipe_sekolah'] == 'MA' ? 'selected' : '' ?>>MA</option>
                    </select>
                </div>

                <!-- Jurusan Otomatis -->
                <div class="mb-3 col-md-6">
                    <label class="form-label">Keahlian/Jurusan *</label>
                    <select name="jurusan_asal" id="jurusan" class="form-select" required>
                        <option value="">-</option>
                        <?php
                        $sma = ['IPA', 'IPS', 'Bahasa'];
                        $smk = ['Teknik Komputer dan Jaringan',
                                'Rekayasa Perangkat Lunak',
                                'Akuntansi dan Keuangan Lembaga',
                                'Teknik dan Bisnis Sepeda Motor',
                                'Agribisnis Tanaman Pangan',
                                'Agribisnis Tanaman Pangan dan Hortikultura',
                                'Agribisnis Pengolahan Hasil Pertanian'];
                        $ma = ['IPA', 'IPS', 'Keagamaan'];

                        $semua_jurusan = array_merge($sma, $smk, $ma);
                        foreach ($semua_jurusan as $item): ?>
                            <option value="<?= $item ?>" <?= $mahasiswa['jurusan_asal'] == $item ? 'selected' : '' ?>><?= $item ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tahun Lulus -->
                <div class="mb-3 col-md-6">
                    <label class="form-label">Tahun Lulus *</label>
                    <select name="tahun_lulus" class="form-select" required>
                        <option value="">-</option>
                        <?php for ($i = date('Y'); $i >= 2015; $i--): ?>
                            <option value="<?= $i ?>" <?= $mahasiswa['tahun_lulus'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>


                <!-- Tambahan: Email -->
                <div class="mb-3 col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="<?= esc($mahasiswa['email'] ?? '') ?>" readonly>
                </div>

                <!-- Tambahan: No HP -->
                <div class="mb-3 col-md-6">
                    <label class="form-label">No HP</label>
                    <input class="form-control" type="text" name="no_hp" value="<?= esc($mahasiswa['no_hp'] ?? '') ?>">
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
<script>
    const jurusanOptions = {
        SMA: ['IPA', 'IPS', 'Bahasa'],
        SMK: ['Teknik Komputer dan Jaringan',
            'Rekayasa Perangkat Lunak',
            'Akuntansi dan Keuangan Lembaga',
            'Teknik dan Bisnis Sepeda Motor',
            'Agribisnis Tanaman Pangan',
            'Agribisnis Tanaman Pangan dan Hortikultura',
            'Agribisnis Pengolahan Hasil Pertanian'],
        MA: ['IPA', 'IPS', 'Keagamaan']
    };

    document.getElementById('tipe_sekolah').addEventListener('change', function () {
        const selected = this.value;
        const jurusanSelect = document.getElementById('jurusan');
        jurusanSelect.innerHTML = '<option value="">-</option>'; // kosongkan dulu

        if (jurusanOptions[selected]) {
            jurusanOptions[selected].forEach(function (jurusan) {
                const opt = document.createElement('option');
                opt.value = jurusan;
                opt.text = jurusan;
                jurusanSelect.appendChild(opt);
            });
        }
    });

    // Trigger saat halaman pertama kali dimuat (agar jurusan terisi saat edit)
    window.addEventListener('DOMContentLoaded', function () {
        const currentTipe = document.getElementById('tipe_sekolah').value;
        const currentJurusan = "<?= $mahasiswa['jurusan_asal'] ?>";
        const jurusanSelect = document.getElementById('jurusan');

        if (jurusanOptions[currentTipe]) {
            jurusanOptions[currentTipe].forEach(function (jurusan) {
                const opt = document.createElement('option');
                opt.value = jurusan;
                opt.text = jurusan;
                if (jurusan === currentJurusan) {
                    opt.selected = true;
                }
                jurusanSelect.appendChild(opt);
            });
        }
    });
</script>

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
