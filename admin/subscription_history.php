  <?php 
  include("inc/header.php"); 
  include("../function.php");
  $subscriptions = get_all_subscription('all');

  $data_subsc=mysqli_fetch_assoc($subscriptions);

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
            

              <!-- Product List Table -->
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
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <!-- <td>asdsad</td> -->
                        <!-- <td>asdsad</td> -->

                          <?php
                          $i = 1;
                          while($row=mysqli_fetch_assoc($subscriptions)){
                            $statusLabel = (($row['active'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $statusText = (($row['active'] == 1) ? ("Published") : ("Draft")) ;
                          ?>
                          <tr>
                            <td><?php echo $row['id'] ? $row['id'] : "N/A"; ?></td>
                            <td><?php echo $row['package_id'] ? get_single_package($row['package_id'])['name'] : "N/A"; ?></td>
                            <td><?php echo $row['user_id'] ? get_user_det($row['user_id'])['uname'] : "N/A"; ?></td>
                            <td>$<?php echo $row['price'] ? number_format((float)$row['price'], 2, '.', '') : "0"; ?></td>
                            <td><?php echo $row['package_id'] ? count_products_by_package($row['package_id']) : "0"; ?></td>
                            <td><?= system_date_format($row['created_at']); ?></td>
                            <td>Active</td>
                            <td>
                            <div class="d-inline-block text-nowrap">
                              <a href="subscription_detail.php?id=<?=$row['id'];?>"><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i class="ti ti-eye ti-md"></i></button></a>
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