<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ;?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><small><?= htmlspecialchars($this->session->userdata('level'), ENT_QUOTES, 'UTF-8') ;?></small></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('User/listPelanggaran')?>"><small><?= htmlspecialchars($parent, ENT_QUOTES, 'UTF-8') ;?></small></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('User/listPelanggaranDetail/'.$this->encrypt->encode($onepel->id_penilaian));?>"><small><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ;?></small></a></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Detail Pelanggaran</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Nama Karyawan</strong>
                            <p class="text-muted">
                                <?= htmlspecialchars($onepel->nama, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <hr>
                            <strong><i class="fas fa-map-marker-alt mr-1"></i> NIP</strong>
                            <p class="text-muted"><?= htmlspecialchars($onepel->nik, ENT_QUOTES, 'UTF-8') ?></p>
                            <hr>
                            <strong><i class="fas fa-pencil-alt mr-1"></i> Jenis Pelanggaran</strong>
                            <p class="text-muted">
                                <?= htmlspecialchars($onepel->violation_name, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <hr>
                            <strong><i class="far fa-file-alt mr-1"></i> Catatan</strong>
                            <p class="text-muted"><?= htmlspecialchars($onepel->catatan, ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
