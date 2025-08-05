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
                <li class="breadcrumb-item"><small><?= htmlspecialchars($this->session->userdata('level'), ENT_QUOTES, 'UTF-8');?></small></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dataKategoriKategoriPelanggaran')?>"><small><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ;?></small></a></li>
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
      <section class="content">
        <div class="container-fluid">
          <!-- Default box -->
          <div class="card card-outline card-info">
            <div class="card-header">
              <h4 class="card-title " text-align="center"><strong><?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8'); ?></strong></h4>
              <a class="btn btn-sm btn-outline-info float-right" href="#" data-toggle="modal" data-target="#kategoriPelanggaranAdd">
                <i class="fas fa-plus"></i> Add Data
              </a>
            </div>
            <div class="card-body">
              <!-- SEARCH FORM -->

              <div class="input-group ">
                <input class="form-control col-sm-12" name="seachKategoriPelanggaran" id="seachKategoriPelanggaran" type="text" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-primary">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <table id="myTable" class="table table-bordered table-striped display nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col">#</th>
										<th scope="col">Code</th>
                    <th scope="col">Indikator Penilaian</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=0; foreach($tipePelanggaran as $pel) :  $i++;?>
                  <tr id="<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8'); ?>">
                    <td><?= $i ;?></td>
										<td><?= htmlspecialchars($pel->code, ENT_QUOTES, 'UTF-8');?></td>
                    <td><?= htmlspecialchars($pel->violation_name, ENT_QUOTES, 'UTF-8');?></td>
                    <td>
                      <a class="btn btn-sm btn-info" style="margin-right:10px; height: 30px; width: 30px;" href="#" data-toggle="modal" data-target="#pelanggaranDetailModal<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8');?>" title="Detail"><i class="fas fa-info"></i></a>
                      <a class="btn btn-sm btn-warning"  style="margin-right:10px; height: 30px; width: 30px;" href="#" data-toggle="modal" data-target="#pelanggaranEditModal<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8');?>" title="Edit"><i class="fas fa-edit text-white"></i></a>
                      <a class="btn btn-sm btn-danger"style="margin-right:10px; height: 30px; width: 30px;" onclick=" deletePel(<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8')?>)" id="<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8') ;?>" title="Delete"><i class="fas fa-trash text-white"></i></a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
          <!-- /.row -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

      <!-- Modal Add-->
      <div class="modal fade" id="kategoriPelanggaranAdd">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h5 class="modal-title">Add Indikator Penilaian</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url('admin/dataIndikatorPenilaianAdd'); ?>" method="post">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
              <div class="modal-body">

								<div class="form-group">
									<label for="addCode">Code</label>
									<input type="text" name="code" class="form-control" id="addCode" placeholder="Code Pelanggaran" required>
									<?php echo form_error('code', '<small class="text-danger pl-3">', '</small>');?>
								</div>

                <div class="form-group">
                 <label  for="addNama">Indikator Penilaian</label>
                 <input type="text" name="nama" class="form-control" id="addNama" placeholder="Nama Kategori Pelanggaran" required>
                 <?php echo form_error('nama', '<small class="text-danger pl-3">', '</small>');?>
               </div>

             </div>
             <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fas fa-times"></i>&ensp;Close</button>
              <button type="submit" class="btn btn-primary btn-sm" onclick="console.log('Form submitted with:', document.getElementById('addCode').value, document.getElementById('addNama').value);"><i class="fas fa-plus"></i>&ensp;Add</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Detail-->
    <?php $i=0; foreach($tipePelanggaran as $pel) : $i++; ?>
    <div class="modal fade" id="pelanggaranDetailModal<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8');?>">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h5 class="modal-title">Detail Kategori Pelanggaran</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form>
            <div class="modal-body">

              <div class="form-group">
                <label id="detailNama">Nama Kategori Pelanggaran</label>
                <input type="text" readonly class="form-control" for="detailNama" placeholder="Nama Kategori Pelanggaran" value="<?= htmlspecialchars($pel->violation_name, ENT_QUOTES, 'UTF-8') ;?>">
              </div>
              <div class="form-group">
                <label id="detailPoint">Jumlah Point</label>
                <input type="text" readonly class="form-control" for="detailPoint" placeholder="Jumlah Point" value="<?= htmlspecialchars($pel->get_point, ENT_QUOTES, 'UTF-8');?>">
              </div>

            </div>
            <div class="modal-footer" style='clear:both'>
              <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i>&ensp;Close</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  <?php endforeach; ?>

  <!-- Modal Edit-->
  <?php $i=0; foreach($tipePelanggaran as $pel) : $i++; ?>
  <div class="modal fade" id="pelanggaranEditModal<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8');?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Edit Kategori Pelanggaran </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?= base_url('admin/dataIndikatorPenilaianEdit'); ?>" method="post">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div class="modal-body">

            <input type="hidden" name="z" readonly value="<?= htmlspecialchars($pel->id, ENT_QUOTES, 'UTF-8') ;?>"  class="form-control" >

            <div class="form-group">
              <label id="editNama">Nama Kategori Pelanggaran</label>
              <input type="text" name="nama" class="form-control" for="editNama" value="<?= htmlspecialchars($pel->violation_name, ENT_QUOTES, 'UTF-8') ;?>" placeholder="Nama Kategori Pelanggaran">
            </div>
            <div class="form-group">
              <label id="editPoint">Jumlah Point</label>
              <input type="text" name="point" class="form-control" for="editPoint" value="<?= htmlspecialchars($pel->get_point, ENT_QUOTES, 'UTF-8') ;?>" placeholder="Jumlah Point">
            </div>

          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i>&ensp;Close</button>
            <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i>&ensp;Update</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
<?php endforeach; ?>
<!--End Modal -->


</section>
<!-- /.content -->

</div>
<!-- Barang Hapus Modal-->
<script>
  function deletePel(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "<?= base_url('admin/deleteIndikator') ?>",
          type: "POST",
          data: {
            id: id,
            '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
          },
          success: function(data) {
            if (data.status === 'success') {
              Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              ).then(() => {
                location.reload();
              });
            } else {
              Swal.fire(
                'Error!',
                data.message,
                'error'
              );
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire(
              'Error!',
              'Something went wrong. Please try again later.',
              'error'
            );
          }
        });
      }
    })
  }
</script>
