<?= $this->extend('layout/page') ?>

<?= $this->section('content') ?>

<section class="section dashboard">
    <div class="row">
        <!-- Sales Card -->
        <div class="col-12 col-md-4 mb-3">
            <div class="card info-card sales-card">
                <div class="card-body">
                    <h5 class="card-title">Jumlah Kategori</h5>
                    <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class="bi bi-cart"></i>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div><!-- End Sales Card -->

    </div>
</section>

<?= $this->endSection() ?>