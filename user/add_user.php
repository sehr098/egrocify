  <?php
  include("inc/header.php"); 
  include("../function.php");

   $update = false;
   $user = "";
   if(isset($_GET['id'])){
      $update = true;
      $user = get_single_package($_GET['id']);
   }

  ?>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <?php include("inc/sidebar.php"); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

        <!-- Navbar -->
        <?php include("inc/topbar.php"); ?>
        <!-- / Navbar -->

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-ecommerce">
                <form id="updateUserForm" class="mb-3" action="../ajax-request.php" method="POST" >

                <!-- Add Product -->
                <div
                  class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                  <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Add a new User</h4>
                    <!-- <p class="mb-0">Orders placed across your store</p> -->
                  </div>
                  <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                      <a class="btn btn-label-secondary" href="users.php">Discard</a>
                      <!-- <button class="btn btn-label-primary">Save draft</button> -->
                    </div>
                      <?php
                      if($update == false){
                      ?>
                        <button  class="btn btn-primary">Publish User</button>
                      <?php } 
                      else{
                      ?>
                        <button  class="btn btn-primary">Update User</button>
                      <?php } ?>
                  </div>
                </div>

                <div class="row">
                  
                  <!-- First column-->
                  <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-6">
                      <div class="card-header">
                        <h5 class="card-tile mb-0">User information</h5>
                      </div>

                      <div class="card-body">


                      <div class="row">
                        <div class="mb-6 col">
                          <label class="form-label" for="ecommerce-product-name">Name</label>
                          <input
                            type="text"
                            class="form-control"
                            id="ecommerce-product-name"
                            placeholder="name"
                            name="uname"
                            value="<?php echo (($update === true) ? ($user['uname']) : ""); ?>"
                            aria-label="Product title" />
                        </div>
                        <div class="mb-6 col">
                          <label class="form-label" for="ecommerce-product-name">Email</label>
                          <input
                            type="text"
                            class="form-control"
                            id="ecommerce-product-name"
                            placeholder="Email"
                            name="email"
                            value="<?php echo (($update === true) ? ($user['email']) : ""); ?>"
                            aria-label="Product title" />
                        </div>
                      </div>
                       <div class="row mb-6">
                          <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="vendor"> Package </label>
                            <select id="vendor" class="select2 form-select" data-placeholder="Select Vendor" name="package">
                              <option value="0">Select Package</option>
                              <option value="1">Men's Clothing</option>
                              <option value="2">Men's Clothing</option>
                              <option value="3">Men's Clothing</option>
                            </select>
                          </div>
                          <div class="mb-6 col">
                            <label class="form-label" for="ecommerce-product-name">Phone</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-name"
                              placeholder="Phone"
                              name="phone"
                              value="<?php echo (($update === true) ? ($user['phone']) : ""); ?>"
                              aria-label="Product title" />
                          </div>
                        </div>

                        <div class="row mb-6">
                          <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="vendor"> Description </label>
                            <textarea class="form-control add-new-item" rows="2" name="description" placeholder="Description" autofocus required><?php echo (($update === true) ? ($user['description']) : ""); ?></textarea>
                          </div>
                          <div class="mb-6 col">
                            <label class="form-label" for="ecommerce-product-name">Inviter code (Optional)</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-name"
                              placeholder="Inviter code"
                              name="inviter_code"
                              value="<?php echo (($update === true) ? ($user['inviter_code']) : ""); ?>"
                              aria-label="Product title" />
                          </div>
                        </div>

                        <div class="row mb-6">
                          <div class="mb-6 col">
                            <label class="form-label" for="ecommerce-product-name">Password</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-name"
                              placeholder="password"
                              name="password"
                              value=""
                              aria-label="Product title" />
                          </div>
                          <div class="mb-6 col">
                            <label class="form-label" for="ecommerce-product-name">confirm Password</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-name"
                              placeholder="Confirm Password"
                              name="c_password"
                              value=""
                              aria-label="Product title" />
                          </div>
                        </div>
                        
                        <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="status"> Active </label>
                              <select id="status" name="status" class="select2 form-select" data-placeholder="Select Status">
                                <option value="0" <?php echo (($update === true) ? (($user['active'] == 0) ? ('selected') : "") : ""); ?>>Deactive</option>
                                <option value="1" <?php echo (($update === true) ? (($user['active'] == 1) ? ('selected') : "") : ""); ?>>Active</option>
                              </select>
                          </div>


                        
                      </div>
                    </div>
                  </div>

                </div>

                    <?php
                    if($update == false){
                    ?>
                       <input type="hidden" name="add-user-request" id="add-user-request" value="1">
                       <input type="hidden" name="user_id" id="user_id" value="">
                       <input type="hidden" name="role" id="role" value="2">
                    <?php
                    }else{?>
                       <input type="hidden" name="admin-update-user-request" id="update-user-request" value="1">
                       <input type="hidden" name="user_id" id="user_id" value="<?=$_GET['id'];?>">
                       <input type="hidden" name="role" id="role" value="2">
                    <?php }
                    ?>
                    </form>
              </div>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="text-body">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , made with ❤️ by <a href="https://pixinvent.com" target="_blank" class="footer-link">Pixinvent</a>
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="https://themeforest.net/licenses/standard" class="footer-link me-4" target="_blank"
                      >License</a
                    >
                    <a href="https://1.envato.market/pixinvent_portfolio" target="_blank" class="footer-link me-4"
                      >More Themes</a
                    >

                    <a
                      href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"
                      target="_blank"
                      class="footer-link me-4"
                      >Documentation</a
                    >

                    <a href="https://pixinvent.ticksy.com/" target="_blank" class="footer-link d-none d-sm-inline-block"
                      >Support</a
                    >
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/quill/katex.js"></script>
    <script src="assets/vendor/libs/quill/quill.js"></script>
    <script src="assets/vendor/libs/select2/select2.js"></script>
    <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/app-ecommerce-product-add.js"></script>
  </body>


  <script type="text/javascript">

    $(document).ready(function() {

      console.log("aaaaa=> "+$("#description").val());

      $("#updateUserForm").on('submit', (function(e) {
         e.preventDefault();
         

         console.log("form submitted here");
          $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            cache: false,
            processData: false,

            success: function(data) {
              if(data.success == 1){
                  // promptMsg(1,data.msg);
                  alert(data.msg);
                  location.reload();
                  // window.location.href = "/360";
              }
              else{
                  alert(data.msg);
              }
                    
            },
            error: function(e) {
              console.log(e);
            }
          });

        }));


      });

  </script>
</html>
