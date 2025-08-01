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
                    <li class="breadcrumb-item"><small><?= $this->session->userdata('level') ;?></small></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('User/listPenilaian');?>"><small><?= $parent ;?></small></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('User/listPenilaianEdit/'.$this->encrypt->encode($onepel->id_penilaian));?>"><small><?= $page ;?></small></a></li>
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
                  <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('User/listPenilaian');?>">
                    <i class="fas fa-arrow-left"></i>&ensp;Back
                  </a>
                </div>
                <div class="card-body">

                  <form action="<?= base_url('User/listPenilaianEdit/'.$this->encrypt->encode($onepel->id_penilaian).'')?>" method="post">

                    <input type="hidden" name="z" value="<?= $onepel->id_penilaian ;?>">

                    <div class="row ">

                      <div class="col-md-6">

                        <!-- form-group -->
                        <div class="form-group">
                          <label for="karyawan">Nama Karyawan</label>
                          <select id="karyawan" name="karyawan" class="form-control select2" style="width: 100%;">
                            <option value="">Pilih Karyawan</option>
                            <?php foreach($userAll as $karyawan): ?>
                              <option value="<?= $karyawan->id ?>" <?= (isset($onepel->pegawai_id) && $onepel->pegawai_id == $karyawan->id) ? 'selected' : '' ?>>
                                <?= $karyawan->nama ?>
                              </option>
                            <?php endforeach; ?>
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
                            <option value="">Pilih Indikator</option>
                            <?php foreach($pelanggaranAll as $indikator): ?>
                              <option value="<?= $indikator->id ?>" <?= (isset($onepel->indikator_id) && $onepel->indikator_id == $indikator->id) ? 'selected' : '' ?>>
                                <?= $indikator->violation_name ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
                          <?= form_error('indikatorPenilaian', '<small class="text-danger pl-3">', '</small>');?>
                        </div>
                        <!-- /.form-group -->

                      </div>

                    </div>
                    <!-- /.row -->

                    <!-- Tanggal Penilaian -->
                    <div class="form-group">
                      <label for="tanggalPenilaian" class="col-form-label">Tanggal Penilaian</label>
                      <input type="date" name="tanggalPenilaian" class="form-control" id="tanggalPenilaian" value="<?= isset($onepel->date) ? $onepel->date : date('Y-m-d') ?>">
                      <?= form_error('tanggalPenilaian', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Tanggal Penilaian -->

                    <div class="form-group text-right">
                      <button type="submit" class="btn btn-warning btn-sm ">Update &ensp;<i class="fas fa-edit"></i></button>
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
