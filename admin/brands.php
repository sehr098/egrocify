  <?php
  include("inc/header.php"); 
  include("../function.php");
  $brands = get_all_brands('all');

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
              <!-- <div class="card mb-6">
                <div class="card-widget-separator-wrapper">
                  <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                      <div class="col-sm-6 col-lg-3">
                        <div
                          class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                          <div>
                            <p class="mb-1">In-store Sales</p>
                            <h4 class="mb-1">$5,345.43</h4>
                            <p class="mb-0">
                              <span class="me-2">5k orders</span><span class="badge bg-label-success">+5.7%</span>
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
                            <p class="mb-1">Website Sales</p>
                            <h4 class="mb-1">$674,347.12</h4>
                            <p class="mb-0">
                              <span class="me-2">21k orders</span><span class="badge bg-label-success">+12.4%</span>
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
                            <p class="mb-1">Discount</p>
                            <h4 class="mb-1">$14,235.12</h4>
                            <p class="mb-0">6k orders</p>
                          </div>
                          <span class="avatar p-2 me-sm-6">
                            <span class="avatar-initial rounded"><i class="ti-28px ti ti-gift text-heading"></i></span>
                          </span>
                        </div>
                      </div>
                      <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <p class="mb-1">Affiliate</p>
                            <h4 class="mb-1">$8,345.23</h4>
                            <p class="mb-0">
                              <span class="me-2">150 orders</span><span class="badge bg-label-danger">-3.5%</span>
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
              </div> -->

              <!-- Product List Table -->
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Brand List</h5>
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
                        <th>ID</th>
                        <th>BRAND</th>
                        <th>Company</th>
                        <th>Total Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      
                        <?php
                        while($row=mysqli_fetch_assoc($brands)){
                          $statusLabel = (($row['active'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                          $statusText = (($row['active'] == 1) ? ("Published") : ("Draft")) ;
                          
                          $encoded_name = mb_convert_encoding($row['name'], 'UTF-8', 'auto');
                        ?>
                          <tr>
                            <td><?php echo $row['id'] ? $row['id'] : "-"; ?></td>
                            <td><?php echo $row['name'] ? html_entity_decode($row['name']) : "-"; ?></td>
                            <td><?php echo $row['company_id'] ? $row['company_id'] : "-"; ?></td>
                            <td><a href="products.php?brand=<?=$row['id']?>" target="_blank"><?php echo $row['id'] ? get_total_prod_in_brand($row['id']) : "-"; ?></a></td>
                            <td><?=$statusText?></td>
                            <td>
                            <div class="d-inline-block text-nowrap">
                              <a href="add_brand.php?id=<?=$row['id'];?>"><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i class="ti ti-edit ti-md"></i></button></a>

                              <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></button>
                              
                              <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                <a href="../ajax-request.php?brand-publish-request=1&brand_id=<?=$row['id'];?>&approved=<?php echo (($row['active'] == 0) ? "1" : "0"); ?>" class="dropdown-item"><?php echo (($row['active'] == 0) ? "Active" : "Deactive"); ?></a>
                                <a href="../ajax-request.php?brand-delete-request=1&brand_id=<?=$row['id'];?>" class="dropdown-item "><i class="ti ti-trash ti-md"></i></a>
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