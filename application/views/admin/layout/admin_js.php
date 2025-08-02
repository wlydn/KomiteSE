<script type="text/javascript">


  /*-- Jquery Change Assess  --*/
  $('.custom-file-input').on('change', function(){
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);

  });

  var baseUrl = "<?= base_url();?>";
  
  // CSRF Token for AJAX requests
  var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
  var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
  
  // Function to update CSRF token
  function updateCSRFToken() {
    $.get(baseUrl + 'ajax/getCSRFToken', function(data) {
      if(data.csrf_hash) {
        csrfHash = data.csrf_hash;
      }
    });
  }
  
  // Setup AJAX to include CSRF token in all requests
  $.ajaxSetup({
    beforeSend: function(xhr, settings) {
      // Add CSRF token to data if it's a POST request
      if (settings.type === 'POST') {
        if (typeof settings.data === 'string') {
          settings.data += '&' + csrfName + '=' + encodeURIComponent(csrfHash);
        } else if (typeof settings.data === 'object') {
          settings.data[csrfName] = csrfHash;
        } else {
          settings.data = csrfName + '=' + encodeURIComponent(csrfHash);
        }
      }
    },
    complete: function(xhr, status) {
      // Update CSRF token from response header if available
      var token = xhr.getResponseHeader('X-CSRF-TOKEN');
      if(token) {
        csrfHash = token;
      }
    }
  });

     //Date range picker
     $('#reservation').daterangepicker({
      singleDatePicker : true,
      showDropdowns : true,
      timePicker : true,
      timePicker24Hour: true,
      timePickerSeconds: true,
      // startDate: "2020-01-01 12:00:00",
      startDate : moment().startOf('hour:minute:second'),
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    });

     $('#akhir').daterangepicker({
      singleDatePicker : true,
      showDropdowns : true,
      timePicker : true,
      timePicker24Hour: true,
      timePickerSeconds: true,
      startDate : moment().startOf('hour:minute:second'),
      locale: {
        format: 'YYYY-MM-DD HH:mm:ss'
      }
    });



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

      /*-- Ajax Responsive Table Whitout ServerSide For Mobile  --*/
      /*-- pay attention to capital D, which is mandatory to retrieve "api" datatables' object, as @Lionel said --*/
      var oTable = $('#myTable').DataTable({

        sDom: 'lrtip',
        paging : true,
        responsive: true,
        autoWidth: false,
        autoWidth: false,
        info: false,
        ordering: true,
        lengthChange: false

      });   
      $('#seachKategoriPelanggaran').keyup(function(){
        oTable.search($(this).val()).draw() ;
      });

    });


