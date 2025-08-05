<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><small><?= htmlspecialchars($this->session->userdata('level'), ENT_QUOTES, 'UTF-8'); ?></small></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('Admin/dataListPenilaian') ?>"><small><?= htmlspecialchars($parent, ENT_QUOTES, 'UTF-8'); ?></small></a></li>
                        <li class="breadcrumb-item active"><small><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?></small></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
            
            <?php if(validation_errors()) : ?>
                <!-- Row Note -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-exclamation-triangle"></i> Validation Error:</h5>
                            <?= validation_errors(); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <!--/. Col -->
                </div>
            <?php endif; ?>
            
            <?php if($this->session->flashdata('message') == TRUE) : ?>
                <!-- Row Note -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-info"></i> Note:</h5>
                            <?= $this->session->flashdata('message'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <!--/. Col -->
                </div>
            <?php endif; ?>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit"></i> Edit Penilaian Karyawan
                            </h3>
                            <div class="card-tools">
                                <a href="<?= base_url('Admin/dataListPenilaian') ?>" class="btn btn-tool" title="Kembali ke List">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="<?= base_url('Admin/listPenilaianEdit/' . $this->encrypt->encode($onepel->id)) ?>" method="post" id="editPenilaianForm">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="z" value="<?= $onepel->id; ?>">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="karyawan"><i class="fas fa-user"></i> Nama Karyawan <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="karyawan" name="karyawan" required>
                                                <option value="">-- Pilih Karyawan --</option>
                                                <?php foreach ($karyawanAll as $karyawan) : ?>
                                                    <option value="<?= $karyawan->id; ?>" <?= ($karyawan->id == $onepel->pegawai_id) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($karyawan->nama, ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($karyawan->nik, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?= form_error('karyawan', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="indikatorPenilaian"><i class="fas fa-clipboard-list"></i> Indikator Penilaian <span class="text-danger">*</span></label>
                                            <select class="form-control select2" id="indikatorPenilaian" name="indikatorPenilaian" required>
                                                <option value="">-- Pilih Indikator --</option>
                                                <?php foreach ($indikatorAll as $indikator) : ?>
                                                    <option value="<?= $indikator->id; ?>" <?= ($indikator->id == $onepel->indikator_id) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($indikator->code, ENT_QUOTES, 'UTF-8'); ?> - <?= htmlspecialchars($indikator->violation_name, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?= form_error('indikatorPenilaian', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggalPenilaian"><i class="fas fa-calendar-alt"></i> Tanggal Penilaian <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tanggalPenilaian" name="tanggalPenilaian" value="<?= $onepel->date; ?>" required>
                                            <?= form_error('tanggalPenilaian', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pelapor"><i class="fas fa-user-tie"></i> Pelapor</label>
                                            <input type="text" class="form-control" value="<?= htmlspecialchars($onepel->pelapor_nama ? $onepel->pelapor_nama : 'Tidak Diketahui', ENT_QUOTES, 'UTF-8'); ?>" readonly style="background-color: #f8f9fa;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="catatan"><i class="fas fa-sticky-note"></i> Catatan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="catatan" name="catatan" rows="4" placeholder="Masukkan catatan penilaian..." required><?= htmlspecialchars($onepel->catatan, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                    <?= form_error('catatan', '<small class="text-danger">', '</small>'); ?>
                                    <small class="form-text text-muted">Berikan catatan yang jelas dan detail mengenai penilaian ini.</small>
                                </div>
                                
                                <hr>
                                
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-primary btn-lg mr-2">
                                        <i class="fas fa-save"></i> Update Penilaian
                                    </button>
                                    <a href="<?= base_url('Admin/dataListPenilaian') ?>" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-times"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-muted">
                            <small><i class="fas fa-info-circle"></i> Pastikan semua data yang diisi sudah benar sebelum menyimpan perubahan.</small>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Initialize Select2 -->
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Form validation
    $('#editPenilaianForm').on('submit', function(e) {
        var isValid = true;
        var errorMessage = '';
        
        // Check required fields
        if ($('#karyawan').val() === '') {
            isValid = false;
            errorMessage += '- Nama Karyawan harus dipilih\n';
        }
        
        if ($('#indikatorPenilaian').val() === '') {
            isValid = false;
            errorMessage += '- Indikator Penilaian harus dipilih\n';
        }
        
        if ($('#tanggalPenilaian').val() === '') {
            isValid = false;
            errorMessage += '- Tanggal Penilaian harus diisi\n';
        }
        
        if ($('#catatan').val().trim() === '') {
            isValid = false;
            errorMessage += '- Catatan harus diisi\n';
        }
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Error',
                text: 'Mohon lengkapi field berikut:\n' + errorMessage,
                confirmButtonText: 'OK'
            });
            return false;
        }
        
        // Show loading
        Swal.fire({
            title: 'Menyimpan...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    });
});
</script>
