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
                <li class="breadcrumb-item"><a href="<?= base_url('admin/pengaturanPengguna')?>"><small><?= $page ;?></small></a></li>
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
      <section class="content">
        <div class="container-fluid">
          <!-- Default box -->
          <div class="card card-outline card-info">
            <div class="card-header">
              <h4 class="card-title " text-align="center"><strong><?= $page; ?></strong></h4>
              <a class="btn btn-sm btn-outline-info float-right" href="<?= base_url('Admin/pengaturanPenggunaAdd')?>">
                <i class="fas fa-plus"></i> Add Data
              </a>
            </div>
            <div class="card-body">
              <!-- SEARCH FORM -->

              <div class="input-group ">
                <input class="form-control col-sm-12" name="seachPengguna" id="seachPengguna" type="text" placeholder="Search By FullName and Username" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-primary">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <table id="penggunaData" class="table table-bordered table-striped display nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">FullName</th>
                    <th scope="col">Level</th>
                    <th scope="col">Status</th>
                    <th scope="col" >Action</th>
                  </tr>
                </thead>
              </table>


              <!-- /.row -->
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.Container Fluid -->
      </section>
      <!-- /.content -->

    </div>
    <!-- Barang Hapus Modal-->
