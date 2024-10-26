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
                          <!-- <a href="javascript:void(0)" class="btn btn-primary mb-1">
                            <i class="ti ti-user-check ti-xs me-2"></i>Connected
                          </a> -->
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
                        <a class="nav-link active" href="javascript:void(0);"
                          ><i class="ti-sm ti ti-users me-1_5"></i> Settings</a
                        >
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="change_password.php"
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
                          <span>Administrator</span>
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
                   <div class="card mb-4">
                      <div class="card-header d-flex align-items-center justify-content-between">
                         <h5 class="mb-0">Basic Information</h5>
                         <small class="text-muted float-end"> </small>
                      </div>
                      <div class="card-body">
                            <form id="updateUserForm" class="mb-3" action="../ajax-request.php" method="POST">
                            
                             <div class="row mb-3">
                               <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">User Name</label>
                               <div class="col-sm-10">
                                  <div class="input-group input-group-merge">
                                     <input type="text" class="form-control" id="basic-icon-default-fullname" placeholder="User Name" aria-label="User Name" aria-describedby="basic-icon-default-fullname2" name="uname" value="<?php echo $userDetail['uname'] ? $userDetail['uname'] : ""; ?>"/>
                                  </div>
                               </div>
                            </div>
                            <div class="row mb-3">
                               <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Email</label>
                               <div class="col-sm-10">
                                  <div class="input-group input-group-merge">
                                     <input type="text" class="form-control" id="basic-icon-default-fullname" placeholder="Email" aria-label="Email" aria-describedby="basic-icon-default-fullname2" name="email" value="<?php echo $userDetail['email'] ? $userDetail['email'] : ""; ?>"/>
                                  </div>
                               </div>
                            </div>
                            <div class="row mb-3">
                               <label class="col-sm-2 col-form-label" for="formtabs-package">Package</label>
                               <div class="col-sm-10">
                                  <select id="formtabs-package" class=" form-select" name="package">
                                     <option value="0" >Select Package</option>
                                     <option value="1" >package1</option>
                                     <option value="2" >package2</option>
                                </select>
                              </div>
                             </div>

                             
                             <div class="row mb-3">
                                 <label class="col-sm-2 form-label" for="basic-icon-default-phone">Phone No</label>
                                 <div class="col-sm-10">
                                    <div class="input-group input-group-merge">
                                       <span id="basic-icon-default-phone2" class="input-group-text"><i class="mdi mdi-phone"></i></span>
                                       <input type="text" id="basic-icon-default-phone" class="form-control phone-mask" placeholder="Phone#" aria-label="Phone#" aria-describedby="basic-icon-default-phone2" name="phone" value="<?php echo $userDetail['phone'] ? $userDetail['phone'] : ""; ?>" />
                                    </div>
                                 </div>
                              </div>
                              <div class="row mb-3">
                                 <label class="col-sm-2 form-label" for="basic-icon-default-phone">Description</label>
                                 <div class="col-sm-10">
                                    <textarea class="form-control add-new-item" rows="2" name="description" placeholder="Description" autofocus ><?php echo $userDetail['description'] ? $userDetail['description'] : ""; ?></textarea>
                                 </div>
                              </div>
                              <div class="row mb-3">
                                 <label class="col-sm-2 form-label" for="ecommerce-product-name">Inviter code (Optional)</label>
                                 <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Inviter code"
                                      name="inviter_code" aria-label="Product title" value="<?php echo $userDetail['inviter_code'] ? $userDetail['inviter_code'] : ""; ?>" />
                                    </div>
                              </div>


                                    
                            
                            
                            <div class="row justify-content-end ">
                               <div class="col-sm-10">
                                  <button class="btn btn-primary d-grid w-100">Update Settings</button>
                               </div>

                            </div>

                            <input type="hidden" name="user_id" id="user_id" value="<?=$userDetail['id'];?>">
                            <input type="hidden" name="role" id="role" value="1">
                            <input type="hidden" name="status" id="status" value="1">
                            <input type="hidden" name="update-user-request" id="update-user-request" value="1">
                         </form>
                      </div>
                      
                   </div>
                </div>

                
              </div>
              <!--/ User Profile Content -->
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <?php include('inc/footer.php'); ?>


          <script type="text/javascript">
             $(document).ready(function() {

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