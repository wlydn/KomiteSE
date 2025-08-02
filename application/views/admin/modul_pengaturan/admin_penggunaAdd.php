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
            <li class="breadcrumb-item"><a href="<?= base_url('admin/pengaturanPenggunaAdd');?>"><small><?= $page ;?></small></a></li>
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
    <div class="container-fluid">

      <div class="row">

        <div class="col-sm-8">
          <!-- Default box -->
          <div class="card card-outline card-info">
            <div class="card-header">
              <h4 class="card-title " text-align="center"><strong><?= $page; ?></strong></h4>
              <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('Admin/pengaturanPengguna');?>">
                <i class="fas fa-arrow-left"></i>&ensp;Back
              </a>
            </div>
            <div class="card-body">


              <form action="<?= base_url('Admin/pengaturanPenggunaAdd')?>" method="post">

                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <!-- Level -->
                <div class="form-group">
                  <label for="addPenggunaLevel" class="col-form-label">Level</label>
                  <select class="form-control" name="level" id="addPenggunaLevel">
                    <option value="">Silahkan Memilih Level</option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                  </select>
                  <?= form_error('level', '<small class="text-danger pl-3">', '</small>');?>
                </div>
                <!-- / Level -->


                <div id=addPenggunaAdmin style="display: none">

									 <!-- NIK -->
                  <div class="form-group">
                    <label for="addNIKAdmin" class="col-form-label">Silahkan Cari NIK Karyawan</label>
                    <select class="form-control select2" name="addNIKAdmin" id="addNIKAdmin" onchange="populateUserDataAdmin()">
                      <option value="">Tulis NIK / Nama Karyawan</option>
                      <?php
                      foreach ($userAll as $user) {
                        echo '<option value="'.$user->id.'" data-nik="'.$user->nik.'" data-nama="'.$user->nama.'">'.$user->nik.' / '. $user->nama .'</option>';
                      }
                      ;?>
                    </select>
                  </div>
                  <!-- / NIK -->

                  <!-- Fullname -->
                  <div class="form-group">
                    <label for="addPenggunaFullnameAdmin" class="col-form-label">Nama User</label>
                    <input type="text" name="fullnameAdmin" class="form-control" id="addPenggunaFullnameAdmin" placeholder="Nama User" readonly/>
                    <?= form_error('fullnameAdmin', '<small class="text-danger pl-3">', '</small>');?>
                  </div>
                  <!-- / Fullname -->
                  <!-- Username -->
                  <div class="form-group">
                    <label for="addPenggunaUsernameAdmin" class="col-form-label">Username (NIK)</label>
                    <input type="text" name="usernameAdmin" class="form-control" id="addPenggunaUsernameAdmin" placeholder="Username akan otomatis terisi dari NIK" readonly/>
                    <?= form_error('usernameAdmin', '<small class="text-danger pl-3">', '</small>');?>
                  </div>
                  <!-- / Username -->

                  <!-- Password -->
                  <div class="form-group">
                    <label for="addPenggunaPasswordAdmin" class="col-form-label">Password</label>
                    <input type="password" name="passwordAdmin" class="form-control" id="addPenggunaPasswordAdmin" placeholder="Password" value="<?= set_value('password')?>" />
                    <?= form_error('passwordAdmin', '<small class="text-danger pl-3">', '</small>');?>
                  </div>
                  <!-- / Password -->

                </div>

                <div id=addPenggunaUser style="display: none">

                  <!-- NIK -->
                  <div class="form-group">
                    <label for="addNIKUser" class="col-form-label">Silahkan Cari NIK Karyawan</label>
                    <select class="form-control select2" name="addNIKUser" id="addNIKUser" onchange="populateUserDataUser()">
                      <option value="">Tulis NIK / Nama Karyawan</option>
                      <?php
                      foreach ($userAll as $user) {
                        echo '<option value="'.$user->id.'" data-nik="'.$user->nik.'" data-nama="'.$user->nama.'">'.$user->nik.' / '. $user->nama .'</option>';
                      }
                      ;?>
                    </select>
                  </div>
                  <!-- / NIK -->

                  <!-- Fullname -->
                  <div class="form-group">
                    <label for="addPenggunaFullnameUser" class="col-form-label">Nama User</label>
                    <input type="text" name="fullnameUser" class="form-control" id="addPenggunaFullnameUser" placeholder="Nama User" readonly/>
                    <?= form_error('fullnameUser', '<small class="text-danger pl-3">', '</small>');?>
                  </div>
                  <!-- / Fullname -->
                  <!-- Username -->
                  <div class="form-group">
                    <label for="addPenggunaUsernameUser" class="col-form-label">Username (NIK)</label>
                    <input type="text" name="usernameUser" class="form-control" id="addPenggunaUsernameUser" placeholder="Username akan otomatis terisi dari NIK" readonly/>
                    <?= form_error('usernameUser', '<small class="text-danger pl-3">', '</small>');?>
                  </div>
                  <!-- / Username -->

                  <!-- Password -->
                  <div class="form-group">
                    <label for="addPenggunaPasswordUser" class="col-form-label">Password</label>
                    <input type="password" name="passwordUser" class="form-control" id="addPenggunaPasswordUser" placeholder="Password" value="<?= set_value('password')?>" />
                    <?= form_error('passwordUser', '<small class="text-danger pl-3">', '</small>');?>
                  </div>
                  <!-- / Password -->

                </div>

                <div class="form-group text-right">
                  <a class="btn btn-danger btn-sm" href="<?= base_url('Admin/pengaturanPenggunaAdd');?>"><i class="fa fa-undo"></i>&ensp;Reset</a>
                  <button type="submit" class="btn btn-primary btn-sm ">Submit &ensp;<i class="fas fa-arrow-right"></i></button>
                </div> 

              </form>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>


        <div class="col-sm-4">

          <!-- Default box -->
          <div class="card card-outline card-info">
            <div class="card-header">
              <h4 class="card-title " text-align="center"><strong>List Level</strong></h4>
              <div class="card-tools">
                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <ol>
                <li><b>Admin</b></li>
                <p>Untuk Level Admin Bisa Memasukkan Username Yang Diingkan</p>
                <li><b>User</b></li>
                <p>Untuk Level User Silahkan Mencari berdasarkan NIK Karyawan<br> <b>Contoh</b> 202507003</p>
              </ol>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

        </div>

      </div>


    </div>
    <!-- /.Container Fluid -->
  </section>
  <!-- /.content -->

