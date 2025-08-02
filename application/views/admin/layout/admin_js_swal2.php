<script type="text/javascript">
// Get CSRF variables from admin_js.php
var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

// SweetAlert2 version of deleteDataPenilaianAdmin function
function deleteDataPenilaianAdmin(id){
  // Use SweetAlert2 syntax
  Swal.fire({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data ini!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      // Show loading
      Swal.fire({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar...",
        icon: "info",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      $.ajax({
        url: baseUrl + "ajax/penilaianDelete",
        method: "post",
        data: {
          id: id,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response){
          try {
            if(response.status == 'success') {
              Swal.fire({
                title: "Terhapus!",
                text: response.message,
                icon: "success",
                timer: 2000,
                showConfirmButton: false
              });
              // Reload DataTable
              if(typeof penilaianData !== 'undefined') {
                penilaianData.ajax.reload();
              } else {
                // Fallback: reload page if DataTable not available
                setTimeout(() => {
                  location.reload();
                }, 2000);
              }
            } else {
              if(response.redirect) {
                Swal.fire({
                  title: "Sesi Berakhir!",
                  text: response.message,
                  icon: "warning",
                  showConfirmButton: true,
                  confirmButtonText: "Login Ulang"
                }).then(() => {
                  window.location.href = baseUrl + 'home';
                });
              } else {
                Swal.fire({
                  title: "Error!",
                  text: response.message,
                  icon: "error",
                  showConfirmButton: true
                });
              }
            }
          } catch(e) {
            console.error('Response parsing error:', e);
            console.log('Raw response:', response);
            Swal.fire({
              title: "Error!",
              text: "Terjadi kesalahan dalam memproses response.",
              icon: "error",
              showConfirmButton: true
            });
          }
        },
        error: function(xhr, status, error){
          console.error('AJAX Error:', {xhr: xhr, status: status, error: error});
          if(xhr.responseJSON && xhr.responseJSON.redirect) {
            Swal.fire({
              title: "Sesi Berakhir!",
              text: "Sesi Anda telah berakhir. Silakan login kembali.",
              icon: "warning",
              showConfirmButton: true,
              confirmButtonText: "Login Ulang"
            }).then(() => {
              window.location.href = baseUrl + 'home';
            });
          } else {
            Swal.fire({
              title: "Error!",
              text: "Terjadi kesalahan saat menghapus data. Silakan coba lagi.",
              icon: "error",
              showConfirmButton: true
            });
          }
        }
      });
    }
  });
}

// SweetAlert2 version of deletePel function for Indikator Penilaian
function deletePel(id){
  // Use SweetAlert2 syntax
  Swal.fire({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data indikator penilaian ini!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      // Show loading
      Swal.fire({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar...",
        icon: "info",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      $.ajax({
        url: baseUrl + "admin/deleteIndikator",
        method: "post",
        data: {
          id: id,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        beforeSend: function() {
          console.log('Sending delete request for ID:', id);
          console.log('URL:', baseUrl + "admin/deleteIndikator");
          console.log('CSRF Name:', csrfName);
          console.log('CSRF Hash:', csrfHash);
        },
        success: function(response){
          console.log('Success response received:', response);
          try {
            // Update CSRF hash if provided
            if(response.csrf_hash) {
              csrfHash = response.csrf_hash;
              console.log('Updated CSRF hash:', csrfHash);
            }
            
            if(response.status == 'success') {
              Swal.fire({
                title: "Terhapus!",
                text: response.message,
                icon: "success",
                timer: 1500,
                showConfirmButton: false
              });
              // Reload page after successful deletion
              setTimeout(() => {
                location.reload();
              }, 2000);
            } else {
              if(response.redirect) {
                Swal.fire({
                  title: "Sesi Berakhir!",
                  text: response.message,
                  icon: "warning",
                  showConfirmButton: true,
                  confirmButtonText: "Login Ulang"
                }).then(() => {
                  window.location.href = baseUrl + 'home';
                });
              } else {
                Swal.fire({
                  title: "Error!",
                  text: response.message,
                  icon: "error",
                  showConfirmButton: true
                });
              }
            }
          } catch(e) {
            console.error('Response parsing error:', e);
            console.log('Raw response:', response);
            Swal.fire({
              title: "Error!",
              text: "Terjadi kesalahan dalam memproses response.",
              icon: "error",
              showConfirmButton: true
            });
          }
        },
        error: function(xhr, status, error){
          console.error('AJAX Error Details:', {
            xhr: xhr,
            status: status,
            error: error,
            responseText: xhr.responseText,
            statusCode: xhr.status,
            readyState: xhr.readyState
          });
          
          // Show detailed error information
          var errorMessage = "Terjadi kesalahan saat menghapus data.";
          var errorTitle = "Error!";
          
          if(xhr.status === 0) {
            errorMessage = "Tidak dapat terhubung ke server. Periksa koneksi internet.";
            errorTitle = "Connection Error!";
          } else if(xhr.status === 403) {
            errorMessage = "Token keamanan tidak valid. Halaman akan dimuat ulang.";
            errorTitle = "CSRF Error!";
            Swal.fire({
              title: errorTitle,
              text: errorMessage,
              icon: "warning",
              showConfirmButton: true,
              confirmButtonText: "OK"
            }).then(() => {
              location.reload();
            });
            return;
          } else if(xhr.status === 404) {
            errorMessage = "URL tidak ditemukan: " + baseUrl + "admin/deleteIndikator";
            errorTitle = "Not Found!";
          } else if(xhr.status === 500) {
            errorMessage = "Terjadi kesalahan server internal.";
            errorTitle = "Server Error!";
          }
          
          // Try to parse JSON error response
          try {
            var errorResponse = JSON.parse(xhr.responseText);
            if(errorResponse.redirect) {
              Swal.fire({
                title: "Sesi Berakhir!",
                text: errorResponse.message,
                icon: "warning",
                showConfirmButton: true,
                confirmButtonText: "Login Ulang"
              }).then(() => {
                window.location.href = baseUrl + 'home';
              });
              return;
            } else if(errorResponse.message) {
              errorMessage = errorResponse.message;
            }
          } catch(parseError) {
            console.log('Could not parse error response as JSON');
          }
          
          Swal.fire({
            title: errorTitle,
            text: errorMessage + " (Status: " + xhr.status + ")",
            icon: "error",
            showConfirmButton: true
          });
        }
      });
    }
  });
}

// SweetAlert2 version of deleteDataPel function for List Pelanggaran
function deleteDataPel(id){
  // Use SweetAlert2 syntax
  Swal.fire({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data pelanggaran ini!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      // Show loading
      Swal.fire({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar...",
        icon: "info",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      $.ajax({
        url: baseUrl + "admin/deletePelanggaran",
        method: "post",
        data: {
          id: id,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response){
          try {
            if(response.status == 'success') {
              Swal.fire({
                title: "Terhapus!",
                text: response.message,
                icon: "success",
                timer: 1500,
                showConfirmButton: false
              });
              // Reload page after successful deletion
              setTimeout(() => {
                location.reload();
              }, 2000);
            } else {
              Swal.fire({
                title: "Error!",
                text: response.message,
                icon: "error",
                showConfirmButton: true
              });
            }
          } catch(e) {
            console.error('Response parsing error:', e);
            console.log('Raw response:', response);
            // Fallback for old response format
            Swal.fire({
              title: "Terhapus!",
              text: "Data pelanggaran berhasil dihapus.",
              icon: "success",
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(() => {
              location.reload();
            }, 2000);
          }
        },
        error: function(xhr, status, error){
          console.error('AJAX Error:', {xhr: xhr, status: status, error: error});
          Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan saat menghapus data. Silakan coba lagi.",
            icon: "error",
            showConfirmButton: true
          });
        }
      });
    }
  });
}

// SweetAlert2 version of deleteDataUser function
function deleteDataUser(id){
  Swal.fire({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data user ini!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar...",
        icon: "info",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });

      $.ajax({
        url: baseUrl + "admin/dataMasterKaryawanDelete",
        method: "post",
        data: {
          id: id,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response){
          Swal.fire({
            title: "Terhapus!",
            text: "Data user berhasil dihapus.",
            icon: "success",
            timer: 1500,
            showConfirmButton: false
          });
          setTimeout(() => { location.reload(); }, 2000);
        },
        error: function(xhr, status, error){
          Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan saat menghapus data user.",
            icon: "error",
            showConfirmButton: true
          });
        }
      });
    }
  });
}

