<div class="content-wrapper">
          <!-- Content Header (Page header) -->
          <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0 text-dark"><?= $page ;?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><small><?= $this->session->userdata('level');?></small></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('Admin/listPenilaian');?>"><small><?= $parent ;?></small></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('Admin/listPenilaianAdd/');?>"><small><?= $page ;?></small></a></li>
                  </ol>
                </div><!-- /.col -->
              </div><!-- /.row -->
              <?php if(validation_errors()) : ?>
                <!-- Row Note -->
                <div class="row">
                  <div class="col-12">
                    <div class="alert callout callout-info bg-danger" role="alert">
                      <h5><i class="fas fa-info"></i> Note:</h5>
                      <?= validation_errors(); ?>
                    </div>
                  </div>
                  <!--/. Col -->
                </div>
              <?php endif ;?>
              <?php if($this->session->flashdata('message') == TRUE) : ?>
                <!-- Row Note -->
                <div class="row">
                  <div class="col-12">
                    <div class="alert callout callout-info bg-danger" role="alert">
                      <h5><i class="fas fa-info"></i> Note:</h5>
                      <?= $this->session->flashdata('message'); ?>
                    </div>
                  </div>
                  <!--/. Col -->
                </div>
              <?php endif ;?>
              
              <?php if($this->session->flashdata('success')) : ?>
                <!-- Success Message -->
                <div class="row">
                  <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <h5><i class="fas fa-check"></i> Success:</h5>
                      <?= $this->session->flashdata('success'); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endif ;?>
              
              <?php if($this->session->flashdata('error')) : ?>
                <!-- Error Message -->
                <div class="row">
                  <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <h5><i class="fas fa-exclamation-triangle"></i> Error:</h5>
                      <?= $this->session->flashdata('error'); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endif ;?>
            </div><!-- /.container-fluid -->
          </div>
          <!-- /.content-header -->

          <!-- Main content -->
          <section class="content ">
            <div class="container-fluid col-sm-8">
              <!-- Default box -->
              <div class="card card-outline card-info">
                <div class="card-header">
                  <h4 class="card-title " text-align="center"><strong><?= $page; ?></strong></h4>
                  <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('Admin/listPenilaian');?>">
                    <i class="fas fa-arrow-left"></i>&ensp;Back
                  </a>
                </div>
                <div class="card-body">

                    <form action="<?= base_url('Admin/dataListPenilaianEdit')?>" method="POST">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="row ">

                      <div class="col-md-6">

                        <!-- form-group -->
                        <div class="form-group">
                          <label for="karyawan">Nama Karyawan</label>
                          <select id="karyawan" name="karyawan" class="form-control select2" style="width: 100%;">
                            <option value="" selected="selected">Tulis NIP / Nama Karyawan</option>
                            <?php
                        if(isset($userAll)) {
                          foreach ($userAll as $karyawan) {
                            $selected = ($karyawan->id == $onepel->pegawai_id) ? 'selected' : '';
                            echo '<option value="'.$karyawan->id.'" '.$selected.'>'.$karyawan->nama.' - '.$karyawan->nik.'</option>';
                          }
                        }
                        ?>
                          </select>
                          <?= form_error('karyawan', '<small class="text-danger pl-3">', '</small>');?>
                        </div>
                        <!-- /.form-group -->

                      </div>
                      <div class="col-md-6">

                        <!-- form-group -->
                        <div class="form-group">
                          <label for="indikatorPenilaian">Indikator Penilaian</label>
                          <select id="indikatorPenilaian" name="indikatorPenilaian" class="form-control select2" style="width: 100%;">
                            <option value="" selected="selected">Tulis Kode / Indikator Penilaian</option>
                            <?php
                        if(isset($pelanggaranAll)) {
                          foreach ($pelanggaranAll as $indikator) {
                            $selected = ($indikator->id == $onepel->indikator_id) ? 'selected' : '';
                            echo '<option value="'.$indikator->id.'" '.$selected.'>'.$indikator->violation_name.'</option>';
                          }
                        }
                        ?>
                          </select>
                          <?= form_error('indikatorPenilaian', '<small class="text-danger pl-3">', '</small>');?>
                        </div>
                        <!-- /.form-group -->

                      </div>

                    </div>
                    <!-- /.row -->

                    <!-- Pelapor -->
                    <div class="row ">
											<div class="col-md-6">
                      <label for="pelapor" class="col-form-label">Pelapor</label>
                      <input type="text" name="pelapor" id="pelapor" class="form-control" value="<?= $user->nama_pegawai ?>" readonly>
                      <?= form_error('pelapor', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
										<div class="col-md-6">
											<label for="tanggalPelaporan" class="col-form-label">Tanggal Pelaporan</label>
											<input type="date" name="tanggalPelaporan" id="tanggalPelaporan" class="form-control" value="<?= set_value('tanggalPenilaian', $onepel->date)?>">
											<?= form_error('tanggalPelaporan', '<small class="text-danger pl-3">', '</small>');?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label for="catatanPenilaian" class="col-form-label">Catatan</label>
											<textarea name="catatanPenilaian" id="catatanPenilaian" class="form-control" placeholder="Catatan Penilaian" style="width: 100%"><?= set_value('catatan', $onepel->catatan)?></textarea>
											<?= form_error('catatanPenilaian', '<small class="text-danger pl-3">', '</small>');?>
										</div>
									</div>
                    <!-- / Pelapor -->
									<br>
                    <div class="form-group text-right">
                      <a class="btn btn-danger btn-sm" href="<?= base_url('Admin/dataListPenilaianEdit');?>"><i class="fa fa-undo"></i>&ensp;Reset</a>
                      <button type="submit" class="btn btn-primary btn-sm ">Submit &ensp;<i class="fas fa-arrow-right"></i></button>
                    </div> 

                  </form>


                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.Container Fluid -->
          </section>
          <!-- /.content -->
          
        </div>
