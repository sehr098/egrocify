  <?php 
  include("inc/header.php"); 
  include("../function.php");
  $packages = get_all_packages('all');

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

            	<!-- CARDS -->
              <!-- <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                  <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                      <div class="col-sm-6 col-lg-4">
                        <div
                          class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">Sales</p>
                            <h4 class="mb-1">$5,345.43</h4>
                            <p class="mb-0">
                            </p>
                          </div>
                          <span class="avatar me-sm-6">
                            <span class="avatar-initial rounded"
                              ><i class="ti-28px ti ti-smart-home text-heading"></i
                            ></span>
                          </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6" />
                      </div>
                      <div class="col-sm-6 col-lg-4">
                        <div
                          class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">Customers</p>
                            <h4 class="mb-1">20k+</h4>
                            <p class="mb-0">
                            </p>
                          </div>
                          <span class="avatar p-2 me-lg-6">
                            <span class="avatar-initial rounded"
                              ><i class="ti-28px ti ti-device-laptop text-heading"></i
                            ></span>
                          </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none" />
                      </div>
                      <div class="col-sm-6 col-lg-4">
                        <div
                          class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                          <div>
                            <p class="mb-1">Product</p>
                            <h4 class="mb-1">55</h4>
                          </div>
                          <span class="avatar p-2 me-sm-6">
                            <span class="avatar-initial rounded"><i class="ti-28px ti ti-gift text-heading"></i></span>
                          </span>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </div> -->

              <!-- Product List Table -->
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Package List</h5>
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
                        <th>Name</th>
                        <th>Desription</th>
                        <th>Price</th>
                        <th>Referral Award</th>
                        <th>Withdraw Limit</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                         <?php
                          while($row=mysqli_fetch_assoc($packages)){
                            $statusLabel = (($row['active'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $statusText = (($row['active'] == 1) ? ("Published") : ("Draft")) ;
                          ?>
                          <tr>
                            <td><?php echo $row['name'] ? $row['name'] : "N/A"; ?></td>
                            <td><?php echo $row['description'] ? $row['description'] : "N/A"; ?></td>
                            <td><?php echo $row['price'] ? $row['price'] : "N/A"; ?></td>
                            <td><?php echo $row['referal_reward'] ? $row['referal_reward'] : "N/A"; ?></td>
                            <td><?php echo $row['withdraw_limit'] ? $row['withdraw_limit'] : "N/A"; ?></td>
                            <td><span class="badge rounded-pill <?=$statusLabel?>"><?=$statusText?></span></td>

                            
                            <td>
                            <div class="d-inline-block text-nowrap">
                              <a href="add_package.php?id=<?=$row['id'];?>"><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i class="ti ti-edit ti-md"></i></button></a>

                              <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></button>
                              
                              <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                <a href="../ajax-request.php?package-publish-request=1&package_id=<?=$row['id'];?>&approved=<?php echo (($row['active'] == 0) ? "1" : "0"); ?>" class="dropdown-item"><?php echo (($row['active'] == 0) ? "Active" : "Deactive"); ?></a>
                                <a href="../ajax-request.php?package-delete-request=1&package_id=<?=$row['id'];?>" class="dropdown-item "><i class="ti ti-trash ti-md"></i></a>
                              </div>
                            </div>
                            </td>
                          </tr>
                         
                        <?php } ?>
                      
                    </tbody>

                  </table>
                </div>
              </div>
            </div>
            <!-- / Content -->

  
  <?php include("inc/footer.php"); ?>