// SweetAlert2 version of deleteDataKelas function
function deleteDataKelas(id){
  Swal.fire({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data kelas ini!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar...",
        icon: "info",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });

      $.ajax({
        url: baseUrl + "admin/dataMasterKelasDelete",
        method: "post",
        data: {
          id: id,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response){
          Swal.fire({
            title: "Terhapus!",
            text: "Data kelas berhasil dihapus.",
            icon: "success",
            timer: 1500,
            showConfirmButton: false
          });
          setTimeout(() => { location.reload(); }, 2000);
        },
        error: function(xhr, status, error){
          Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan saat menghapus data kelas.",
            icon: "error",
            showConfirmButton: true
          });
        }
      });
    }
  });
}

// SweetAlert2 version of deleteDataSiswa function
function deleteDataSiswa(id){
  Swal.fire({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data siswa ini!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar...",
        icon: "info",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });

      $.ajax({
        url: baseUrl + "admin/dataMasterSiswaDelete",
        method: "post",
        data: {
          id: id,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response){
          Swal.fire({
            title: "Terhapus!",
            text: "Data siswa berhasil dihapus.",
            icon: "success",
            timer: 1500,
            showConfirmButton: false
          });
          setTimeout(() => { location.reload(); }, 2000);
        },
        error: function(xhr, status, error){
          Swal.fire({
            title: "Error!",
            text: "Terjadi kesalahan saat menghapus data siswa.",
            icon: "error",
            showConfirmButton: true
          });
        }
      });
    }
  });
}

