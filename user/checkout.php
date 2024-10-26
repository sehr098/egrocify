<?php 
  include("inc/header.php"); 
  include("../function.php");

  $product = get_single_product($_POST['prod_id']);
  $payment_mode = $_POST['payment_mode'];
  $user_id = 0;
  if(isset($_SESSION['loggedin_id'])){
    $user_id = $_SESSION['loggedin_id'];
  }
  // var_dump($product);
  $order_id = get_last_order_id();

  $qty = 1;
  if(isset($_POST['qty']))
  {
    $qty = $_POST['qty']; 
  }
  $product_image = '../'.$product['location'].$product['featured_image'];
  // var_dump($product_image);
  // exit;
  

  ?>
  <?php
        // FROM MERCHANT SANDBOX
        // $url = "https://sandbox.bankalfalah.com/HS/HS/HS";
        // $Key1= "fK5VQPASB26bxAYK";
        // $Key2= "3241655911003835";
        // $HS_MerchantHash="OUU362MB1up4QHX20s7ukDprewwg6w+OvDKuHvm39rvD6Q0lksOzS3VdfX70rR3Nbxc5OMqhewg=";
        // $HS_MerchantUsername="taqege" ;
        // $HS_MerchantPassword="b6mvARjRmjdvFzk4yqF7CA==";
        
        // FROM MERCHANT PRODUCTION
        $url = "https://payments.bankalfalah.com/HS/HS/HS";
        $Key1= "KDTwxdzjfNGZ2uQu";
        $Key2= "7915900251639620";
        $HS_MerchantHash="s8WY8hBaL9g6a3sMIOsPjn6JgzejGRSOKHHZwBkiY/s=";
        $HS_MerchantUsername="pyvady" ;
        $HS_MerchantPassword="xgaPBgXs06VvFzk4yqF7CA==";

        $bankorderId   = $order_id.'_'.rand(0,1786612);
        $HS_ChannelId="1001";
        $HS_MerchantId="16327";
        $HS_StoreId="021865";
        $HS_IsRedirectionRequest  = 0;
        $HS_ReturnURL="https://seller.egrocify.com/return-url-order.php";
        $HS_TransactionReferenceNumber= $bankorderId;
        $transactionTypeId = $payment_mode;
        $TransactionAmount = ($product['price']*278.50)*$qty;
        $cipher="aes-128-cbc";
      
        $mapString = 
          "HS_ChannelId=$HS_ChannelId" 
        . "&HS_IsRedirectionRequest=$HS_IsRedirectionRequest" 
        . "&HS_MerchantId=$HS_MerchantId" 
        . "&HS_StoreId=$HS_StoreId" 
        . "&HS_ReturnURL=$HS_ReturnURL"
        . "&HS_MerchantHash=$HS_MerchantHash"
        . "&HS_MerchantUsername=$HS_MerchantUsername"
        . "&HS_MerchantPassword=$HS_MerchantPassword"
        . "&HS_TransactionReferenceNumber=$HS_TransactionReferenceNumber";
        
        $cipher_text = openssl_encrypt($mapString, $cipher, $Key1,   OPENSSL_RAW_DATA , $Key2);
        $hashRequest = base64_encode($cipher_text);

        $fields = [
            "HS_ChannelId"=>$HS_ChannelId,
            "HS_IsRedirectionRequest"=>$HS_IsRedirectionRequest,
            "HS_MerchantId"=> $HS_MerchantId,
            "HS_StoreId"=> $HS_StoreId,
            "HS_ReturnURL"=> $HS_ReturnURL,
            "HS_MerchantHash"=> $HS_MerchantHash,
            "HS_MerchantUsername"=> $HS_MerchantUsername,
            "HS_MerchantPassword"=> $HS_MerchantPassword,
            "HS_TransactionReferenceNumber"=> $HS_TransactionReferenceNumber,
            "HS_RequestHash"=> $hashRequest
        ];
        
        $fields_string = http_build_query($fields);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        $result = curl_exec($ch);
        $handshake =  json_decode($result);
        $AuthToken = $handshake->AuthToken;
  
  
