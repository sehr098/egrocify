<?php
  
  include("inc/header.php");
  include("../function.php");
  $user_id = $_SESSION['loggedin_id'];
  $withdraws = get_all_withdraws('all', $user_id);

  $withraw_disabled = ' ';
  $cursor_withdraw = ' pointer ';

  if(check_expiry() == true){
    $withraw_disabled = 'disabled=true';
    $cursor_withdraw = ' not-allowed ';
  }

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
              <div class="row">
                  <div class="offset-8 col-4 col-sm-4 col-lg-4 mb-4">
                  <div class="card" style="cursor: <?=$cursor_withdraw?>">
                    <div class="card-body text-center">
                      <h5>Make a Withdraw Request </h5>
                      <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addNewCCModal" <?=$withraw_disabled?> > Withdraw </button>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-12 mb-12">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="card-title">Withdraw List</h5>
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
                            <th>User name</th>
                            <th>Amount</th>
                            <th>Account type</th>
                            <th>Account no</th>
                            <th>Account Name</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <!-- <td>asdsad</td> -->
                            <!-- <td>asdsad</td> -->

                              <?php
                              while($row=mysqli_fetch_assoc($withdraws)){
                                $statusLabel = (($row['active'] == 1) ? ("bg-label-success") : ("bg-label-warning")) ;
                                $statusText = (($row['active'] == 1) ? ("Pending") : (($row['active'] == 2) ? ("Processing") : ("Paid"))) ;
                              ?>
                              <tr>
                                <td><?php echo $row['user_id'] ? get_user_det($row['user_id'])['uname'] : "N/A"; ?></td>
                                <td><?=currency_sym()?><?php echo $row['amount'] ? $row['amount'] : "N/A"; ?></td>
                                <td><?php echo $row['account_type'] ? (($row['account_type'] == 1) ? ("Bank") : ("Wallet Transfer")) : "N/A"; ?></td>
                                <td><?php echo $row['account_number'] ? $row['account_number'] : "N/A"; ?></td>
                                <td><?php echo $row['account_name'] ? $row['account_name'] : "N/A"; ?></td>
                                <td><span class="badge rounded-pill <?=$statusLabel?>"><?=$statusText?></span></td>
                                <td><?= system_date_format($row['request_time']); ?></td>
                                
                                <td>
                                <div class="d-inline-block text-nowrap">
                                  <!-- <a href="add_package.php?id=<?=$row['id'];?>"><button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light"><i class="ti ti-edit ti-md"></i></button></a>

                                  <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill waves-effect waves-light dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical ti-md"></i></button>
                                  
                                  <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                    <a href="../ajax-request.php?package-publish-request=1&package_id=<?=$row['id'];?>&approved=<?php echo (($row['active'] == 0) ? "1" : "0"); ?>" class="dropdown-item"><?php echo (($row['active'] == 0) ? "Active" : "Deactive"); ?></a>
                                    <a href="../ajax-request.php?package-delete-request=1&package_id=<?=$row['id'];?>" class="dropdown-item "><i class="ti ti-trash ti-md"></i></a> -->
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

              
              </div>
            </div>

            <!-- / Content -->
            <!-- / MODAL -->
              <div class="modal fade" id="addNewCCModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                  <div class="modal-content">
                    <div class="modal-body">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      <div class="text-center mb-6">
                        <h4 class="mb-2">Create a Withdraw Request</h4>
                        <p>Add Bank Details to proceed</p>
                      </div>
                      <form id="addWithdrawRequest" class="mb-3" action="../ajax-request.php" method="POST" >
                        <div class="col-12 mb-4">
                          <label class="form-label w-100" for="modalAddCard">Account Name</label>
                          <div class="input-group input-group-merge">
                            <input
                              id="acc_name"
                              name="acc_name"
                              class="form-control credit-card-mask"
                              type="text"
                              placeholder="Account Name"
                              aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"
                              ><span class="card-type"></span
                            ></span>
                          </div>
                        </div>
                        <div class="col-12 mb-4">
                          <label class="form-label w-100" for="modalAddCard">Account Type</label>
                          <div class="input-group input-group-merge">
                            <select id="acc_type" name="acc_type" class="select2 form-select" data-placeholder="Select Account Type" style="border-right: none;">
                              <option value="1" >Bank</option>
                              <option value="2" >Wallet Transfer</option>
                            </select>
                            <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"
                              ><span class="card-type"></span
                            ></span>
                          </div>
                        </div>
                        <div class="col-12 mb-4">
                          <label class="form-label w-100" for="modalAddCard">Bank Name</label>
                          <div class="input-group input-group-merge">
                            <input
                              id="bank_name"
                              name="bank_name"
                              class="form-control credit-card-mask"
                              type="text"
                              placeholder="Bank Name"
                              aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"
                              ><span class="card-type"></span
                            ></span>
                          </div>
                        </div>
                        <div class="col-12 mb-4">
                          <label class="form-label w-100" for="modalAddCard">Account Number</label>
                          <div class="input-group input-group-merge">
                            <input
                              id="acc_no"
                              name="acc_no"
                              class="form-control credit-card-mask"
                              type="text"
                              placeholder="1356 3215 6548 7898"
                              aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"
                              ><span class="card-type"></span
                            ></span>
                          </div>
                        </div>
                        <div class="col-12 mb-4">
                          <label class="form-label w-100" for="modalAddCard">Amount</label>
                          <div class="input-group input-group-merge">
                            <input
                              id="amount"
                              name="amount"
                              class="form-control credit-card-mask"
                              type="text"
                              placeholder="Amount"
                              aria-describedby="modalAddCard2" />
                            <span class="input-group-text cursor-pointer p-1" id="modalAddCard2"
                              ><span class="card-type"></span
                            ></span>
                          </div>
                        </div>
                       
                       
                        <div class="col-12 text-center">
                          <button type="submit" class="btn btn-primary me-3">Submit</button>
                          <a
                            type="reset"
                            id="reset_modal"
                            class="btn btn-label-secondary btn-reset"
                            data-bs-dismiss="modal"
                            aria-label="Close" href="javascript:void(0)">
                            Cancel
                          </a>
                        </div>
                        <input type="hidden" name="add-withdraw-request" id="add-withdraw-request" value="1">
                        <input type="hidden" name="user_id" id="user_id" value="<?=$user_id;?>">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            <!-- / MODAL -->
  
<?php include("inc/footer.php"); ?>
<script type="text/javascript">
  $(document).ready(function() {

    $("#addWithdrawRequest").on('submit', (function(e) {
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
                alert(data.msg);
                $('#addNewCCModal').hide();
                location.reload();
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