$(function () {

  'use strict'

  /*-- Make the dashboard widgets sortable Using jquery UI --*/
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


// Delete functions moved to admin_js_swal2.php to avoid conflicts
// Functions deletePel and deleteDataPel are now handled by SweetAlert2 version


function deleteDataUser(id){

  swal({

    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data ini!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Yes, delete it!",
    closeOnConfirm: false

  },

  function(){

    $.ajax({

      url : "<?= base_url('Admin/dataMasterUserDelete')?>",
      method:"post",
      data:{
        id:id,
        [csrfName]: csrfHash
      },
      dataType: 'json',
      success:function(data){

        swal({
          title: "Deleted!",
          text: "Data Berhasil Di Hapus.",
          type: "success",
          showConfirmButton: false,
          timer: 1500
        });
        setInterval('location.reload()', 2000);        

      },

      error:function(data){
        swal({
          title: "Canceled!",
          text: "Data Tidak Dapat Di Hapus.",
          type: "error",
          showConfirmButton: false,
          timer: 1500
        });
        setInterval('location.reload()', 2000);        

      }

    });
  });
};

function deleteDataPengguna(username){

  swal({

    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data ini!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Yes, delete it!",
    closeOnConfirm: false

  },

  function(){

      $.ajax({

        url : "<?= base_url('admin/pengaturanPenggunaDelete')?>",
        method:"post",
        data:{
          username:username,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success:function(data){

          swal({
            title: "Deleted!",
            text: "Data Berhasil Di Hapus.",
            type: "success",
            showConfirmButton: false,
            timer: 1500
          });
          setInterval('location.reload()', 2000);        

        },

        error:function(data){
          swal({
            title: "Canceled!",
            text: "Data Tidak Dapat Di Hapus.",
            type: "error",
            showConfirmButton: false,
            timer: 1500
          });
          setInterval('location.reload()', 2000);        

        }

      });
    });
};



/*-- DataTable To Load Data Pelanggaran --*/
var pelanggaranData = $('#pelanggaranData').DataTable({

  "sDom": 'lrtip',
  "lengthChange": false,
  "processing": true, 
  "serverSide": true, 
  "order": [],
  "ajax": {
    "url": baseUrl+'ajax/pelanggaranData',
    "type": "POST",
    "data": function(d) {
      d[csrfName] = csrfHash;
      return d;
    },
    "dataSrc": function(json) {
      if(json.csrf_hash) {
        csrfHash = json.csrf_hash;
      }
      return json.data;
    },
    "error": function(xhr, error, thrown) {
      console.log('DataTables Error:', error);
      updateCSRFToken();
    }
  },

  "columnDefs": [{ 

    "targets": [ 0 ], 
    "orderable": false, 

  }],

  "responsive": true

});
$('#seachListPelanggaran').keyup(function(){
  pelanggaranData.search($(this).val()).draw() ;
})


/*-- DataTable To Load Data Karyawan --*/
var karyawanData = $('#karyawanData').DataTable({

  "sDom": 'lrtip',
  "lengthChange": false,
  "processing": true, 
  "serverSide": true, 
  "order": [],
  "ajax": {
    "url": baseUrl+'ajax/karyawanData',
    "type": "POST",
    "data": function(d) {
      d[csrfName] = csrfHash;
      return d;
    },
    "dataSrc": function(json) {
      if(json.csrf_hash) {
        csrfHash = json.csrf_hash;
      }
      return json.data;
    },
    "error": function(xhr, error, thrown) {
      console.log('DataTables Error:', error);
      updateCSRFToken();
    }
  },

  "columnDefs": [{ 

    "targets": [ 0 ], 
    "orderable": false, 

  }],

  "responsive": true

});
$('#seachKaryawan').keyup(function(){
  karyawanData.search($(this).val()).draw() ;
})


/*-- DataTable To Load Data Pengguna --*/
var penggunaData = $('#penggunaData').DataTable({

  "sDom": 'lrtip',
  "lengthChange": false,
  "processing": true, 
  "serverSide": true, 
  "order": [],
  "ajax": {
    "url": baseUrl+'ajax/penggunaData',
    "type": "POST",
    "data": function(d) {
      d[csrfName] = csrfHash;
      return d;
    },
    "dataSrc": function(json) {
      if(json.csrf_hash) {
        csrfHash = json.csrf_hash;
      }
      return json.data;
    },
    "error": function(xhr, error, thrown) {
      console.log('DataTables Error:', error);
      updateCSRFToken();
    }
  },

  "columnDefs": [{ 

    "targets": [ 0 ], 
    "orderable": false, 

  }],

  "responsive": true

});
$('#seachPengguna').keyup(function(){
  penggunaData.search($(this).val()).draw() ;
})

/*-- DataTable To Load Data Penilaian for Admin --*/
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
      // Add CSRF token
      d[csrfName] = csrfHash;
      // Add filter data
      d.filterUnit = $('#filterUnit').val();
      d.filterTanggalMulai = $('#filterTanggalMulai').val();
      d.filterTanggalSelesai = $('#filterTanggalSelesai').val();
      return d;
    },
    "dataSrc": function(json) {
      // Update CSRF token if provided in response
      if(json.csrf_hash) {
        csrfHash = json.csrf_hash;
      }
      return json.data;
    },
    "error": function(xhr, error, thrown) {
      console.log('DataTables Error:', error);
      console.log('Response:', xhr.responseText);
      // Try to get new CSRF token
      updateCSRFToken();
    }
  },
  "columnDefs": [{ 
    "targets": [ 0 ], 
    "orderable": false, 
  }],
  "responsive": true
});

$('#seachListPelanggaran').keyup(function(){
  penilaianData.search($(this).val()).draw();
});

$('#btnFilter').click(function(){
  penilaianData.ajax.reload();
});

$('#btnResetFilter').click(function(){
  $('#filterUnit').val('');
  $('#filterTanggalMulai').val('');
  $('#filterTanggalSelesai').val('');
  penilaianData.ajax.reload();
});

