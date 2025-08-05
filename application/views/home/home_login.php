    <div class="intro-section" id="home-section">

      <div class="slide-1" style="background-image: url('<?= base_url('assets/oneschool/');?>images/background.jpg');">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-12">
              <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                  <h1  data-aos="fade-up" data-aos-delay="100">KOMITE SE RAYHAN</h1>
                  <p class="mb-4"  data-aos="fade-up" data-aos-delay="200">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime ipsa nulla sed quis rerum amet natus quas necessitatibus.</p>

                </div>

                <div class="col-lg-5 ml-auto" data-aos="fade-up" data-aos-delay="500">
                  <form action="<?= base_url('home/login');?>" method="post" class="form-box">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <h3 class="h4 text-black mb-4">Login
										</h3>
                    <div class="form-group">
                      <input type="text" name="username" class="form-control" placeholder="Username" autocomplete="off">
                      <?php echo form_error('username', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off">
                      <?php echo form_error('password', '<small class="text-danger pl-3">', '</small>');?>
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary btn-pill" value="GO">
                    </div>
                  </form>

                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
