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
                    <li class="breadcrumb-item"><small>Admin</small></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/pengaturanPengguna');?>"><small><?= $parent ;?></small></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/pengaturanPenggunaEdit/'.$this->encrypt->encode($oneUsers->id));?>"><small><?= $page ;?></small></a></li>
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
                  <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('admin/pengaturanPengguna');?>">
                    <i class="fas fa-arrow-left"></i>&ensp;Back
                  </a>
                </div>
                <div class="card-body">

                  <form action="<?= base_url('admin/pengaturanPenggunaEdit/'.$this->encrypt->encode($oneUsers->id))?>" method="post">

                    <input type="hidden" name="z" value="<?= $oneUsers->id ;?>">
										
										<!-- Username -->
										<div class="form-group">
											<label for="editPenggunaUsername" class="col-form-label">Username</label>
											<input type="text" name="username" class="form-control" id="editPenggunaUsername" placeholder="Username" value="<?= $oneUsers->username?>" disabled/>
											<?= form_error('username', '<small class="text-danger pl-3">', '</small>');?>
										</div>
										<!-- / Username -->

                    <!-- Nama Pegawai -->
                    <div class="form-group">
                      <label for="editPenggunaFullname" class="col-form-label">Nama Pegawai</label>
                      <input type="text" name="fullname" class="form-control" id="editPenggunaFullname" placeholder="Fullname" value="<?= $oneUsers->nama_pegawai ? $oneUsers->nama_pegawai : 'Admin User' ?>" disabled />
                      <?= form_error('fullname', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Nama Pegawai -->
                    

                    <!-- Password -->
                    <div class="form-group">
                      <label for="editPenggunaPassword" class="col-form-label">Password</label>
                      <div class="input-group">
                        <input type="password" name="password" class="form-control" id="editPenggunaPassword" placeholder="Password" value="<?= set_value('password')?>" />
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" id="togglePassword"><i class="fas fa-eye"></i></button>
                        </div>
                      </div>
                      <p class="text-danger">*Catatan : Kosongkan Jika Tidak Diubah</p>
                      <?= form_error('password', '<small class="text-danger pl-3">', '</small>');?>
                    </div>

                    <script>
                      document.getElementById('togglePassword').addEventListener('click', function () {
                        const passwordField = document.getElementById('editPenggunaPassword');
                        const icon = this.querySelector('i');
                        if (passwordField.type === 'password') {
                          passwordField.type = 'text';
                          icon.classList.remove('fa-eye');
                          icon.classList.add('fa-eye-slash');
                        } else {
                          passwordField.type = 'password';
                          icon.classList.remove('fa-eye-slash');
                          icon.classList.add('fa-eye');
                        }
                      });
                    </script>
                    <!-- / Password -->



                    <!-- Level -->
                    <div class="form-group">
                      <label for="detailPenggunaLevel" class="col-form-label">Level</label>
                      <select class="form-control select2" name="level" id="addPenggunaLevel">

                        <?php 
                        if($oneUsers->level == 'Admin'){
                          $output ='
                          <option value="Admin" selected>Admin</option>
                          <option value="User">User</option>
                          ';
                        }elseif($oneUsers->level == 'User'){
                          $output ='
                          <option value="Admin">Admin</option>
                          <option value="User" selected>User</option>
                          ';
                        }
                        echo $output;
                        ?>
                      </select>
                      <?= form_error('level', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <!-- / Level -->

                    <div class="form-group text-right">
                      <button type="submit" class="btn btn-warning btn-sm" ><i class="fa fa-edit"></i>&ensp;Edit</button>
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
