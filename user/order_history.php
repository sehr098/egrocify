<?php 
  include("inc/header.php"); 
  include("../function.php");

  $user_id = $_SESSION['loggedin_id'];
  $orders = get_all_orders('all', $user_id);

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
                        <th>ROI(%)</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                          $i = 1;
                          while($row=mysqli_fetch_assoc($orders)){
                            $statusLabel = (($row['active'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $statusText = (($row['active'] == 1) ? ("Published") : ("Draft")) ;

                            $paidLabel = (($row['paid_status'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                            $paidText = (($row['paid_status'] == 1) ? ("Paid") : ("un-paid")) ;

                          ?>
                          <tr>
                            <td><?php echo $row['id'] ? $row['id'] : "N/A"; ?></td>
                            <td><?php echo $row['user_id'] ? get_user_det($row['user_id'])['uname'] : "N/A"; ?></td>
                            <td><?php echo $row['product_id'] ? get_single_product($row['product_id'])['name'] : "0"; ?></td>
                            <td><?php echo $row['price'] ? number_format((float)$row['price']/$row['qty'], 2, '.', ''): "0.00"; ?></td>
                            <td><?php echo $row['qty'] ? number_format((float)$row['qty'], 2, '.', '') : "N/A"; ?></td>
                            <td><?=currency_sym()?><?php echo $row['price'] ? number_format((float)$row['price'], 2, '.', '') : "0"; ?></td>
                            <td><?php echo $row['product_id'] ? get_single_product($row['product_id'])['roi'].'%' : "0"; ?></td>
                            <td><?= system_date_format($row['created_at']); ?></td>
                            <td><span class="<?=$paidLabel;?>"><?=$paidText;?></span></td>
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