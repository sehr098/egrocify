  <?php 
  include("inc/header.php"); 
  include("../function.php");
  $orders = get_all_orders('all');

  $data_subsc=mysqli_fetch_assoc($orders);

  $user_total_sale = total_user_sale_by_pkg($data_subsc['package_id']); 

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
              <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                  <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                      <div class="col-sm-6 col-lg-3">
                        <div
                          class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">Sales</p>
                            <h4 class="mb-1">$<?php echo $user_total_sale ? number_format((float)$user_total_sale, 2, '.', '') : "0"; ?></h4>
                            <p class="mb-0">
                              <!-- <span class="me-2">5k orders</span><span class="badge bg-label-success">+5.7%</span> -->
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
                      <div class="col-sm-6 col-lg-3">
                        <div
                          class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">Customers</p>
                            <h4 class="mb-1">20k+</h4>
                            <p class="mb-0">
                              <!-- <span class="me-2">21k orders</span><span class="badge bg-label-success">+12.4%</span> -->
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
                      <div class="col-sm-6 col-lg-3">
                        <div
                          class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                          <div>
                            <p class="mb-1">Product</p>
                            <h4 class="mb-1">55</h4>
                            <!-- <p class="mb-0">6k orders</p> -->
                          </div>
                          <span class="avatar p-2 me-sm-6">
                            <span class="avatar-initial rounded"><i class="ti-28px ti ti-gift text-heading"></i></span>
                          </span>
                        </div>
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <p class="mb-1">Revenue</p>
                            <h4 class="mb-1">$88k</h4>
                            <p class="mb-0">
                              <!-- <span class="me-2">150 orders</span><span class="badge bg-label-danger">-3.5%</span> -->
                            </p>
                          </div>
                          <span class="avatar p-2">
                            <span class="avatar-initial rounded"
                              ><i class="ti-28px ti ti-wallet text-heading"></i
                            ></span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Product List Table -->
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Order List</h5>
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
                        <th>Username</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Grand Total</th>
                        <th>Created At</th>
                        <th>Active</th>
                        <th>Status</th>
                        <th>ROI Paid</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                          <?php
                          $i = 1;
                          while($row=mysqli_fetch_assoc($orders)){
                            $activeLabel = (($row['active'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $activeText = (($row['active'] == 1) ? ("Active") : ("In-active")) ;

                            $statusLabel = (($row['paid_status'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $statusText = (($row['paid_status'] == 1) ? ("Paid") : ("Un-Paid")) ;

                            $roiLabel = (($row['roi_paid'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $roiText = (($row['roi_paid'] == 1) ? ("Paid") : ("Un-Paid")) ;
                          ?>
                          <tr>
                            <td><?php echo $row['id'] ? $row['id'] : "N/A"; ?></td>
                            <td><?php echo $row['user_id'] ? get_user_det($row['user_id'])['uname'] : "N/A"; ?></td>
                            <td><?php echo $row['product_id'] ? get_single_product($row['product_id'])['name'] : "-"; ?></td>
                            <td>$<?php echo $row['price'] ? number_format((float)$row['price']/$row['qty'], 2, '.', '') : "0"; ?></td>
                            <td>$<?php echo $row['qty'] ? number_format((float)$row['qty'], 2, '.', '') : "0"; ?></td>
                            <td>$<?php echo $row['price'] ? number_format((float)$row['price'], 2, '.', '') : "0"; ?></td>
                            <td><?= system_date_format($row['created_at']); ?></td>
                            <td><span class="<?=$activeLabel;?>"><?=$activeText;?></span></td>
                            <td><span class="<?=$statusLabel;?>"><?=$statusText;?></span></td>
                            <td><span class="<?=$roiLabel;?>"><?=$roiText;?></span></td>
                            <td>
                            <div class="d-inline-block text-nowrap">
                              <a href="order_detail.php?id=<?=$row['id'];?>"><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i class="ti ti-eye ti-md"></i></button></a>
                            </div>
                            </td>
                          </tr>
                        <?php $i++; } ?>
                      
                    </tbody>

                  </table>
                </div>
              </div>
            </div>
            <!-- / Content -->

  
  <?php include("inc/footer.php"); ?>