<?= $this->extend('layout/page') ?>
<?= $this->section('content') ?>

<!-- Include Select2 dan jQuery -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<h4 class="py-3">Generate NPM Mahasiswa</h4>

<?php if ($disableForm): ?>
<div class="alert alert-warning">
    Profil Anda belum lengkap. Silakan lengkapi jurusan, prodi, tahun, dan tanggal lahir di halaman profil.
</div>
<?php endif; ?>

<div class="card p-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="nama" class="form-label">Nama Mahasiswa</label>
            <select id="nama" class="form-control" style="width: 100%" <?= $disableForm ? 'disabled' : '' ?>></select>
        </div>
        <div class="col-md-6">
            <label for="tgllahir" class="form-label">Tanggal Lahir</label>
            <input type="date" id="tgllahir" class="form-control" <?= $disableForm ? 'disabled' : '' ?> />
        </div>
    </div>

    <button id="generate" class="btn btn-primary" <?= $disableForm ? 'disabled' : '' ?>>Generate NPM</button>

    <div class="mt-4">
        <h5>Hasil:</h5>
        <div id="result" class="alert alert-info">NPM akan ditampilkan di sini</div>
    </div>
</div>

<?php if (!$disableForm): ?>
<script>
$(document).ready(function () {
    $('#nama').select2({
        placeholder: 'Ketik minimal 3 huruf...',
        minimumInputLength: 3,
        ajax: {
            url: '/mahasiswa/searchNama',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { query: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            }
        }
    });

    $('#nama').on('select2:select', function () {
        $('#generate').prop('disabled', false);
    });

    $('#generate').on('click', function () {
        let nama = $('#nama').val();
        let tgllahir = $('#tgllahir').val();

        fetch('/mahasiswa/generateNpm', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'nama=' + encodeURIComponent(nama) + '&tgllahir=' + encodeURIComponent(tgllahir)
        })
        .then(response => response.json())
        .then(data => {
            $('#result').text(data.npm);
        });
    });
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>
