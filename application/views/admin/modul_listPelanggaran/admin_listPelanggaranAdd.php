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
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/listPenilaian');?>"><small><?= $parent ;?></small></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/listPenilaianAdd');?>"><small><?= $page ;?></small></a></li>
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
                  <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('admin/listPenilaian');?>">
                    <i class="fas fa-arrow-left"></i>&ensp;Back
                  </a>
                </div>
                <div class="card-body">

                  <form action="<?= base_url('admin/listPenilaianAdd')?>" method="post">
                    
                    <!-- Pegawai/Karyawan -->
                    <div class="form-group">
                      <label for="pegawai" class="col-form-label">Pegawai/Karyawan</label>
                      <select name="pegawai" id="pegawai" class="form-control select2" style="width: 100%;" >
                        <option value="" selected>Pilih Pegawai/Karyawan</option>
                        <?php
                        if(isset($karyawanAll)) {
                          foreach ($karyawanAll as $karyawan) {
                            echo '<option value="'.$karyawan->id.'">'.$karyawan->nama.' - '.$karyawan->nik.'</option>';
                          }
                        }
                        ?>
                      </select>
                      <?= form_error('pegawai', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Pegawai -->

                    <!-- Pelapor -->
                    <div class="form-group">
                      <label for="pelapor" class="col-form-label">Pelapor</label>
                      <select name="pelapor" id="pelapor" class="form-control select2" style="width: 100%;" >
                        <option value="" selected>Pilih Pelapor</option>
                        <?php
                        if(isset($userAll)) {
                          foreach ($userAll as $user) {
                            echo '<option value="'.$user->id.'">'.$user->nama.'</option>';
                          }
                        }
                        ?>
                      </select>
                      <?= form_error('pelapor', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Pelapor -->

                    <!-- Indikator Penilaian -->
                    <div class="form-group">
                      <label for="indikator" class="col-form-label">Indikator Penilaian</label>
                      <select name="indikator" id="indikator" class="form-control select2" style="width: 100%;" >
                        <option value="" selected>Pilih Indikator Penilaian</option>
                        <?php
                        if(isset($indikatorAll)) {
                          foreach ($indikatorAll as $indikator) {
                            echo '<option value="'.$indikator->id.'">'.$indikator->violation_name.'</option>';
                          }
                        }
                        ?>
                      </select>
                      <?= form_error('indikator', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Indikator Penilaian -->

                    <!-- Tanggal -->
                    <div class="form-group">
                      <label for="tanggal" class="col-form-label">Tanggal</label>
                      <input type="date" name="tanggal" class="form-control" id="tanggal" value="<?= set_value('tanggal', date('Y-m-d'))?>">
                      <?= form_error('tanggal', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Tanggal -->

                    <!-- Catatan -->
                    <div class="form-group">
                      <label for="catatan" class="col-form-label">Catatan</label>
                      <textarea type="text" name="catatan" class="form-control" id="catatan" placeholder="Catatan tambahan (opsional)"><?= set_value('catatan')?></textarea>
                      <?= form_error('catatan', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Catatan -->

                    <div class="form-group text-right">
                      <a class="btn btn-danger btn-sm" href="<?= base_url('admin/listPenilaianAdd');?>"><i class="fa fa-undo"></i>&ensp;Reset</a>
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
