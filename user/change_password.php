<?php 

include('inc/header.php'); 
include('../function.php'); 
$userDetail = "";
   $getSlugURL = "";
   if(isset($_GET['updateId'])){
      $userDetail = get_user_det($_GET['updateId']);
      $getSlugURL = "?updateId=".$_GET['updateId'];
   }
   else{
      if(isset($_SESSION['loggedin_id'])){
           $userDetail = get_current_user_det();
       }
   }
?>

    <link rel="stylesheet" href="assets/vendor/libs/@form-validation/form-validation.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/page-user-view.css" />



  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <?php include('inc/sidebar.php'); ?>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
        <?php include('inc/topbar.php'); ?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Header -->
              <div class="row">
                <div class="col-12">
                  <div class="card mb-6">
                    <div class="user-profile-header-banner">
                      <img src="assets/img/pages/profile-banner.png" alt="Banner image" class="rounded-top" />
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                      <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img
                          src="assets/img/avatars/1.png"
                          alt="user image"
                          class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img" />
                      </div>
                      <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div
                          class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                          <div class="user-profile-info">
                            <h4 class="mb-2 mt-lg-6"><?= $_SESSION['loggedin_name'] ?></h4>
                            <ul
                              class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                              <li class="list-inline-item d-flex gap-2 align-items-center">
                                <i class="ti ti-calendar ti-lg"></i><span class="fw-medium"> Joined <?= system_date_format($userDetail['created_date']); ?></span>
                              </li>
                            </ul>
                          </div>
                          <a href="javascript:void(0)" class=" <?php echo $userDetail['verified'] == 1 ? 'btn btn-success' : 'btn btn-warning'; ?> mb-1">
                            <i class="ti  <?php echo $userDetail['verified'] == 1 ? 'ti-user-check' : 'ti-user-cancel'; ?> ti-xs me-2"></i><?php echo $userDetail['verified'] == 1 ? 'Verified' : 'Un-Verified'; ?>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--/ Header -->

              <!-- Navbar pills -->
              <div class="row">
                <div class="col-md-12">
                  <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-2 gap-lg-0">
                      <li class="nav-item">
                        <a class="nav-link " href="profile.php"
                          ><i class="ti-sm ti ti-user-check me-1_5"></i> Profile</a
                        >
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="profile_settings.php"
                          ><i class="ti-sm ti ti-users me-1_5"></i> Settings</a
                        >
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"
                          ><i class="ti-sm ti ti-layout-grid me-1_5"></i> Change Password</a
                        >
                      </li>
                      <!-- <li class="nav-item">
                        <a class="nav-link" href="pages-profile-connections.html"
                          ><i class="ti-sm ti ti-link me-1_5"></i> Connections</a
                        >
                      </li> -->
                    </ul>
                  </div>
                </div>
              </div>
              <!--/ Navbar pills -->

              <!-- User Profile Content -->
              <div class="row">

                <div class="col-xl-4 col-lg-5 col-md-5">
                  <!-- About User -->
                  <div class="card mb-6">
                    <div class="card-body">
                      <small class="card-text text-uppercase text-muted small">About</small>
                      <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                          <i class="ti ti-user ti-lg"></i><span class="fw-medium mx-2">Full Name:</span>
                          <span><?php echo $userDetail['uname'] ? $userDetail['uname'] : "N/A"; ?></span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                          <i class="ti ti-crown ti-lg"></i><span class="fw-medium mx-2">Role:</span>
                          <span>Customer</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                          <i class="ti ti-flag ti-lg"></i><span class="fw-medium mx-2">Country:</span> <span><?php echo $userDetail['country'] ? $userDetail['country'] : "N/A"; ?></span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                          <i class="ti ti-language ti-lg"></i><span class="fw-medium mx-2">Languages:</span>
                          <span>English</span>
                        </li>
                      </ul>
                      <small class="card-text text-uppercase text-muted small">Contacts</small>
                      <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                          <i class="ti ti-phone-call ti-lg"></i><span class="fw-medium mx-2">Contact:</span>
                          <span><?php echo $userDetail['phone'] ? $userDetail['phone'] : "N/A"; ?></span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                          <i class="ti ti-mail ti-lg"></i><span class="fw-medium mx-2">Email:</span>
                          <span><?php echo $userDetail['email'] ? $userDetail['email'] : "N/A"; ?></span>
                        </li>
                      </ul>
                      
                    </div>
                  </div>
                  <!--/ About User -->
                  
                </div>

                <div class="col-xl-8 col-lg-8 col-md-8">
                  
                  <!-- Change Password -->
                  <div class="card mb-6">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                      <!-- <form id="formChangePassword" method="GET" onsubmit="return false"> -->
                        <form id="formChangePassword" class="mb-3" action="../ajax-request.php" method="GET" onsubmit="return true">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                          <h5 class="alert-heading mb-1">Ensure that these requirements are met</h5>
                          <span>Minimum 8 characters long, uppercase & symbol</span>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="row gx-6">
                          <div class="mb-4 col-12 col-sm-6 form-password-toggle">
                            <label class="form-label" for="newPassword">New Password</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                id="newPassword"
                                name="newPassword"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                          </div>

                          <div class="mb-4 col-12 col-sm-6 form-password-toggle">
                            <label class="form-label" for="confirmPassword">Confirm New Password</label>
                            <div class="input-group input-group-merge">
                              <input
                                class="form-control"
                                type="password"
                                name="confirmPassword"
                                id="confirmPassword"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                          </div>
                          <div>
                            <button  class="btn btn-primary me-2">Change Password</button>
                          </div>
                        </div>
                        <input type="hidden" name="user_id" id="user_id" value="<?=$userDetail['id'];?>">
                        <input type="hidden" name="role" id="role" value="2">
                        <input type="hidden" name="status" id="status" value="1">
                        <input type="hidden" name="update-user-password-request" id="update-user-password-request" value="1">
                      </form>
                    </div>
                  </div>
                  <!--/ Change Password -->


                </div>

                
              </div>
              <!--/ User Profile Content -->
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include('inc/footer.php'); ?>

            <!-- PAGE JS -->
            <!-- FOR PASSWORD VALIDATION -->
            <!-- <script src="assets/vendor/libs/@form-validation/popular.js"></script>
            <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
            <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script>
            <script src="assets/js/app-user-view-security.js"></script> -->


             <!-- Vendors JS -->
            <script src="assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
            <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
            <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
            <script src="assets/vendor/libs/select2/select2.js"></script>
            <script src="assets/vendor/libs/@form-validation/popular.js"></script>
            <script src="assets/vendor/libs/@form-validation/bootstrap5.js"></script>
            <script src="assets/vendor/libs/@form-validation/auto-focus.js"></script>

            <!-- Page JS -->
            <script src="assets/js/modal-edit-user.js"></script>
            <script src="assets/js/modal-enable-otp.js"></script>
            <script src="assets/js/app-user-view.js"></script>
            <script src="assets/js/app-user-view-security.js"></script>

            <script type="text/javascript">
            $(document).ready(function() {

              $("#formChangePassword").on('submit', (function(e) {
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