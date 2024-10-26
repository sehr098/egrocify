  <?php
  include("inc/header.php"); 
  include("../function.php");

   $update = false;
   $product = "";
   if(isset($_GET['id'])){
      $update = true;
      $product = get_single_product($_GET['id']);
   }
   if($update == true){
      $location = "../".$product['location'];
      $imgLink = $location.'/'.$product['featured_image'];
      $imgLinks = $location.'/'.$product['images'];
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
                <form id="updateProductForm" class="mb-3" action="../ajax-request.php" method="POST" >

                <!-- Add Product -->
                <div
                  class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                  <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1">Add a new Product</h4>
                    <p class="mb-0">Orders placed across your store</p>
                  </div>
                  <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                      <a class="btn btn-label-secondary" href="products.php">Discard</a>
                    </div>
                    <?php
                      if($update == false){
                      ?>
                        <button  class="btn btn-primary">Publish Product</button>
                      <?php } 
                      else{
                      ?>
                        <button  class="btn btn-primary">Update Product</button>
                      <?php } ?>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 col-lg-8">
                    <div class="card mb-6">
                      <div class="card-header">
                        <h5 class="card-tile mb-0">Product information</h5>
                      </div>
                      <div class="card-body">
                        <div class="mb-6">
                          <label class="form-label" for="ecommerce-product-name">Name</label>
                          <input
                            type="text"
                            class="form-control"
                            id="ecommerce-product-name"
                            placeholder="Product title"
                            name="name"
                            value="<?php echo (($update === true) ? ($product['name']) : ""); ?>"
                            aria-label="Product title" />
                        </div>
                        <div class="row mb-6">
                          <div class="col">
                            <label class="form-label" for="ecommerce-product-sku">SKU</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-sku"
                              placeholder="SKU"
                              name="sku"
                              value="<?php echo (($update === true) ? ($product['sku']) : ""); ?>"
                              aria-label="Product SKU" />
                          </div>
                          <div class="col">
                            <label class="form-label" for="ecommerce-product-barcode">Barcode</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-barcode"
                              placeholder="0123-4567"
                              name="barcode"
                              value="<?php echo (($update === true) ? ($product['barcode']) : ""); ?>"
                              aria-label="Product barcode" />
                          </div>
                        </div>


                        <div class="row mb-6">
                          <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="vendor"> Package </label>
                            <select id="vendor" class="select2 form-select" data-placeholder="Select Package" name="package">
                               <?php

                              $getPackageDropdown = getPackageDropdown();
                              // var_dump($getPackageDropdown);
                              // die();
                              foreach ($getPackageDropdown as $code => $codeVal) { ?>
                                  <option value="<?=$code;?>" <?= (isset($product['package'])) ? ((($product['package'] == $code) ? ('selected') : ("") )) : (""); ?> ><?=$codeVal;?></option> 
                              <?php } ?>
                            </select>
                          </div>
                          <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="vendor"> Brand </label>
                            <select id="vendor" class="select2 form-select" data-placeholder="Select Brand" name="brand">
                               <?php

                              $getBrandDropdown = getBrandDropdown();
                              // var_dump($getBrandDropdown);
                              // die();
                              foreach ($getBrandDropdown as $code => $codeVal) { ?>
                                  <option value="<?=$code;?>" <?= (isset($product['brand'])) ? ((($product['brand'] == $code) ? ('selected') : ("") )) : (""); ?> ><?=$codeVal;?></option> 
                              <?php } ?>
                            </select>
                          </div>
                        </div>

                        <div class="row mb-6">
                          <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label" for="ecommerce-product-sku">ROI</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-sku"
                              placeholder="ROI"
                              name="roi"
                              value="<?php echo (($update === true) ? ($product['roi']) : ""); ?>"
                              aria-label="Product SKU" />
                          </div>
                          <div class="mb-6 col ecommerce-select2-dropdown">
                           <label class="form-label" for="ecommerce-product-sku">ROI Days</label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-sku"
                              placeholder="ROI days"
                              name="roi_days"
                              value="<?php echo (($update === true) ? ($product['roi_days']) : ""); ?>"
                              aria-label="Product SKU" />
                          </div>
                        </div>

                        <div class="row mb-6">
                          <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="vendor"> InStock </label>
                            <input
                              type="text"
                              class="form-control"
                              id="ecommerce-product-sku"
                              placeholder="InStock"
                              name="in_stock"
                              value="<?php echo (($update === true) ? ($product['in_stock']) : ""); ?>"
                              aria-label="Product SKU" />
                          </div>
                          <div class="mb-6 col ecommerce-select2-dropdown">
                              <label class="form-label mb-1" for="status"> Active </label>
                              <select id="status" name="status" class="select2 form-select" data-placeholder="Select Status">
                                <option value="0" <?php echo (($update === true) ? (($product['active'] == 0) ? ('selected') : "") : ""); ?>>Deactive</option>
                                <option value="1" <?php echo (($update === true) ? (($product['active'] == 1) ? ('selected') : "") : ""); ?>>Active</option>
                              </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="row">
                          <div class="col">
                            <label class="mb-1">Short Description</label>
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
                          <div class="col">
                            <label class="mb-1">Description</label>
                            <div class="form-control p-0">
                              <div class="comment-toolbarw border-0 border-bottom">
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
                              <div class="comment-editor2 border-0 pb-6" id="ecommerce-category-description"></div>
                            </div>
                          </div>
                        </div>



                      </div>
                    </div>
                    <div class="card mb-3">
                      <div class="row">

                        <div class="col">
                          <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Featured Image</h5>
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
                                <input name="f_image[]" id="f_image" type="file" />
                              </div>
                          </div>
                        </div>
                        <div class="col">
                          <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Images</h5>
                          </div>
                          <?php
                          if($update == true){
                          ?>
                          <div class="img">
                            <img src="<?php echo (($update === true) ? ($imgLinks) : ""); ?>" alt="Image" 
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
                                <input name="images[]" id="images" type="file" />
                              </div>
                          </div>
                        </div>


                      </div>
                    </div>

                   
                  </div>
                  <!-- /Second column -->

                  <!-- Second column -->
                  <div class="col-12 col-lg-4">
                    <!-- Pricing Card -->
                    <div class="card mb-6">
                      <div class="card-header">
                        <h5 class="card-title mb-0">Pricing</h5>
                      </div>
                      <div class="card-body">
                        <!-- Base Price -->
                        <div class="mb-6">
                          <label class="form-label" for="ecommerce-product-price">Cost</label>
                          <input
                            type="number"
                            class="form-control"
                            id="ecommerce-product-price"
                            placeholder="Cost"
                            name="cost"
                            value="<?php echo (($update === true) ? ($product['cost']) : ""); ?>"
                            aria-label="Product price" />
                        </div>
                        <!-- Discounted Price -->
                        <div class="mb-6">
                          <label class="form-label" for="ecommerce-product-discount-price"> Price</label>
                          <input
                            type="number"
                            class="form-control"
                            id="ecommerce-product-discount-price"
                            placeholder=" Price"
                            name="price"
                            value="<?php echo (($update === true) ? ($product['price']) : ""); ?>"
                            aria-label="Product discounted price" />
                        </div>
                        <!-- Charge tax check box -->
                        <!-- <div class="form-check ms-2 mt-2 mb-4">
                          <input class="form-check-input" type="checkbox" value="" id="price-charge-tax" checked />
                          <label class="switch-label" for="price-charge-tax"> Charge tax on this product </label>
                        </div> -->
                        <!-- Instock switch -->
                        <!-- <div class="d-flex justify-content-between align-items-center border-top pt-2">
                          <span class="mb-0">In stock</span>
                          <div class="w-25 d-flex justify-content-end">
                            <div class="form-check form-switch me-n3">
                              <input type="checkbox" class="form-check-input" />
                            </div>
                          </div>
                        </div> -->
                      </div>
                    </div>
                    <!-- /Pricing Card -->
                    <!-- Organize Card -->
                    
                    <!-- /Organize Card -->
                  </div>
                  <!-- /Second column -->

                  <textarea name="short_description" id="short_description" hidden ><?php echo (($update === true) ? ($product['short_description']) : ""); ?></textarea>
                  <textarea name="long_description" id="long_description" hidden ><?php echo (($update === true) ? ($product['long_description']) : ""); ?></textarea>
                    <?php
                    if($update == false){
                    ?>
                       <input type="hidden" name="add-product-request" id="add-product-request" value="1">
                       <input type="hidden" name="product_id" id="product_id" value="">
                    <?php
                    }else{?>
                       <input type="hidden" name="update-product-request" id="update-product-request" value="1">
                       <input type="hidden" name="product_id" id="product_id" value="<?=$_GET['id'];?>">
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
      $(".comment-editor").find(".ql-editor").html($("#short_description").val());
      $(".comment-editor2").find(".ql-editor").html($("#long_description").val());
      // $(".ql-editor").html($("#description").val());

      console.log("aaaaa=> "+$("#description").val());

      $("#updateProductForm").on('submit', (function(e) {
         e.preventDefault();
         
         $("#short_description").val($(".comment-editor").find(".ql-editor").html());
         $("#long_description").val($(".comment-editor2").find(".ql-editor").html());

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
