  <?php 
  include("inc/header.php"); 
  include("../function.php");

   $update = false;
   $brand = "";
   if(isset($_GET['id'])){
      $update = true;
      $brand = get_single_brand($_GET['id']);
   }
   if($update == true){
      $location = "../".$brand['location'];
      $imgLink = $location.'/'.$brand['logo'];
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

                <form id="updateBrandForm" class="mb-3" action="../ajax-request.php" method="POST" >

                <!-- Add Product -->
                <div
                  class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                  <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Add a new Brand</h4>
                    <!-- <p class="mb-0">Orders placed across your store</p> -->
                  </div>
                  <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                      <a href="brands.php" class="btn btn-label-secondary">Discard</a>
                      <!-- <button class="btn btn-label-primary">Save draft</button> -->
                    </div>
                     <?php
                      if($update == false){
                      ?>
                        <button  class="btn btn-primary">Publish Brand</button>
                      <?php } 
                      else{
                      ?>
                        <button  class="btn btn-primary">Update Brand</button>
                      <?php } ?>
                  </div>
                </div>

                <div class="row">
                  <!-- First column-->
                  <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-6">
                      <div class="card-header">
                        <h5 class="card-tile mb-0">Brand information</h5>
                      </div>

                      <div class="card-body">


                      <div class="row">
                        <div class="mb-6 col">
                          <label class="form-label" for="ecommerce-product-name">Name</label>
                          <input
                            type="text"
                            class="form-control"
                            id="ecommerce-product-name"
                            placeholder="Brand"
                            name="name"
                            value="<?php echo (($update === true) ? ($brand['name']) : ""); ?>"
                            aria-label="Product title" />
                        </div>
                        <div class="mb-6 col">
                          <label class="form-label" for="ecommerce-product-name">Company</label>
                          <input
                            type="text"
                            class="form-control"
                            id="ecommerce-product-name"
                            placeholder="Company"
                            name="company_id"
                            value="<?php echo (($update === true) ? ($brand['company_id']) : ""); ?>"
                            aria-label="Product title" />
                        </div>
                      </div>



                        <!-- Description -->
                          <div class="col">
                            <label class="mb-1"> Description</label>
                            <div class="form-control p-0">
                              <div class="comment-toolbar border-0 border-bottom">
                                <div class="d-flex justify-content-start">
                                  <span class="ql-formats me-0">
                                    <button class="ql-bold"></button>
                                    <button class="ql-italic"></button>
                                    <button class="ql-underline"></button>
                                    <button class="ql-list" value="ordered"></button>
                                    <button class="ql-list" value="bullet"></button>
                                    <button class="ql-link"></button>
                                    <button class="ql-image"></button>
                                  </span>
                                </div>
                              </div>
                              <div class="comment-editor border-0 pb-6" id="ecommerce-category-description"></div>
                            </div>
                          </div>

                          <div class="mb-6 col ecommerce-select2-dropdown">
                              <label class="form-label mb-1" for="status"> Active </label>
                              <select id="status" name="status" class="select2 form-select" data-placeholder="Select Status">
                                <option value="0" <?php echo (($update === true) ? (($brand['active'] == 0) ? ('selected') : "") : ""); ?>>Deactive</option>
                                <option value="1" <?php echo (($update === true) ? (($brand['active'] == 1) ? ('selected') : "") : ""); ?>>Active</option>
                              </select>
                            </div>
                        
                      </div>
                    </div>
                  </div>

                  <!-- Second column -->
                  <div class="col-12 col-lg-4">
                    
                    <!-- IMAGE CODE -->
                    <div class="card mb-3">
                          <div class="col">
                            <div class="card-header d-flex justify-content-between align-items-center">
                              <h5 class="mb-0 card-title">Logo</h5>
                            </div>
                            <?php
                            if($update == true){
                            ?>
                            <div class="img">
                              <img src="<?php echo (($update === true) ? ($imgLink) : ""); ?>" alt="Image" 
                              style="width: -webkit-fill-available;">
                            </div>
                          <?php } ?>

                            <div class="card-body">
                                <div class="dz-message needsclick">
                                  <p class="h4 needsclick pt-3 mb-2">Drag and drop your image here</p>
                                  <p class="h6 text-muted d-block fw-normal mb-2">or</p>
                                  <span class="note needsclick btn btn-sm btn-label-primary" id="btnBrowse"
                                    >Browse image</span
                                  >
                                </div>
                                <div class="fallback">
                                  <input name="logo[]" id="logo" type="file" />
                                </div>
                            </div>
                          </div>
                      </div>
                    <!-- IMAGE CODE -->


                    <!-- /Organize Card -->
                  </div>
                  <!-- /Second column -->

                  <textarea name="description" id="description" hidden ><?php echo (($update === true) ? ($brand['description']) : ""); ?></textarea>
                    <?php
                    if($update == false){
                    ?>
                       <input type="hidden" name="add-brand-request" id="add-brand-request" value="1">
                       <input type="hidden" name="brand_id" id="brand_id" value="">
                    <?php
                    }else{?>
                       <input type="hidden" name="update-brand-request" id="update-brand-request" value="1">
                       <input type="hidden" name="brand_id" id="brand_id" value="<?=$_GET['id'];?>">
                    <?php }
                    ?>
                    </form>

                </div>
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
    $(".ql-editor").html($("#description").val());

    console.log("aaaaa=> "+$("#description").val());

    $("#updateBrandForm").on('submit', (function(e) {
       e.preventDefault();
       
       $("#description").val($(".ql-editor").html());

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