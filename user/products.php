<?php 
  include("inc/header.php"); 
  include("../function.php");
  $products = get_all_products('all');
  $user_id = 0;
  $userData = 0;
  $package = 0;

  if(isset($_SESSION['loggedin_id'])){
    $user_id = $_SESSION['loggedin_id'];
    $package = get_current_user_det()['package'];

  }

  // EXPIRY FUNCTIONALITY
    $resellbox = '  ';
    if(check_expiry() == true){
      $resellbox = ' d-none ';
    }


  // EXPIRY FUNCTIONALITY
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
        <?php include("inc/topbar.php"); ?>
        <!-- / Navbar -->

          

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="app-academy">

                <div class="card mb-6">
                  <div class="card-header ">
                    <div class="card-title mb-0 me-1">
                      <h5 class="mb-0">My Products</h5>
                      <!-- <p class="mb-0">Total 3</p> -->
                    </div>
                    <!-- FILTER -->
                    <!-- <div class="d-flex justify-content-md-end align-items-center column-gap-6">
                      <select class="form-select">
                        <option value="">All Courses</option>
                        <option value="ui/ux">UI/UX</option>
                        <option value="seo">SEO</option>
                        <option value="web">Web</option>
                        <option value="music">Music</option>
                        <option value="painting">Painting</option>
                      </select>

                      <div class="form-check form-switch my-2 ms-2">
                        <input type="checkbox" class="form-check-input" id="CourseSwitch" />
                        <label class="form-check-label text-nowrap mb-0" for="CourseSwitch">Hide completed</label>
                      </div>
                    </div>
                  </div> -->

                  <div class="row gy-6 mb-6">
                      

                    <div class="card-body col-sm-6 col-lg-8">
                      <!-- LISTING -->
                      <div class="row gy-6 mb-6 <?=$resellbox?>" id="dataContainer" >
                      </div>
                      <!-- LISTING -->
                    </div>

                    <div class="card-body col-sm-4 col-lg-4">
                      <!-- <div class="col-sm-6 col-lg-4">
                          <div class="card p-2 h-100 shadow-none border">
                            <div class="rounded-2 text-center mb-4">
                              <a href="app-academy-course-details.html"
                                ><img
                                  class="img-fluid"
                                  src="../../assets/img/pages/app-academy-tutor-1.png"
                                  alt="tutor image 1"
                              /></a>
                            </div>
                            <div class="card-body p-4 pt-2">
                              <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="badge bg-label-primary">Web</span>
                                <p class="d-flex align-items-center justify-content-center fw-medium gap-1 mb-0">
                                  4.4 <span class="text-warning"><i class="ti ti-star-filled ti-lg me-1"></i></span
                                  ><span class="fw-normal">(1.23k)</span>
                                </p>
                              </div>
                              <a href="app-academy-course-details.html" class="h5">Basics of Angular</a>
                              <p class="mt-1">Introductory course for Angular and framework basics in web development.</p>
                              <p class="d-flex align-items-center mb-1"><i class="ti ti-clock me-1"></i>30 minutes</p>
                              <div class="progress rounded-pill mb-4" style="height: 8px">
                                <div
                                  class="progress-bar w-75"
                                  role="progressbar"
                                  aria-valuenow="25"
                                  aria-valuemin="0"
                                  aria-valuemax="100"></div>
                              </div>
                              <div
                                class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                <a
                                  class="w-100 btn btn-label-secondary d-flex align-items-center"
                                  href="app-academy-course-details.html">
                                  <i class="ti ti-rotate-clockwise-2 ti-xs align-middle scaleX-n1-rtl me-2"></i
                                  ><span>Start Over</span>
                                </a>
                                <a
                                  class="w-100 btn btn-label-primary d-flex align-items-center"
                                  href="app-academy-course-details.html">
                                  <span class="me-2">Continue</span
                                  ><i class="ti ti-chevron-right ti-xs scaleX-n1-rtl"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                      </div> -->

                      <input type="hidden" name="searchbar" value="">
                      <input type="hidden" name="package" id="package" value="<?=$package?>">
                      <button type="submit" class="src-btn" id="search" style="visibility: hidden;">
                    </div>

                  </div>

                </div>

              
              </div>
            </div>
            <!-- / Content -->

  
  <?php include("inc/footer.php"); ?>
  <script type="text/javascript">
  $(document).ready(function(){
    $("#search").trigger("click");
  });


  $("#search").click(function(){

    var searchbar = $("#searchbar").val();
    var package = $("#package").val();
    console.log("tes" + package);
    $.post('pagination/getDataProduct.php', {'page' : 0, 'searchbar' : searchbar, 'package' : package}, function(data){$('#dataContainer').html(data); }); return false; 
  });

  function resell_trigger(ee){
    var prod_id = $(ee).attr('id');

    var qty = $(ee).prev().val();
    console.log(qty);

    var url = 'checkout.php';
    var form = $('<form action="' + url + '" method="POST">' +
    '<input type="hidden" name="prod_id" value="'+prod_id+'" />' +
    '<input type="hidden" name="qty" value="'+qty+'" />' +
    '<input type="hidden" name="payment_mode" value="3" />' +
    '</form>');
    $('body').append(form);
    form.submit();
  }
</script>