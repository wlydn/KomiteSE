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
                <li class="breadcrumb-item"><a href="<?= base_url('User/listPelanggaran')?>"><small><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ;?></small></a></li>
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
              <h4 class="card-title " text-align="center"><strong><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?></strong></h4>
              <a class="btn btn-sm btn-outline-info float-right" href="<?= base_url('User/listPenilaianAdd')?>">
                <i class="fas fa-plus"></i> Add Data
              </a>
            </div>
            <div class="card-body">
              <!-- FILTER FORM -->
              <div class="row mb-3">
                <div class="col-md-3">
                  <label for="filterUnit">Pilih Unit:</label>
                  <select class="form-control select2" id="filterUnit" name="filterUnit">
                    <option value="">Semua Unit</option>
                    <?php foreach($units as $unit): ?>
                      <option value="<?= htmlspecialchars($unit->unit, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($unit->unit, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="filterTanggalMulai">Tanggal Mulai:</label>
                  <input type="date" class="form-control" id="filterTanggalMulai" name="filterTanggalMulai">
                </div>
                <div class="col-md-3">
                  <label for="filterTanggalSelesai">Tanggal Selesai:</label>
                  <input type="date" class="form-control" id="filterTanggalSelesai" name="filterTanggalSelesai">
                </div>
                <div class="col-md-3">
                  <label>&nbsp;</label>
                  <div class="d-block">
                    <button type="button" class="btn btn-primary" id="btnFilter">
                      <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="btn btn-secondary" id="btnResetFilter">
                      <i class="fas fa-undo"></i> Reset
                    </button>
                  </div>
                </div>
              </div>

              <!-- SEARCH FORM -->
              <div class="input-group mb-3">
                <input class="form-control col-sm-12" name="seachListPelanggaran" id="seachListPelanggaran" type="text" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-primary">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <table id="penilaianData" class="table table-bordered table-striped display nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">NIP</th>
                    <th scope="col">Nama Karyawan</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Penilaian</th>
										<th scope="col">Catatan</th>
										<th scope="col">Tanggal</th>
                    <th scope="col">Pelapor</th>
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