</div>

<script>
function populateUserDataAdmin() {
    var select = document.getElementById('addNIKAdmin');
    var selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        var nik = selectedOption.getAttribute('data-nik');
        var nama = selectedOption.getAttribute('data-nama');
        
        // Populate fields for Admin level
        document.getElementById('addPenggunaUsernameAdmin').value = nik;
        document.getElementById('addPenggunaFullnameAdmin').value = nama;
    } else {
        // Clear fields if no selection
        document.getElementById('addPenggunaUsernameAdmin').value = '';
        document.getElementById('addPenggunaFullnameAdmin').value = '';
    }
}

function populateUserDataUser() {
    var select = document.getElementById('addNIKUser');
    var selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        var nik = selectedOption.getAttribute('data-nik');
        var nama = selectedOption.getAttribute('data-nama');
        
        // Populate fields for User level
        document.getElementById('addPenggunaUsernameUser').value = nik;
        document.getElementById('addPenggunaFullnameUser').value = nama;
    } else {
        // Clear fields if no selection
        document.getElementById('addPenggunaUsernameUser').value = '';
        document.getElementById('addPenggunaFullnameUser').value = '';
    }
}

// Show/hide form sections based on level selection
document.getElementById('addPenggunaLevel').addEventListener('change', function() {
    var level = this.value;
    var adminDiv = document.getElementById('addPenggunaAdmin');
    var userDiv = document.getElementById('addPenggunaUser');
    
    if (level === 'Admin') {
        adminDiv.style.display = 'block';
        userDiv.style.display = 'none';
        // Clear user fields when switching to admin
        document.getElementById('addPenggunaUsernameUser').value = '';
        document.getElementById('addPenggunaFullnameUser').value = '';
    } else if (level === 'User') {
        adminDiv.style.display = 'none';
        userDiv.style.display = 'block';
        // Clear admin fields when switching to user
        document.getElementById('addPenggunaUsernameAdmin').value = '';
        document.getElementById('addPenggunaFullnameAdmin').value = '';
    } else {
        adminDiv.style.display = 'none';
        userDiv.style.display = 'none';
        // Clear all fields when no level selected
        document.getElementById('addPenggunaUsernameAdmin').value = '';
        document.getElementById('addPenggunaFullnameAdmin').value = '';
        document.getElementById('addPenggunaUsernameUser').value = '';
        document.getElementById('addPenggunaFullnameUser').value = '';
    }
});
</script>
