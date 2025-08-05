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
                    <li class="breadcrumb-item"><small>Admin</small></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/pengaturanPengguna');?>"><small><?= htmlspecialchars($parent, ENT_QUOTES, 'UTF-8') ;?></small></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/pengaturanPenggunaDetail/'.$this->encrypt->encode($oneUsers->id));?>"><small><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ;?></small></a></li>
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
                  <h4 class="card-title " text-align="center"><strong><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?></strong></h4>
                  <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('admin/pengaturanPengguna');?>">
                    <i class="fas fa-arrow-left"></i>&ensp;Back
                  </a>
                </div>
                <div class="card-body">

                  <form action="<?= base_url('admin/pengaturanPenggunaAdd')?>" method="post">

                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <!-- Fullname -->
                    <div class="form-group">
                      <label for="detailPenggunaFullname" class="col-form-label">Nama Pegawai</label>
                      <input type="text" name="fullname" class="form-control" id="detailPenggunaFullname" placeholder="Fullname" value="<?= htmlspecialchars($oneUsers->nama_pegawai, ENT_QUOTES, 'UTF-8')?>" readonly />
                      <?= form_error('fullname', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Fullname -->

                    <!-- Email - Hidden since not available in current database structure -->
                    <!-- <div class="form-group">
                      <label for="detailPenggunaEmail" class="col-form-label">Email</label>
                      <input type="text" name="email" class="form-control" id="detailPenggunaEmail" placeholder="Email" value="" readonly/>
                      <?= form_error('email', '<small class="text-danger pl-3">', '</small>');?>
                    </div> -->
                    
                    <!-- Username -->
                    <div class="form-group">
                      <label for="detailPenggunaUsername" class="col-form-label">Username</label>
                      <input type="text" name="username" class="form-control" id="detailPenggunaUsername" placeholder="Username" value="<?= htmlspecialchars($oneUsers->username, ENT_QUOTES, 'UTF-8')?>" readonly />
                      <?= form_error('username', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Username -->



                    <!-- Level -->
                    <div class="form-group">
                      <label for="detailPenggunaLevel" class="col-form-label">Level</label>
                      <input type="text" name="username" class="form-control" id="detailPenggunaLevel" placeholder="Username" value="<?= htmlspecialchars($oneUsers->level, ENT_QUOTES, 'UTF-8')?>" readonly />
                      <?= form_error('password', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Level -->

                    <div class="form-group text-right">
                      <a class="btn btn-warning btn-sm" href="<?= base_url('admin/pengaturanPenggunaEdit/'.$this->encrypt->encode($oneUsers->id));?>"><i class="fa fa-edit"></i>&ensp;Edit</a>
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
