<?php
include('inc/header.php');
include('../function.php');


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
              <div class="row g-6">
                <!-- View sales -->
                <div class="col-xl-4">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-7">
                        <div class="card-body text-nowrap">
                          <h5 class="card-title mb-0">Congratulations <?=$_SESSION['loggedin_name']?>! 🎉</h5>
                          <p class="mb-2">Current Balance</p>
                          <h4 class="text-primary mb-1"><?=display_amount(get_current_user_det()['balance'] - total_user_withdraw());?></h4>
                          <a href="order_history.php" class="btn btn-primary">View Sales</a>
                        </div>
                      </div>
                      <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="assets/img/illustrations/card-advance-sale.png"
                            height="140"
                            alt="view sales" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- View sales -->

                <!-- Statistics -->
                <div class="col-xl-8 col-md-12">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                      <h5 class="card-title mb-0">Statistics</h5>
                      <small class="text-muted">Updated 1 month ago</small>
                    </div>
                    <div class="card-body d-flex align-items-end">
                      <div class="w-100">
                        <div class="row gy-3">
                          <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                              <div class="badge rounded bg-label-primary me-4 p-2">
                                <i class="ti ti-chart-pie-2 ti-lg"></i>
                              </div>
                              <div class="card-info">
                                <h5 class="mb-0"><?= display_amount(total_user_sale())?></h5>
                                <small>Sales</small>
                              </div>
                            </div>
                          </div>

                          <!-- <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                              <div class="badge rounded bg-label-info me-4 p-2"><i class="ti ti-users ti-lg"></i></div>
                              <div class="card-info">
                                <h5 class="mb-0">8.549k</h5>
                                <small>Customers</small>
                              </div>
                            </div>
                          </div> -->
                          <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                              <div class="badge rounded bg-label-danger me-4 p-2">
                                <i class="ti ti-shopping-cart ti-lg"></i>
                              </div>
                              <div class="card-info">
                                <h5 class="mb-0"><?= display_amount(total_user_withdraw())?></h5>
                                <small>Withdraw</small>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                              <div class="badge rounded bg-label-success me-4 p-2">
                                <i class="ti ti-currency-dollar ti-lg"></i>
                              </div>
                              <div class="card-info">
                                <h5 class="mb-0"><?= display_amount(total_user_sale()*0.10)?></h5>
                                <small>Profit</small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Statistics -->

                <div class="col-xxl-4 col-12">
                  <div class="row g-6">
                    <!-- Profit last month -->
                    <div class="col-xl-12 col-sm-12">
                      <div class="card h-100">
                        <div class="card-header pb-0">
                          <h5 class="card-title mb-1">Profit</h5>
                          <p class="card-subtitle">Last 10 Months</p>
                        </div>
                        <div class="card-body">
                          <div id="profitLastMonth"></div>
                          <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                            <h4 class="mb-0"><?= display_amount(total_user_sale()*0.10)?></h4>
                            <small class="text-success">+5%</small>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--/ Profit last month -->

                    <!-- Expenses -->
                    <div class="col-xl-6 col-sm-6 d-none">
                      <div class="card h-100">
                        <div class="card-header pb-2">
                          <h5 class="card-title mb-1">0</h5>
                          <p class="card-subtitle">Expenses</p>
                        </div>
                        <div class="card-body">
                          <div id="expensesChart"></div>
                          <div class="mt-3 text-center">
                            <small class="text-muted mt-3"><?=currency_sym();?>0 Expenses more than last month</small>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--/ Expenses -->
                    <?php
                        $reffered_users = get_all_reffered_users($_SESSION['loggedin_id']);
                        $total_users_referred = 0;
                          if($reffered_users->num_rows > 0){
                            $total_users_referred = $reffered_users->num_rows;
                          }

                    ?>

                    <!-- Generated Leads -->
                    <div class="col-xl-12">
                        <div class="card">
                          <div class="d-flex align-items-end row">
                            <div class="col-7">
                              <div class="card-body text-nowrap">
                                <h5 class="card-title mb-0">Your Refferal Accounts! 🎉</h5>
                                <p class="mb-2">Total</p>
                                <h4 class="text-primary mb-1"><?=$total_users_referred;?></h4>
                                <a href="referrals.php" class="btn btn-primary">View Refferal Page</a>
                              </div>
                            </div>
                            <div class="col-5 text-center text-sm-left">
                              <div class="card-body pb-0 px-0 px-md-4">
                                <img
                                  src="assets/img/illustrations/card-advance-sale.png"
                                  height="140"
                                  alt="view sales" />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    <!--/ Generated Leads -->
                  </div>
                </div>

                <!-- Revenue Report -->
                <div class="col-xxl-8">
                  <div class="card h-100">
                    <div class="card-body p-0">
                      <div class="row row-bordered g-0">
                        <div class="col-md-8 position-relative p-6">
                          <div class="card-header d-inline-block p-0 text-wrap position-absolute">
                            <h5 class="m-0 card-title">Revenue Report</h5>
                          </div>
                          <div id="totalRevenueChart" class="mt-n1"></div>
                        </div>
                        <div class="col-md-4 p-4">
                          <div class="text-center mt-5">
                            <div class="dropdown">
                              <button
                                class="btn btn-sm btn-label-primary dropdown-toggle"
                                type="button"
                                id="budgetId"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <script>
                                  document.write(new Date().getFullYear());
                                </script>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="budgetId">
                                <a class="dropdown-item prev-year1" href="javascript:void(0);">
                                  <script>
                                    document.write(new Date().getFullYear() - 1);
                                  </script>
                                </a>
                                <a class="dropdown-item prev-year2" href="javascript:void(0);">
                                  <script>
                                    document.write(new Date().getFullYear() - 2);
                                  </script>
                                </a>
                                <a class="dropdown-item prev-year3" href="javascript:void(0);">
                                  <script>
                                    document.write(new Date().getFullYear() - 3);
                                  </script>
                                </a>
                              </div>
                            </div>
                          </div>
                          <?php
                          $profit = total_user_sale()*0.10;
                          ?>
                          <h3 class="text-center pt-8 mb-0"><?= display_amount(total_user_sale()+$profit)?></h3>
                          <p class="mb-8 text-center"><span class="fw-medium text-heading">Budget: </span><?=display_amount(get_current_user_det()['balance'] - total_user_withdraw());?></p>
                          <div class="px-3">
                            <div id="budgetChart"></div>
                          </div>
                          <div class="text-center mt-8 d-none">
                            <button type="button" class="btn btn-primary">Increase Button</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Transactions -->
                <div class="col-xxl-12 col-md-12 d-none">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title m-0 me-2">
                        <h5 class="mb-1">Transactions</h5>
                        <p class="card-subtitle">Total 0 Transactions done in this Month</p>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1"
                          type="button"
                          id="transactionID"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-md text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                          <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <!-- <li class="d-flex mb-3 pb-1 align-items-center">
                          <div class="badge bg-label-primary me-4 rounded p-1_5">
                            <i class="ti ti-wallet ti-md"></i>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Wallet</h6>
                              <small class="text-body d-block">Starbucks</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0 text-danger">-$75</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                          <div class="badge bg-label-success me-4 rounded p-1_5">
                            <i class="ti ti-browser-check ti-md"></i>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Bank Transfer</h6>
                              <small class="text-body d-block">Add Money</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0 text-success">+$480</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                          <div class="badge bg-label-danger me-4 rounded p-1_5">
                            <i class="ti ti-brand-paypal ti-md"></i>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Paypal</h6>
                              <small class="text-body d-block">Client Payment</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0 text-success">+$268</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                          <div class="badge bg-label-secondary me-4 rounded p-1_5">
                            <i class="ti ti-credit-card ti-md"></i>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Master Card</h6>
                              <small class="text-body d-block">Ordered iPhone 13</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0 text-danger">-$699</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                          <div class="badge bg-label-info me-4 rounded p-1_5">
                            <i class="ti ti-currency-dollar ti-md"></i>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Bank Transactions</h6>
                              <small class="text-body d-block">Refund</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0 text-success">+$98</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                          <div class="badge bg-label-danger me-4 rounded p-1_5">
                            <i class="ti ti-brand-paypal ti-md"></i>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Paypal</h6>
                              <small class="text-body d-block">Client Payment</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0 text-success">+$126</h6>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex align-items-center">
                          <div class="badge bg-label-success me-4 rounded p-1_5">
                            <i class="ti ti-building-bank ti-md"></i>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Bank Transfer</h6>
                              <small class="text-body d-block">Pay Office Rent</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0 text-danger">-$1290</h6>
                            </div>
                          </div>
                        </li> -->
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Transactions -->
               
              </div>
            </div>
            <!-- / Content -->

          <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                 <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="text-body">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , made with ❤️ by <a href="https://seller.egrocify.com/" target="_blank" class="footer-link">Egrocify LLC</a>
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="https://seller.egrocify.com/" class="footer-link me-4" target="_blank"
                      >License</a
                    >
                    
                </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/app-ecommerce-dashboard.js"></script>
  </body>
</html>
