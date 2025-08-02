    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url('Admin');?>" class="brand-link">
        <img src="<?= base_url('assets/AdminLTE-3.0.5/');?>dist/img/icon.png" alt="Rayhan Hospital" class="brand-image img-circle elevation-3"
        style="opacity: .8">
        <span class="brand-text font-weight-light">Komite SE Rayhan</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?= base_url('Admin')?>" class="nav-link <?php if($page == 'Dashboard' || $page == 'Detail Pelanggaran'){echo 'active';} ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-header">Main Menu</li>
          <li class="nav-item has-treeview <?php if($parent == 'Data Kategori' || $parent == 'Data Kategori' || $parent == 'List Pelanggaran'){echo 'menu-open';} ?>">
            <a href="#" class="nav-link <?php if($parent == 'Data Kategori' || $parent == 'Data Kategori' || $parent == 'List Pelanggaran'){echo 'active';} ?>">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Data Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ">
                <a href="<?= base_url('Admin/dataIndikatorPenilaian')?>" class="nav-link <?php if($page == 'Indikator Penilaian' || $page == 'Indikator Penilaian Add' || $page == 'Kategori Pelanggaran Edit'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Indikator Penilaian</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?= base_url('Admin/dataListKaryawan')?>" class="nav-link <?php if($page == 'List Karyawan' || $page == 'List Karyawan Add' || $page == 'List Karyawan Detail' || $page == 'List Karyawan Edit'){echo 'active';} ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Karyawan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('Admin/dataListPenilaian')?>" class="nav-link <?php if($parent == 'Data Laporan'){echo 'active';} ?>">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                List Penilaian Karyawan
              </p>
            </a>
          </li>
          <li class="nav-header" <?php if($parent == 'Pengguna' || $parent == 'Data Pengguna' ){echo 'active';} ?>>User</li>
					<li class="nav-item">
                <a href="<?= base_url('Admin/pengaturanPengguna') ;?>" class="nav-link <?php if($page == 'Data Pengguna' || $page == 'Data Pengguna Add' || $page == 'Data Pengguna Detail' || $page == 'Data Pengguna Edit'){echo 'active';} ?>">
                  <i class="nav-icon fa-fw fas fa-user"></i>
                  <p>Pengguna</p>
                </a>
              </li>
          <li class="nav-item">
            <a href="#" class="nav-link" data-toggle="modal" data-target="#logOutModal">
              <i class="nav-icon fa-fw fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
