<?php 
  include("inc/header.php"); 
  include("../function.php");
  $packages = get_all_packages('approved');
  if(isset($_SESSION['loggedin_id'])){
    $current_package = get_current_user_det()['package'];
  }else{
      $current_package= 0;
  }
  $subscriptions = get_all_subscription('all', $_SESSION['loggedin_id']);


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


          <!-- Pricing Plans -->
          <section class="section-py first-section-pt">
            <div class="container">
              <h2 class="text-center mb-2">My Subscriptions</h2>
              <!-- <p class="text-center mb-0">
                All plans include various advanced tools and features to boost your product.<br />
                Choose the best plan to fit your needs.
              </p> -->

              <div class="row g-6">
                <!-- Basic -->
                <?php
                while($row=mysqli_fetch_assoc($packages)){

                    $btnText = "Your Current Plan";
                    $btnClass = "btn-label-success";
                    $upgrade_redirection = "";
                  if(check_expiry() == true){
                    $btnText = "Expired! Renew now";
                    $btnClass = "btn-label-danger";
                    $upgrade_redirection = "upgrade_redirection";
                  }

                  $buttonLabel = (($row['id'] == $current_package) ? ($btnClass) : ("btn-primary")) ;
                  $buttonText = (($row['id'] == $current_package) ? ($btnText) : ("Subscribe")) ;
                  $upgrade_class = (($row['id'] == $current_package) ? ($upgrade_redirection) : ("upgrade_redirection")) ;

                  $ml = 1;
                  if($row['id'] == $current_package){
                    $ml = get_current_user_det()['month_limit'];
                  }
                  if($ml > 1)
                    $ml .= " Months";
                  else
                    $ml .= " Month";
                ?>

                <div class="col-lg">
                  <div class="card border rounded shadow-none">
                    <div class="card-body pt-12 px-5">
                      <div class="mt-3 mb-5 text-center">
                        <img src="assets/img/illustrations/page-pricing-basic.png" alt="Basic Image" height="120" />
                      </div>
                      <h4 class="card-title text-center text-capitalize mb-1"><?php echo $row['name'] ? $row['name'] : "-"; ?></h4>
                <p class="text-center mb-5"><?php echo $row['short_description'] ? $row['short_description'] : "-"; ?></p>
                      <div class="text-center h-px-50">
                        <div class="d-flex justify-content-center">
                          <sup class="h6 text-body pricing-currency mt-2 mb-0 me-1"><?=currency_sym()?></sup>
                          <h1 class="mb-0 text-primary"><?php echo $row['price'] ? number_format((float)$row['price'], 2, '.', '') : "0.00"; ?></h1>
                          <sub class="h6 text-body pricing-duration mt-auto mb-1 ms-1">/<?=$ml;?></sub>
                        </div>
                      </div>

                     <div class="ps-6 my-5 pt-9">
                      <?php echo $row['description'] ? $row['description'] : "-"; ?>
                    </div>
                       <?php if(!isset($_SESSION['loggedin_id'])){ ?>

                      <a href="user/login.php" class="btn btn-primary" target="_blank"
                        ><span class="tf-icons ti ti-login scaleX-n1-rtl me-md-1"></span
                        ><span class="d-none d-md-block">Please login to proceed</span></a
                      ><?php }else{ ?>
                      <a href="javascript:void(0);" class="btn <?=$upgrade_class;?> <?=$buttonLabel;?> d-grid w-100" id="<?=$row['id'];?>" onclick="upgrade_class_trigger(this)"><?=$buttonText;?></a>
                    <?php } ?>
                    </div>
                  </div>
                </div>
                <?php
                }
                ?>
       
              </div>


                           <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Subscription List</h5>
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
                        <th>Reference No</th>
                        <th>Package</th>
                        <th>Username</th>
                        <th>Grand Total</th>
                        <th>Product Count</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Pending Payment</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <!-- <td>asdsad</td> -->
                        <!-- <td>asdsad</td> -->

                          <?php
                          $i = 1;
                          while($row=mysqli_fetch_assoc($subscriptions)){
                            $statusLabel = (($row['paid'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $statusText = (($row['paid'] == 1) ? ("Paid") : ("unpaid")) ;

                            $month_limit = (($row['month_limit'] == 1) ? ("1 Month") : ($row['month_limit']." Months")) ;


                          ?>
                          <tr>
                            <td><?php echo $row['id'] ? $row['id'] : "-"; ?></td>
                            <td><?php echo $row['package_id'] ? get_single_package($row['package_id'])['name'] : "-"; ?> ( <?=$month_limit;?> )</td>
                            <td><?php echo $row['user_id'] ? get_user_det($row['user_id'])['uname'] : "-"; ?></td>
                            <td>$<?php echo $row['price'] ? number_format((float)$row['price'], 2, '.', '') : "0"; ?></td>
                            <td><?php echo $row['package_id'] ? count_products_by_package($row['package_id']) : "0"; ?></td>
                            <td><?= system_date_format($row['created_at']); ?></td>
                            <td><span class="<?=$statusLabel?>"><?=$statusText?></span></td>
                            <td>
                              <?php 
                              if($row['paid'] == 0){ ?>
                                <a href="../pending_payment.php?ord_id=<?=$row['id']?>&user_id=<?=$row['user_id']?>" class="btn btn-secondary">Pay to upgrade</a>
                              <?php } ?>
                            </td>
                            <!-- <td>
                            <div class="d-inline-block text-nowrap">
                              <a href="subscription_detail.php?id=<?=$row['id'];?>"><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i class="ti ti-eye ti-md"></i></button></a>
                            </div>
                            </td> -->
                          </tr>
                        <?php $i++; } ?>
                      
                    </tbody>

                  </table>
                </div>
              </div>


            </div>
          </section>

  
  <?php include("inc/footer.php"); ?>
  <script type="text/javascript">
    // $(document).ready(function(){
      function upgrade_class_trigger(eee){
        var subs_id = $(eee).attr('id');
        var user_id = "<?php echo $_SESSION['loggedin_id']; ?>";

      //   console.log(user_id);

      // $.post('../ajax-request.php', { 'user-upgrade-package' : 1, 'subs_id' : subs_id, 'user_id' : user_id}, function(data){
      //   if(data == 1)
      //   {
      //     alert("Upgraded Successfull");
      //     location.reload();
      //   }
      // }); return false; 

          var url = '../payment_page.php';
    var form = $('<form action="' + url + '" method="POST">' +
        '<input type="hidden" name="pkg_id" value="'+subs_id+'" />' +
        '<input type="hidden" name="payment_mode" value="3" />' +
        '</form>');
    
    $('body').append(form);
    form.submit();


      }
    // })
  </script>