// SweetAlert2 version of deleteDataPengguna function
function deleteDataPengguna(username){
  Swal.fire({
    title: "Apakah Anda Yakin Ingin Menghapus?",
    text: "Anda tidak akan dapat memulihkan data pengguna ini!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Ya, hapus!",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Menghapus...",
        text: "Mohon tunggu sebentar...",
        icon: "info",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
      });

      $.ajax({
        url: baseUrl + "admin/pengaturanPenggunaDelete",
        method: "post",
        data: {
          username: username,
          [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response){
          try {
            if(response.status == 'success') {
              Swal.fire({
                title: "Terhapus!",
                text: response.message,
                icon: "success",
                timer: 1500,
                showConfirmButton: false
              });
              setTimeout(() => { location.reload(); }, 2000);
            } else {
              if(response.redirect) {
                Swal.fire({
                  title: "Sesi Berakhir!",
                  text: response.message,
                  icon: "warning",
                  showConfirmButton: true,
                  confirmButtonText: "Login Ulang"
                }).then(() => {
                  window.location.href = baseUrl + 'home';
                });
              } else {
                Swal.fire({
                  title: "Error!",
                  text: response.message,
                  icon: "error",
                  showConfirmButton: true
                });
              }
            }
          } catch(e) {
            console.error('Response parsing error:', e);
            console.log('Raw response:', response);
            Swal.fire({
              title: "Terhapus!",
              text: "Data pengguna berhasil dihapus.",
              icon: "success",
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(() => { location.reload(); }, 2000);
          }
        },
        error: function(xhr, status, error){
          console.error('AJAX Error:', {xhr: xhr, status: status, error: error});
          if(xhr.responseJSON && xhr.responseJSON.redirect) {
            Swal.fire({
              title: "Sesi Berakhir!",
              text: "Sesi Anda telah berakhir. Silakan login kembali.",
              icon: "warning",
              showConfirmButton: true,
              confirmButtonText: "Login Ulang"
            }).then(() => {
              window.location.href = baseUrl + 'home';
            });
          } else {
            Swal.fire({
              title: "Error!",
              text: "Terjadi kesalahan saat menghapus data pengguna.",
              icon: "error",
              showConfirmButton: true
            });
          }
        }
      });
    }
  });
}
</script>