function deleteDataPenilaianAdmin(id){
  // Use SweetAlert v1 syntax (compatible with older versions)
  swal({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data ini!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal",
    closeOnConfirm: false,
    closeOnCancel: true
  },
  function(isConfirm){
    if(isConfirm) {
      // Show loading
      swal({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar",
        type: "info",
        showConfirmButton: false,
        allowOutsideClick: false
      });
      
      // Set a timeout to handle stuck processing dialogs
      var timeoutId = setTimeout(function() {
        // Force clear any stuck processing dialogs after 10 seconds
        if(typeof penilaianData !== 'undefined') {
          penilaianData.processing(false);
        }
        $('.dataTables_processing').hide();
        
        swal({
          title: "Peringatan!",
          text: "Operasi memakan waktu lebih lama dari biasanya. Data mungkin sudah terhapus. Halaman akan dimuat ulang untuk memverifikasi.",
          type: "warning",
          confirmButtonText: "OK"
        }, function() {
          if(typeof penilaianData !== 'undefined') {
            penilaianData.ajax.reload(null, false);
          } else {
            location.reload();
          }
        });
      }, 10000);
      
      $.ajax({
        url : baseUrl + "ajax/penilaianDelete",
        method: "POST",
        data: {
          id: id,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        timeout: 15000, // 15 second timeout
        success: function(response){
          // Clear the timeout since we got a response
          clearTimeout(timeoutId);
          
          // Force hide any processing dialogs
          if(typeof penilaianData !== 'undefined') {
            penilaianData.processing(false);
          }
          $('.dataTables_processing').hide();
          
          // Update CSRF token if provided
          if(response.csrf_hash) {
            csrfHash = response.csrf_hash;
          }
          
          if(response.status == 'success') {
            swal({
              title: "Terhapus!",
              text: response.message || "Data penilaian berhasil dihapus!",
              type: "success",
              showConfirmButton: false,
              timer: 1500
            });
            // Redirect if redirect_url is provided
            setTimeout(function() {
              if(response.redirect_url) {
                window.location.href = response.redirect_url;
              } else if(typeof penilaianData !== 'undefined') {
                penilaianData.ajax.reload(null, false);
              } else {
                location.reload();
              }
            }, 1000);
          } else {
            // Check if session expired and redirect is needed
            if(response.redirect) {
              swal({
                title: "Sesi Berakhir!",
                text: response.message,
                type: "warning",
                confirmButtonText: "Login Kembali"
              }, function() {
                window.location.href = baseUrl;
              });
            } else {
              swal({
                title: "Error!",
                text: response.message,
                type: "error",
                confirmButtonText: "OK"
              });
            }
          }
        },
        error: function(xhr, status, error){
          // Clear the timeout since we got a response (even if error)
          clearTimeout(timeoutId);
          
          console.log("AJAX Error: ", xhr.responseText);
          console.log("Status: ", status);
          console.log("Error: ", error);
          
          // Force hide any processing dialogs immediately
          if(typeof penilaianData !== 'undefined') {
            penilaianData.processing(false);
          }
          $('.dataTables_processing').hide();
          
          // Update CSRF token on error
          updateCSRFToken();
          
          try {
            var response = JSON.parse(xhr.responseText);
            if(response.csrf_hash) {
              csrfHash = response.csrf_hash;
            }
            if(response.redirect) {
              swal({
                title: "Sesi Berakhir!",
                text: response.message,
                type: "warning",
                confirmButtonText: "Login Kembali"
              }, function() {
                window.location.href = baseUrl;
              });
            } else {
              swal({
                title: "Error!",
                text: response.message || "Terjadi kesalahan saat menghapus data.",
                type: "error",
                confirmButtonText: "OK"
              });
            }
          } catch(e) {
            // Handle CSRF token error specifically
            if(xhr.status === 403 && xhr.responseText.includes("The action you have requested is not allowed")) {
              // Check if deletion actually succeeded despite CSRF error
              swal({
                title: "Peringatan!",
                text: "Terjadi masalah dengan token keamanan, tetapi data mungkin sudah terhapus. Halaman akan dimuat ulang untuk memverifikasi.",
                type: "warning",
                confirmButtonText: "OK"
              }, function() {
                // Reload the DataTable to check if deletion succeeded
                if(typeof penilaianData !== 'undefined') {
                  penilaianData.ajax.reload(null, false);
                } else {
                  location.reload();
                }
              });
            } else {
              swal({
                title: "Error!",
                text: "Terjadi kesalahan saat menghapus data. Silakan coba lagi.",
                type: "error",
                confirmButtonText: "OK"
              });
            }
          }
        }
      });
    }
  });
}


</script>