$RequestHash1 = NULL;
$Currency = "PKR";
$IsBIN =0;

$mapStringSSo = 
  "AuthToken=$AuthToken" 
. "&RequestHash=$RequestHash1" 
. "&ChannelId=$HS_ChannelId"
. "&Currency=$Currency"
. "&IsBIN=$IsBIN"
. "&ReturnURL=$HS_ReturnURL"
. "&MerchantId=$HS_MerchantId"
. "&StoreId=$HS_StoreId" 
. "&MerchantHash=$HS_MerchantHash"
. "&MerchantUsername=$HS_MerchantUsername"
. "&MerchantPassword=$HS_MerchantPassword"
. "&TransactionTypeId=$transactionTypeId"
. "&TransactionReferenceNumber=$HS_TransactionReferenceNumber"
. "&TransactionAmount=$TransactionAmount";
     
$cipher_text = openssl_encrypt($mapStringSSo, $cipher, $Key1,   OPENSSL_RAW_DATA , $Key2);
$hashRequest1 = base64_encode($cipher_text);
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

          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                  <div class="mb-1">
                    <span class="h5">Order #<?php echo $order_id ? $order_id : "N/A"; ?> </span>
                    <!-- <span class="badge bg-label-warning me-1 ms-2">Un-Paid</span> -->
                    <!-- <span class="h5">Order #<?php echo $order_id ? $order_id : "N/A"; ?> </span><span class="badge bg-label-success me-1 ms-2">Paid</span> -->
                    <!-- <span class="badge bg-label-info"><?=$statusText?></span> -->
                  </div>
                  <!-- <p class="mb-0"><?= system_date_format($order_data['created_at']); ?></p> -->
                </div>
                <div class="d-flex align-content-center flex-wrap gap-2 d-none">
                  <button class="btn btn-label-danger delete-order">Delete Order</button>
                </div>
              </div>

              <!-- Order Details Table -->

              <div class="row">
                <div class="col-12 col-lg-6">
                    

                  <!-- CUSTOM DESIGN -->
                  <div class="card mb-6 ">
                      <div class="card-header">
                        <h5 class="card-title m-0">Detail</h5>
                      </div>
                      <div class="card-body pt-1">
                        <ul class="timeline pb-0 mb-0">
                          <li class=" timeline-item-transparent ">
                            <div class="timeline-event">

                              <div class="row">
                                <div class="col-md-6">
                                  <img src="<?=$product_image?>" alt="product_image" style="height: auto; width:100%; " >
                                </div>
                                <div class="col-md-6">
                                  <h5><?= $product['name'] ? $product['name'] : "Product"; ?></h5>
                                  <small class="text-muted">Sold by:</small>
                                  <p><?= $product['brand'] ? get_single_brand($product['brand'])['name'] : "Brand"; ?></p>
                                  <small class="text-muted">Quanitity:</small>
                                  <p><?php echo $qty ? number_format((float)$qty, 2, '.', '') : "0"; ?></p>
                                  <small class="text-muted">Amount:</small>
                                  <p><?=currency_sym()?><?php echo $product['price'] ? number_format((float)$product['price'], 2, '.', '') : "0"; ?></p>
                                  <small class="text-muted">SubTotal:</small>
                                  <p><?=currency_sym()?><?php echo $product['price']*$qty ? number_format((float)$product['price']*$qty, 2, '.', '') : "0"; ?></p>
                                </div>
                              </div>
                              
                            </div>
                          </li>
                        </ul>
                      </div>

                    
                    </div>
                    <!-- CUSTOM DESIGN -->


                </div>

                <div class="col-12 col-lg-6">


                  <!-- <div class="card mb-6">
                    <div class="card-header">
                      <h5 class="card-title m-0">Payment details</h5>
                    </div>
                    <div class="card-body">

                      <div class="row ">
                        <div class="col-md-6" style="margin: auto;">
                          <p><strong>Amount to pay</strong></p>
                        </div>
                        <div class="col-md-6" style="margin: auto;">
                          <h4><?=currency_sym()?><?php echo $product['price']*$qty ? number_format((float)$product['price']*$qty, 2, '.', '') : "0"; ?></h4>
                        </div>
                      </div>
                      <div class="row ">
                        
                        <div class="col-md-12 hide_by_position">
                          <form action="https://payments.bankalfalah.com/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">                                                              
                            <input id="AuthToken" name="AuthToken" type="hidden" value="<?php echo $AuthToken; ?>">
                            <input id="RequestHash" name="RequestHash" type="hidden" value="<?php echo $hashRequest1; ?>">
                            <input id="ChannelId" name="ChannelId" type="hidden" value="<?php echo $HS_ChannelId; ?>">                              
                            <input id="Currency" name="Currency" type="hidden" value="PKR">                                                                                                                               
                            <input id="IsBIN" name="IsBIN" type="hidden" value="0">                                                                                     
                            <input id="ReturnURL" name="ReturnURL" type="hidden" value="<?php echo $HS_ReturnURL; ?>">                                                                            
                            <input id="MerchantId" name="MerchantId" type="hidden" value="<?php echo $HS_MerchantId;?>">
                            <input id="StoreId" name="StoreId" type="hidden" value="<?php echo $HS_StoreId;?>">                         
                            <input id="MerchantHash" name="MerchantHash" type="hidden" value="<?php echo $HS_MerchantHash;?>">                                  
                            <input id="MerchantUsername" name="MerchantUsername" type="hidden" value="<?php echo $HS_MerchantUsername;?>">
                            <input id="MerchantPassword" name="MerchantPassword" type="hidden" value="<?php echo $HS_MerchantPassword;?>">  
                            <input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="<?php echo $transactionTypeId;?>"> 
                            <input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="hidden" value="<?php echo $order_id;?>">
                            <input autocomplete="off"  id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="hidden" value="<?php echo $TransactionAmount; ?>">  
                          <button type="submit" class="btn btn-primary mt-4">Pay Now</button>
                        </form> 
                        </div>
                      </div>

                    </div>
                  </div> -->


                  <div class="card mb-6">
                    <div class="card-header">
                      <h5 class="card-title m-0">Payment details</h5>
                    </div>
                    <div class="card-body">

                      <div class="row ">
                        <div class="col-md-6" style="margin: auto;">
                          <p><strong>Amount to pay</strong></p>
                        </div>
                        <div class="col-md-6" style="margin: auto;">
                          <h4><?=currency_sym()?><?php echo $product['price']*$qty ? number_format((float)$product['price']*$qty, 2, '.', '') : "0"; ?></h4>
                        </div>
                      </div>
                      <div class="row ">
                        
                        <div class="col-md-12 hide_by_position">
                          <!--<form action="https://sandbox.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">-->
                           <form action="https://payments.bankalfalah.com/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate"> 

                            <input id="AuthToken" name="AuthToken" type="hidden" value="<?php echo $AuthToken; ?>">
                            <input id="RequestHash" name="RequestHash" type="hidden" value="<?php echo $hashRequest1; ?>">
                            <input id="ChannelId" name="ChannelId" type="hidden" value="<?php echo $HS_ChannelId; ?>">                              
                            <input id="Currency" name="Currency" type="hidden" value="PKR">                                                                                                                               
                            <input id="IsBIN" name="IsBIN" type="hidden" value="0">                                                                                     
                            <input id="ReturnURL" name="ReturnURL" type="hidden" value="<?php echo $HS_ReturnURL; ?>">                                                                            
                            <input id="MerchantId" name="MerchantId" type="hidden" value="<?php echo $HS_MerchantId;?>">
                            <input id="StoreId" name="StoreId" type="hidden" value="<?php echo $HS_StoreId;?>">                         
                            <input id="MerchantHash" name="MerchantHash" type="hidden" value="<?php echo $HS_MerchantHash;?>">                                  
                            <input id="MerchantUsername" name="MerchantUsername" type="hidden" value="<?php echo $HS_MerchantUsername;?>">
                            <input id="MerchantPassword" name="MerchantPassword" type="hidden" value="<?php echo $HS_MerchantPassword;?>">  
                            <input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="<?php echo $transactionTypeId;?>"> 
                            <input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="hidden" value="<?php echo $HS_TransactionReferenceNumber;?>">
                            <input autocomplete="off"  id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="hidden" value="<?php echo $TransactionAmount; ?>">  
                            <!-- <center><button type="submit" class="btn btn-custon-four btn-danger" id="run">PAY ONLINE</button></center>                                                                                                     -->
                          <button type="submit" class="btn btn-primary mt-4">Pay Now</button>
                        </form> 
                        </div>
                      </div>



                    <form id="createOrdersForm" class="mb-3" action="../ajax-request.php" method="POST" >

                      <!-- <h4 class="mb-6">Billing Details</h4> -->
                    <!--     <div class="row g-6">
                          <div class="col-md-6">
                            <label class="form-label" for="billings_name">Full Name</label>
                            <input type="text" id="billings_name" name="billings_name" class="form-control" placeholder="Full Name" />
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="billings-email">Email Address</label>
                            <input type="text" id="billings_email" name="billings_email" class="form-control" placeholder="john.doe@gmail.com" />
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="billings_country">Country</label>
                            <select id="billings_country" name="billings_country" class="form-select" data-allow-clear="true">
                                  <option value="0" >Select Country</option>
                                   <?php
                                     $countryList = countryList();
                                     for($c=0; $c<count($countryList); $c++)
                                     {
                                        // var_dump($countryList[$c]);?>
                                        <option value="<?=$countryList[$c];?>" ><?=$countryList[$c];?></option>

                                     <?php
                                     }
                                  ?>
                            </select>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="billings-zip">Billing Zip / Postal Code</label>
                            <input
                              type="text"
                              id="billings_zip"
                              name="billings_zip"
                              class="form-control billings-zip-code"
                              placeholder="Zip / Postal Code" />
                          </div>
                        </div> -->

                      <input type="hidden" name="billings_name" value="<?=$_SESSION['loggedin_name']?>">
                      <input type="hidden" name="billings_email" value="<?=$_SESSION['loggedin_email']?>">
                      <input type="hidden" name="billings_country" value="PK">
                      <input type="hidden" name="billings_zip" value="0000">




                        <div class="row g-5 py-8">

                          <div class="col-md col-lg-12 col-xl-6 dsa">
                            <div class="form-check custom-option custom-option-basic <?= $payment_mode == 3 ? 'checked' : ''; ?> ">
                              <label
                                class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center"
                                for="customRadioBankAccount">
                                <input
                                  name="customRadioTemp"
                                  class="form-check-input"
                                  type="radio"
                                  value="3"
                                  id="customRadioBankAccount"
                                  <?= $payment_mode == 3 ? 'checked' : ''; ?> />
                                <span class="custom-option-body">
                                  <img
                                    src="assets/img/icons/payments/visa-light.png"
                                    alt="visa-card"
                                    width="58"
                                    data-app-light-img="icons/payments/visa-light.png"
                                    data-app-dark-img="icons/payments/visa-dark.png" />
                                  <span class="ms-4 fw-medium text-heading">Credit/Debit Card </span>
                                </span>
                              </label>
                            </div>
                          </div>
                          <div class="col-md col-lg-12 col-xl-6 dsa">
                            <div class="form-check custom-option custom-option-basic <?= $payment_mode == 1 ? 'checked' : ''; ?> ">
                              <label
                                class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center"
                                for="customRadioWallet">
                                <input
                                  name="customRadioTemp"
                                  class="form-check-input"
                                  type="radio"
                                  value="1"
                                  id="customRadioWallet"
                                  <?= $payment_mode == 1 ? 'checked' : ''; ?>
                                   />
                                <span class="custom-option-body">
                                  <img
                                    src="assets/img/icons/payments/visa-light.png"
                                    alt="visa-card"
                                    width="58"
                                    data-app-light-img="icons/payments/visa-light.png"
                                    data-app-dark-img="icons/payments/visa-dark.png" />
                                  <span class="ms-4 fw-medium text-heading">Alfa Wallet</span>
                                </span>
                              </label>
                            </div>
                          </div>
                          <div class="col-md col-lg-12 col-xl-12 dsa">
                            <div class="form-check custom-option custom-option-basic <?= $payment_mode == 2 ? 'checked' : ''; ?> ">
                              <label
                                class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center"
                                for="customRadioBankAccounts">
                                <input
                                  name="customRadioTemp"
                                  class="form-check-input"
                                  type="radio"
                                  value="2"
                                  id="customRadioBankAccounts"
                                  <?= $payment_mode == 2 ? 'checked' : ''; ?>
                                   />
                                <span class="custom-option-body">
                                  <img
                                    src="assets/img/icons/payments/visa-light.png"
                                    alt="visa-card"
                                    width="58"
                                    data-app-light-img="icons/payments/visa-light.png"
                                    data-app-dark-img="icons/payments/visa-dark.png" />
                                  <span class="ms-4 fw-medium text-heading">Alfalah Bank Accounts</span>
                                </span>
                              </label>
                            </div>
                          </div>

                          
                          
                          

                            <div class="d-grid mt-5">
                              <button class="btn btn-success waves-effect waves-light">
                              <span class="me-2">Checkout </span>
                                <i class="ti ti-arrow-right scaleX-n1-rtl"></i>
                              </button>
                            </div>
                          
                        </div>

                      <input type="hidden" name="user_id" value="<?=$user_id?>">
                      <input type="hidden" name="package_id" value="<?=$product['package']?>">
                      <input type="hidden" name="price" value="<?=$product['price'] * $qty?>">
                      <input type="hidden" name="qty" value="<?=$qty?>">
                      <input type="hidden" name="product_id" value="<?=$product['id']?>">
                      <input type="hidden" name="type" value="product_order">
                      <input type="hidden" name="add-order-request" value="1">
                      </form>

                      </div>

                  </div>

                  <!-- CARD END -->

                  <div class="card mb-6 d-none">
                    <div class="card-header d-flex justify-content-between">
                      <h5 class="card-title m-0">Shipping address</h5>
                      <h6 class="m-0">
                        <a href=" javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">Edit</a>
                      </h6>
                    </div>
                    <div class="card-body">
                      <p class="mb-0">45 Roker Terrace <br />Latheronwheel <br />KW5 8NW,London <br />UK</p>
                    </div>
                  </div>
                  <div class="card mb-6 d-none">
                    <div class="card-header d-flex justify-content-between">
                      <h5 class="card-title m-0">Billing address</h5>
                      <h6 class="m-0">
                        <a href=" javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addNewAddress">Edit</a>
                      </h6>
                    </div>
                    <div class="card-body">
                      <p class="mb-6">45 Roker Terrace <br />Latheronwheel <br />KW5 8NW,London <br />UK</p>
                      <h5 class="mb-1">Mastercard</h5>
                      <p class="mb-0">Card Number: ******4291</p>
                    </div>
                  </div>
                </div>
              </div>


            </div>
            <!-- / Content -->

  
  <?php include("inc/footer.php"); ?>

  <script type="text/javascript">
    $(document).ready(function() {

      $("#createOrdersForm").on('submit', (function(e) {
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
                  // alert(data.msg);
                  // location.reload();
                  alert("Order Sumitted Successfully");
                  document.getElementById('PageRedirectionForm').submit();

                  // window.location.href = "/thankyou.php?ord_id="+data.ord_id+"&type=product_order";
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

      $("input[name='customRadioTemp']").click(function(){
        console.log("radio clicked");
        console.log(this.value);
        var payment_mode =this.value;
        var prod_id = "<?=$_POST['prod_id']?>";
        var qty = "<?=$_POST['qty']?>";

        var url = 'checkout.php';
        var form = $('<form action="' + url + '" method="POST">' +
        '<input type="hidden" name="prod_id" value="'+prod_id+'" />' +
        '<input type="hidden" name="qty" value="'+qty+'" />' +
        '<input type="hidden" name="payment_mode" value="'+payment_mode+'" />' +
        '</form>');
        $('body').append(form);
        form.submit();
    // window.location.href = "/checkout.php?type="+payment_mode;

  });
  </script>