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
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/listPenilaianEdit/'.$this->encrypt->encode($onepel->id));?>"><small><?= $page ;?></small></a></li>
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

                  <form action="<?= base_url('admin/listPenilaianEdit/'.$this->encrypt->encode($onepel->id))?>" method="post">
                    
                    <!-- Hidden ID -->
                    <input type="hidden" name="z" value="<?= $onepel->id ?>">
                    
                    <!-- Karyawan -->
                    <div class="form-group">
                      <label for="karyawan" class="col-form-label">Nama Karyawan</label>
                      <select name="karyawan" id="karyawan" class="form-control select2" style="width: 100%;" >
                        <option value="" selected>Pilih Karyawan</option>
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
                    <!-- / Karyawan -->

                    <!-- Indikator Penilaian -->
                    <div class="form-group">
                      <label for="indikatorPenilaian" class="col-form-label">Indikator Penilaian</label>
                      <select name="indikatorPenilaian" id="indikatorPenilaian" class="form-control select2" style="width: 100%;" >
                        <option value="" selected>Pilih Indikator Penilaian</option>
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
                    <!-- / Indikator Penilaian -->

                    <!-- Tanggal Penilaian -->
                    <div class="form-group">
                      <label for="tanggalPenilaian" class="col-form-label">Tanggal Penilaian</label>
                      <input type="date" name="tanggalPenilaian" class="form-control" id="tanggalPenilaian" value="<?= set_value('tanggalPenilaian', $onepel->date)?>">
                      <?= form_error('tanggalPenilaian', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Tanggal Penilaian -->

                    <!-- Catatan -->
                    <div class="form-group">
                      <label for="catatan" class="col-form-label">Catatan</label>
                      <textarea name="catatan" class="form-control" id="catatan" rows="4" placeholder="Masukkan catatan..."><?= set_value('catatan', $onepel->catatan)?></textarea>
                      <?= form_error('catatan', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Catatan -->

                    <div class="form-group text-right">
                      <a class="btn btn-danger btn-sm" href="<?= base_url('admin/listPenilaian');?>"><i class="fa fa-times"></i>&ensp;Cancel</a>
                      <button type="submit" class="btn btn-primary btn-sm ">Update &ensp;<i class="fas fa-save"></i></button>
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
