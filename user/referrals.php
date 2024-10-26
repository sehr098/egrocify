<?php

  include("inc/header.php"); 
  include("../function.php");
  $brands = get_all_brands('all');

  $reffered_users = get_all_reffered_users($_SESSION['loggedin_id']);

$referralCode = $_SESSION['loggedin_id'];
$baseURL = "http://seller.egrocify.com/user/register.php";

$referralLink = $baseURL."?inviter=".urlencode(encryptStringShort($referralCode));

$package = get_current_user_det()['package'];
$rewardAmount =  get_single_package($package)['referal_reward'];


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


            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row mb-6 g-6">
                <div class="col-sm-6 col-xl-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                          <h5 class="mb-1">$0</h5>
                          <small>Total Earning</small>
                        </div>
                        <span class="badge bg-label-primary rounded-circle p-2">
                          <i class="ti ti-currency-dollar ti-lg"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                          <h5 class="mb-1">$0</h5>
                          <small>Unpaid Earning</small>
                        </div>
                        <span class="badge bg-label-success rounded-circle p-2">
                          <i class="ti ti-gift ti-lg"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                          <h5 class="mb-1">0</h5>
                          <small>Signups</small>
                        </div>
                        <span class="badge bg-label-danger rounded-circle p-2">
                          <i class="ti ti-users ti-lg"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                  <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="content-left">
                          <h5 class="mb-1">0%</h5>
                          <small>Conversion Rate</small>
                        </div>
                        <span class="badge bg-label-info rounded-circle p-2">
                          <i class="ti ti-infinity ti-lg"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-6 g-6">
                <div class="col-lg-7">
                  <div class="card h-100">
                    <div class="card-body">
                      <h5 class="mb-1">How to use</h5>
                      <p class="mb-6 card-subtitle mt-0">Integrate your referral code in 3 easy steps.</p>
                      <div class="d-flex flex-column flex-sm-row justify-content-between text-center gap-6">
                        <div class="d-flex flex-column align-items-center">
                          <span class="p-4 border-1 border-primary rounded-circle border-dashed mb-0 w-px-75 h-px-75"
                            ><img src="../../assets/svg/icons/rocket.svg" alt="Rocket"
                          /></span>
                          <p class="my-2 w-75">Copy Your link</p>
                          <!-- <h6 class="text-primary mb-0">$50</h6> -->
                        </div>
                        <div class="d-flex flex-column align-items-center">
                          <span class="p-4 border-1 border-primary rounded-circle border-dashed mb-0 w-px-75 h-px-75"
                            ><img src="../../assets/svg/icons/user-info.svg" alt="user-info"
                          /></span>
                          <p class="my-2 w-75">Whatsapp your friends</p>
                          <!-- <h6 class="text-primary mb-0">10%</h6> -->
                        </div>
                        <div class="d-flex flex-column align-items-center">
                          <span class="p-4 border-1 border-primary rounded-circle border-dashed mb-0 w-px-75 h-px-75"
                            ><img src="../../assets/svg/icons/paper.svg" alt="paper"
                          /></span>
                          <p class="my-2 w-75">Earn rewards when they signup</p>
                          <h6 class="text-primary mb-0">$<?php echo $rewardAmount ? number_format((float)$rewardAmount, 2, '.', '') : "0"; ?></h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-5">
                  <div class="card h-100">
                    <div class="card-body">
                    <form id="sendReferralLinkForm" class="mb-3" action="../ajax-request.php" method="POST" >
                        <div class="mb-6 mt-1">
                          <h5 class="mb-5">Invite your friends</h5>
                          <div class="d-flex gap-4 align-items-end">
                            <div class="w-100">
                              <label class="form-label mb-1" for="referralEmail"
                                >Enter friend’s email address and invite them</label
                              >
                              <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control w-100"
                                placeholder="Email address" />
                            </div>
                            <div>
                              <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check ti-xs me-2"></i>Submit
                              </button>
                            </div>
                          </div>
                        </div>
                        <input type="hidden" name="send-referral-email-request" id="send-referral-email-request" value="1">
                        <input type="hidden" name="user_id" id="user_id" value="<?=$_SESSION['loggedin_id']?>">
                        <input type="hidden" name="user_email" id="user_email" value="<?=$_SESSION['loggedin_email']?>">
                      </form>

                        <div>
                          <h5 class="mb-5">Share the referral link</h5>
                          <div class="d-flex gap-4 align-items-end">
                            <div class="w-100">
                              <label class="form-label mb-1" for="referralLink"
                                >Share referral link in social media</label
                              >
                              <input
                                type="text"
                                id="referralLink"
                                name="referralLink"
                                class="form-control w-100 h-px-40"
                                placeholder=""
                                value="<?=$referralLink?>" 
                                onclick="copyToClipboard(this)"
                                readonly
                                />
                            </div>
                            <div>
                              <button type="submit" class="btn btn-secondary"
                                onclick="copyToClipboardButton()"
                              >
                                <i class="ti ti-copy ti-xs me-2"></i>Copy
                              </button>
                            </div>
                            <div class="d-flex">
                             <!--  <button type="button" class="btn btn-facebook btn-icon me-2">
                                <i class="ti ti-brand-facebook text-white ti-md"></i>
                              </button>
                              <button type="button" class="btn btn-twitter btn-icon">
                                <i class="ti ti-brand-twitter text-white ti-md"></i>
                              </button> -->
                            </div>
                          </div>
                        </div>
                      <!-- </form> -->
                    </div>
                  </div>
                </div>
              </div>

              <!-- Referral List Table -->
              <!-- Product List Table -->
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Users Reffered</h5>
                  <div class="d-flex justify-content-between align-items-center row pt-4 gap-6 gap-md-0">
                    <div class="col-md-4 product_status"></div>
                    <div class="col-md-4 product_category"></div>
                    <div class="col-md-4 product_stock"></div>
                  </div>
                </div>
                <div class="card-datatable table-responsive">
                  <table class="datatables-products table" id="example">
                    <thead class="border-top">
                      <tr>
                        <!-- <th></th> -->
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                          if($reffered_users->num_rows > 0){


                          $i = 1;
                          while($row=mysqli_fetch_assoc($reffered_users)){
                            $statusLabel = (($row['active'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $statusText = (($row['active'] == 1) ? ("Published") : ("Draft")) ;
                          ?>
                          <tr>
                            <td><?php echo $row['id'] ? $row['id'] : "N/A"; ?></td>
                            <td><?php echo $row['uname'] ? $row['uname'] : "N/A"; ?></td>
                            <td><?php echo $row['email'] ? $row['email'] : "N/A"; ?></td>
                            
                          </tr>
                        <?php $i++; }
                        }else{ ?>
                          <tr>
                            <td colspan=""></td>
                            <td colspan="3">No Reffered Users </td>
                            <td colspan=""></td>
                          </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                  </table>
                </div>
              </div>



            </div>
            <!-- / Content -->
            
            

  
  <?php include("inc/footer.php"); ?>

  <script type="text/javascript">
    $(document).ready(function(){

       $("#sendReferralLinkForm").on('submit', (function(e) {
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
                  $("#email").val("");
                
                // location.reload();
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
  <script>
        function copyToClipboard(element) {
            // Select the input field text
            alert("link copied");
            element.select();
            element.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the input field
            document.execCommand("copy");

            // Optionally, display a message to indicate text is copied
            var message = document.getElementById("message");
            message.innerHTML = "Copied to clipboard: " + element.value;

            // Deselect the input field text
            window.getSelection().removeAllRanges();
        }

        function copyToClipboardButton() {
          $("#referralLink").trigger("click");
        }
    </script>