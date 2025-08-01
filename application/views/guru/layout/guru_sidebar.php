<aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url('Guru') ?>" class="brand-link">
        <img src="<?= base_url('assets/AdminLTE-3.0.5/');?>dist/img/icon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
        style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $title; ?></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
           <li class="nav-item">
						 <a href="<?= base_url('Guru')?>" class="nav-link <?php if($page == 'Dashboard' || $page == 'Detail Pelanggaran'){echo 'active';} ?>">
							 <i class="nav-icon fas fa-tachometer-alt"></i>
							 <p>
								 Dashboard
								</p>
							</a>
						</li>
						<li class="nav-header">Main Menu</li>
						<li class="nav-item">
							<a href="<?= base_url('Guru/listPenilaian')?>" class="nav-link <?php if($page == 'List Penilaian' || $page == 'List Penilaian Add' || $page == 'List Penilaian Detail' || $page == 'List Pelanggaran Edit'){echo 'active';} ?>">
								<i class="nav-icon far fa-list-alt"></i>
								<p>
									List Penilaian
								</p>
							</a>
						</li>
						<li class="nav-item">
                <a href="<?= base_url('Guru/dataKategoriListKaryawan')?>" class="nav-link <?php if($page == 'List Karyawan' || $page == 'List Karyawan Add' || $page == 'List Karyawan Detail' || $page == 'List Karyawan Edit'){echo 'active';} ?>">
                  <i class="nav-icon fa fa-users"></i>
                  <p>
										List Karyawan
									</p>
                </a>
              </li>
							<li class="nav-header">Personal Menu</li>
							<li class="nav-item">
                <a href="<?= base_url('Guru/pengaturanUserEdit')?>" class="nav-link <?php if($page == 'Ubah Password'){echo 'active';} ?>">
                  <i class="nav-icon fa fa-key"></i>
                  <p>
										Ubah Password
									</p>
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
