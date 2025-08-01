<script type="text/javascript">

  /*-- Jquery Change Assess  --*/
  $('.custom-file-input').on('change', function(){
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);

  });


  var baseUrl = "<?= base_url();?>";

  $(document).ready(function() {

    /*-- Ajax Responsive Table Whitout ServerSide For Mobile  --*/
    var terakhir = $('#terakhir').DataTable( {
      rowReorder: {
        selector: 'td:nth-child(2)'
      },
      responsive: true,
      paging: false,
      lengthChange: false,
      searching: false,
      ordering: true,
      info: false,
      autoWidth: false,
    });

    var terbanyak = $('#terbanyak').DataTable( {
      rowReorder: {
        selector: 'td:nth-child(2)'
      },
      responsive: true,
      paging: false,
      lengthChange: false,
      searching: false,
      ordering: true,
      info: false,
      autoWidth: false,
    });

  });


  $(function () {

    'use strict'

  // Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder         : 'sort-highlight',
    connectWith         : '.connectedSortable',
    handle              : '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex              : 999999
  });
  
  $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move');

  /*-- Select 2 --*/
  $('.select2').select2();

  /*-- Timeout Alert Error form_validation 5sec --*/
  var timeout = 5000; 
  $('.alert').delay(timeout).fadeOut(500);

  /*-- Plugin for edit data mahasiswa --*/
  $('[data-mask]').inputmask();

  /*-- DatePicker Plugin to avoid Confict Wit JQuery --*/
  var datepicker = $.fn.datepicker.noConflict();
  $.fn.bootstrapDP = datepicker;    
  $('#tglLhr .input-group.date').datepicker({


  });

});

  /*-- DataTable To Load Data Karyawan --*/
  var karyawanData = $('#karyawanData').DataTable({
    "sDom": 'lrtip',
    "lengthChange": false,
    "processing": true,
    "serverSide": true,
    "order": [],
    "ajax": {
      "url": baseUrl + 'ajax/karyawanData',
      "type": "POST"
    },
    "columnDefs": [{
      "targets": [0],
      "orderable": false,
    }],
    "responsive": true
  });
  $('#seachKaryawan').keyup(function(){
    karyawanData.search($(this).val()).draw();
  });

  /*-- DataTable To Load Data Penilaian --*/
  var penilaianData = $('#penilaianData').DataTable({
    "sDom": 'lrtip',
    "lengthChange": false,
    "processing": true,
    "serverSide": true,
    "order": [],
    "ajax": {
      "url": baseUrl + 'ajax/penilaianData',
      "type": "POST",
      "data": function(d) {
        d.filterUnit = $('#filterUnit').val();
        d.filterTanggalMulai = $('#filterTanggalMulai').val();
        d.filterTanggalSelesai = $('#filterTanggalSelesai').val();
      }
    },
    "columnDefs": [{
      "targets": [0, 8],
      "orderable": false,
    }],
    "responsive": true
  });
  
  $('#seachListPelanggaran').keyup(function(){
    penilaianData.search($(this).val()).draw();
  });

  /*-- Filter functionality --*/
  $('#btnFilter').click(function(){
    penilaianData.ajax.reload();
  });

  $('#btnResetFilter').click(function(){
    $('#filterUnit').val('').trigger('change');
    $('#filterTanggalMulai').val('');
    $('#filterTanggalSelesai').val('');
    penilaianData.ajax.reload();
  });

  /*-- Function to delete penilaian with confirmation --*/
  function deleteDataPenilaian(id){
    swal({
      title: "Apakah Anda yakin?",
      text: "Data penilaian akan dihapus secara permanen!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      confirmButtonText: "Ya, Hapus!",
      cancelButtonText: "Batal",
      closeOnConfirm: false,
      closeOnCancel: true
    }, function(isConfirm) {
      if (isConfirm) {
        $.ajax({
          url: baseUrl + 'ajax/penilaianDelete',
          type: 'POST',
          data: {id: id},
          dataType: 'json',
          success: function(response) {
            if(response.status == 'success') {
              swal("Terhapus!", response.message, "success");
              penilaianData.ajax.reload();
            } else {
              swal("Error!", response.message, "error");
            }
          },
          error: function() {
            swal("Error!", "Terjadi kesalahan saat menghapus data.", "error");
          }
        });
      }
    });
  }

</script>
