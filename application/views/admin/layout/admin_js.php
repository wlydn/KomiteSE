<script type="text/javascript">


  /*-- Jquery Change Assess  --*/
  $('.custom-file-input').on('change', function(){
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);

  });

  var baseUrl = "<?= base_url();?>";

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


      // /*-- Active Link  --*/
      // var url = window.location;
      // const allLinks = document.querySelectorAll('.nav-item a');
      // const currentLink = [...allLinks].filter(e => {
      //   return e.href == url;
      // });

      // currentLink[0].classList.add("active");
      // currentLink[0].closest(".nav-treeview").style.display = "block ";
      // currentLink[0].closest(".has-treeview").classList.add("menu-open");
      // $('.menu-open').find('a').each(function() {
      //   if (!$(this).parents().hasClass('active')) {
      //     $(this).parents().addClass("active");
      //     $(this).addClass("active");
      //   }
      // });

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

      /*-- Ajax Select Nama Kelas  --*/
      $('#kelas').change(function(){
        var kelas = $('#kelas').val();
        if(kelas != ''){
          $.ajax({
            url:baseUrl+'ajax/fetch_subNamakelas',
            method:"POST",
            data:{kelas:kelas},
            success:function(data){
              $('#namaKelas').html(data);

              if(kelas == 'I'){
                $('#namaSiswa').html('<option value="" selected="selected">Pilih Kategori Kelas Terlebih Dahulu</option>');
              }else{
                $('#namaSiswa').html('<option value="" selected="selected">Pilih Nama Kelas Terlebih Dahulu</option>');
              }
            }
          })
        }
      });

      /*-- Ajax Select Nama Siswa  --*/
      $('#namaKelas').change(function(){
        var namaKelas = $('#namaKelas').val();
        if(namaKelas != ''){
          $.ajax({
            url:baseUrl+'ajax/fetch_subNamaSiswa',
            method:"POST",
            data:{namaKelas:namaKelas},
            success:function(data){
              $('#namaSiswa').html(data);
            }
          })
        }
      });



      /*-- Ajax Select Nama Kelas  --*/
      $('#addkelas').change(function(){
        var kelas = $('#addkelas').val();
        if(kelas != ''){
          $.ajax({
            url:baseUrl+'ajax/fetch_subNamakelas',
            method:"POST",
            data:{kelas:kelas},
            success:function(data){
              $('#addnamaKelas').html(data)

            }
          })
        }
      });



      /*-- Ajax Select Tipe Pencarian Laporan  --*/
      $('#tipePencarian').change(function(){
        var pencarian = $('#tipePencarian').val();
        if(pencarian == 'siswa'){

          $("#pencarianKelas").css("display", "none");
          $("#pencarianSiswa").css("display", "block");

        }else if(pencarian == 'kelas'){

          $("#pencarianSiswa").css("display", "none")
          $("#pencarianKelas").css("display", "block");

        }else if(pencarian == ''){

          $("#pencarianKelas").css("display", "none");
          $("#pencarianSiswa").css("display", "none");

        }
      });

    });



