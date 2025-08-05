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
            <li class="breadcrumb-item"><a href="<?= base_url('User/pengaturanUserEdit')?>"><small><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ;?></small></a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <?php if($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $this->session->flashdata('success'); ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h4 class="card-title " text-align="center"><strong>Ubah Password</strong></h4>
                  <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('User');?>">
                    <i class="fas fa-arrow-left"></i>&ensp;Back
                  </a>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="<?= base_url('User/updatePassword');?>" method="post">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
              <div class="card-body">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user->id, ENT_QUOTES, 'UTF-8');?>">
                
                <div class="form-group">
                  <label for="new_password">Password Baru</label>
                  <input type="password" name="new_password" class="form-control <?= (form_error('new_password')) ? 'is-invalid' : '' ?>" id="new_password" placeholder="Masukkan password baru" value="<?= htmlspecialchars(set_value('new_password'), ENT_QUOTES, 'UTF-8')?>">
                  <?= form_error('new_password', '<small class="text-danger pl-3">', '</small>');?>
                </div>
                
                <div class="form-group">
                  <label for="repeat_password">Konfirmasi Password</label>
                  <input type="password" name="repeat_password" class="form-control <?= (form_error('repeat_password')) ? 'is-invalid' : '' ?>" id="repeat_password" placeholder="Konfirmasi password baru" value="<?= htmlspecialchars(set_value('repeat_password'), ENT_QUOTES, 'UTF-8')?>">
                  <?= form_error('repeat_password', '<small class="text-danger pl-3">', '</small>');?>
                  <div id="password-match-info" class="mt-2" style="display: none;">
                    <small id="password-match-text" class="text-danger">
                      <i class="fas fa-times-circle"></i> Password dan konfirmasinya tidak sama
                    </small>
                  </div>
                  <div id="password-match-success" class="mt-2" style="display: none;">
                    <small class="text-success">
                      <i class="fas fa-check-circle"></i> Password sudah sama
                    </small>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary" id="submit-btn">
                  <i class="fas fa-save"></i> Ubah Password
                </button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        
        <div class="col-md-6">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Informasi</h3>
            </div>
            <div class="card-body">
              <div class="callout callout-info">
                <h5><i class="icon fas fa-info"></i> Petunjuk!</h5>
                <ul>
                  <li>Password minimal 8 karakter</li>
                  <li>Pastikan password baru dan konfirmasi password sama</li>
                  <li>Gunakan kombinasi huruf dan angka untuk keamanan yang lebih baik</li>
                  <li>Jangan gunakan password yang mudah ditebak</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<script>
$(document).ready(function() {
    // Function to check password match and length
    function checkPasswordMatch() {
        var newPassword = $('#new_password').val();
        var repeatPassword = $('#repeat_password').val();
        
        // Check password length for new password field
        if (newPassword.length > 0 && newPassword.length < 8) {
            $('#new_password').removeClass('is-valid').addClass('is-invalid');
        } else if (newPassword.length >= 8) {
            $('#new_password').removeClass('is-invalid').addClass('is-valid');
        } else {
            $('#new_password').removeClass('is-invalid is-valid');
        }
        
        // Only show validation if repeat password field has content
        if (repeatPassword.length > 0) {
            // If new password is valid (8+ chars), only check if passwords match
            if (newPassword.length >= 8) {
                if (newPassword === repeatPassword) {
                    // Passwords match and new password is valid
                    $('#password-match-info').hide();
                    $('#password-match-success').show();
                    $('#repeat_password').removeClass('is-invalid').addClass('is-valid');
                    $('#submit-btn').prop('disabled', false);
                } else {
                    // Passwords don't match but new password is valid - only show error on confirmation field
                    $('#password-match-info').show();
                    $('#password-match-success').hide();
                    $('#repeat_password').removeClass('is-valid').addClass('is-invalid');
                    // Keep new password field as valid since it meets requirements
                    $('#new_password').removeClass('is-invalid').addClass('is-valid');
                    $('#submit-btn').prop('disabled', true);
                }
            } else {
                // New password is invalid (less than 8 chars) - don't show confirmation validation
                $('#password-match-info').hide();
                $('#password-match-success').hide();
                $('#repeat_password').removeClass('is-invalid is-valid');
                $('#submit-btn').prop('disabled', true);
            }
        } else {
            // Hide all validation messages when repeat password is empty
            $('#password-match-info').hide();
            $('#password-match-success').hide();
            $('#repeat_password').removeClass('is-invalid is-valid');
            
            // Enable submit only if new password is valid
            if (newPassword.length >= 8) {
                $('#submit-btn').prop('disabled', false);
            } else {
                $('#submit-btn').prop('disabled', true);
            }
        }
    }
    
    // Check password match on keyup for both fields
    $('#new_password, #repeat_password').on('keyup', function() {
        checkPasswordMatch();
    });
    
    // Also check on paste events
    $('#new_password, #repeat_password').on('paste', function() {
        setTimeout(checkPasswordMatch, 100);
    });
});
</script>
