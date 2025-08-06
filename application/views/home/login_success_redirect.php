<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login Success</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  Swal.fire({
    icon: 'success',
    title: 'Login successful!',
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    didClose: () => {
      window.location.href = '<?= $redirect_url ?>';
    }
  });
});
</script>
</head>
<body>
</body>
</html>