$(document).ready(function() {

  /*-- Ajax Select Level  --*/
  $('#addPenggunaLevel').change(function(){
    var penggunaLevel = $('#addPenggunaLevel').val();
    if(penggunaLevel == 'Admin'){

      $("#addPenggunaAdmin").css("display", "block");
      $("#addPenggunaGuru").css("display", "none");
      $("#addPenggunaWali").css("display", "none");
      $("#addPenggunaSiswa").css("display", "none");

    }else if(penggunaLevel == 'User'){

      $("#addPenggunaAdmin").css("display", "none");
      $("#addPenggunaUser").css("display", "block");
      $("#addPenggunaWali").css("display", "none");
      $("#addPenggunaSiswa").css("display", "none");

    }else if(penggunaLevel == 'Wali'){

      $("#addPenggunaAdmin").css("display", "none");
      $("#addPenggunaUser").css("display", "none");
      $("#addPenggunaWali").css("display", "block");
      $("#addPenggunaSiswa").css("display", "none");

    }else if(penggunaLevel == 'Siswa'){

      $("#addPenggunaAdmin").css("display", "none");
      $("#addPenggunaUser").css("display", "none");
      $("#addPenggunaWali").css("display", "none");
      $("#addPenggunaSiswa").css("display", "block");

    }
  });


  /*-- Ajax Select Nama Kelas  --*/
  $('#addNIKUser').change(function(){
    var idUser = $('#addNIKUser').val();
    if(idUser != ''){
      $.ajax({
        url:baseUrl+'ajax/fetch_nikUser',
        method:"POST",
        dataType:'json',
        data:{idUser:idUser},
        success:function(data){
          $('#addPenggunaFullnameUser').val(data.nama);
          $('#addPenggunaUsernameUser').val(data.nik);
        }
      })
    }
  });


  /*-- Ajax Select Nama Kelas  --*/
  $('#addNISNWali').change(function(){
    var nisnWali = $('#addNISNWali').val();
    if(nisnWali != ''){
      $.ajax({
        url:baseUrl+'ajax/fetch_nisnWali',
        method:"POST",
        dataType:'json',
        data:{nisnWali:nisnWali},
        success:function(data){
          $('#addPenggunaFullnameWali').val(data.nama);
          $('#addPenggunaUsernameWali').val(data.nisn);
        }
      })
    }
  });

  /*-- Ajax Select Nama Kelas  --*/
  $('#addNISNSiswa').change(function(){
    var nisnSiswa = $('#addNISNSiswa').val();
    if(nisnSiswa != ''){
      $.ajax({
        url:baseUrl+'ajax/fetch_nisnSiswa',
        method:"POST",
        dataType:'json',
        data:{nisnSiswa:nisnSiswa},
        success:function(data){
          $('#addPenggunaFullnameSiswa').val(data.nama);
          $('#addPenggunaUsernameSiswa').val(data.nisn);
        }
      })
    }
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

      url : "<?= base_url('admin/dataMasterUserDelete')?>",
      method:"post",
      data:{id:id},
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

function deleteDataKelas(id){

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

      url : "<?= base_url('admin/dataMasterKelasDelete')?>",
      method:"post",
      data:{id:id},
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


function deleteDataSiswa(id){

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

      url : "<?= base_url('admin/dataMasterSiswaDelete')?>",
      method:"post",
      data:{id:id},
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

function deleteDataPengguna(id){

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
      data:{id:id},
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
    "type": "POST"

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
    "type": "POST"

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
    "type": "POST"

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
      d.filterUnit = $('#filterUnit').val();
      d.filterTanggalMulai = $('#filterTanggalMulai').val();
      d.filterTanggalSelesai = $('#filterTanggalSelesai').val();
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
      
      $.ajax({
        url : baseUrl + "ajax/penilaianDelete",
        method: "POST",
        data: {id: id},
        dataType: 'json',
        success: function(response){
          if(response.status == 'success') {
            swal({
              title: "Terhapus!",
              text: response.message || "Data penilaian berhasil dihapus!",
              type: "success",
              showConfirmButton: false,
              timer: 2000
            });
            // Reload the DataTable after a short delay
            setTimeout(function() {
              if(typeof penilaianData !== 'undefined') {
                penilaianData.ajax.reload(null, false);
              } else {
                location.reload();
              }
            }, 1500);
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
          console.log("AJAX Error: ", xhr.responseText);
          try {
            var response = JSON.parse(xhr.responseText);
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
            swal({
              title: "Error!",
              text: "Terjadi kesalahan saat menghapus data. Silakan coba lagi.",
              type: "error",
              confirmButtonText: "OK"
            });
          }
        }
      });
    }
  });
}


</script>
