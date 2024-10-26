<?php 
include("inc/header.php"); 
include("function.php"); 

$order_id = 0;
$package_id = 0;
$month_limit = 1;
if(isset($_GET['ord_id'])){
  $order_id = $_GET['ord_id'];
  $package_id = get_single_subscription($_GET['ord_id'])['package_id'];
  $month_limit = get_single_subscription($_GET['ord_id'])['month_limit'];
}

$package = get_single_package($package_id);
$user_id = 0;

$payment_mode = 3;
if(isset($_GET['payment_mode'])){
  $payment_mode = $_GET['payment_mode'];
}


if(isset($_GET['user_id'])){
  $get_user_det = get_user_det($_GET['user_id']);
  $user_id = $_GET['user_id'];
  $user_name = $get_user_det['uname'];
  $user_email = $get_user_det['email'];
}


// $order_id = get_last_order_id();
if(isset($_GET['month_limit'])){
  $month_limit = $_GET['month_limit'];
}



?>

<!-- ALFALAH SETTING -->
<?php


      // FROM MERCHANT SANDBOX
        $url = "https://sandbox.bankalfalah.com/HS/HS/HS";
        $Key1= "fK5VQPASB26bxAYK";
        $Key2= "3241655911003835";
        $HS_MerchantHash="OUU362MB1up4QHX20s7ukDprewwg6w+OvDKuHvm39rvD6Q0lksOzS3VdfX70rR3Nbxc5OMqhewg=";
        $HS_MerchantUsername="taqege" ;
        $HS_MerchantPassword="b6mvARjRmjdvFzk4yqF7CA==";
        
        // FROM MERCHANT PRODUCTION
        // $url = "https://payments.bankalfalah.com/HS/HS/HS";
        // $Key1= "KDTwxdzjfNGZ2uQu";
        // $Key2= "7915900251639620";
        // $HS_MerchantHash="s8WY8hBaL9g6a3sMIOsPjn6JgzejGRSOKHHZwBkiY/s=";
        // $HS_MerchantUsername="pyvady" ;
        // $HS_MerchantPassword="xgaPBgXs06VvFzk4yqF7CA==";


    // $url = "https://sandbox.bankalfalah.com/HS/HS/HS";
         // $bankorderId   = rand(0,1786612);
        $bankorderId   = $order_id.'_'.rand(0,1786612);
        $HS_ChannelId="1001";
        $HS_MerchantId="16327";
        $HS_StoreId="021865";
        $HS_IsRedirectionRequest  = 0;
        $HS_ReturnURL="https://seller.egrocify.com/return-url.php";
        $HS_TransactionReferenceNumber= $bankorderId;
        $transactionTypeId = $payment_mode;
      $TransactionAmount = $package['price']*278.50*$month_limit;
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
<!-- ALFALAH SETTING -->


  <body>
    <script src="assets/vendor/js/dropdown-hover.js"></script>
    <script src="assets/vendor/js/mega-dropdown.js"></script>

    <!-- Navbar: Start -->
    <?php include("inc/topbar.php"); ?>

    <!-- Navbar: End -->

    <!-- Sections:Start -->

    <section class="section-py bg-body first-section-pt">
      <div class="container">
        <div class="card px-3">

          <form id="updateSubscriptionForm" class="mb-3" action="../ajax-request.php" method="POST" >
            <div class="row">
              <div class="col-lg-7 card-body border-end p-md-8">
                <h4 class="mb-2">Checkout</h4>
                <p class="mb-0">
                  All plans include 40+ advanced tools and features to boost your product. <br />
                  Choose the best plan to fit your needs.
                </p>
                <div class="row g-5 py-8">
               <!--    <div class="col-md col-lg-12 col-xl-6">
                    <div class="form-check custom-option custom-option-basic checked">
                      <label
                        class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center"
                        for="customRadioVisa">
                        <input
                          name="customRadioTemp"
                          class="form-check-input"
                          type="radio"
                          value="credit-card"
                          id="customRadioVisa"
                          checked />
                        <span class="custom-option-body">
                          <img
                            src="assets/img/icons/payments/visa-light.png"
                            alt="visa-card"
                            width="58"
                            data-app-light-img="icons/payments/visa-light.png"
                            data-app-dark-img="icons/payments/visa-dark.png" />
                          <span class="ms-4 fw-medium text-heading">Credit Card</span>
                        </span>
                      </label>
                    </div>
                  </div>
                  <div class="col-md col-lg-12 col-xl-6">
                    <div class="form-check custom-option custom-option-basic">
                      <label
                        class="form-check-label custom-option-content form-check-input-payment d-flex gap-4 align-items-center"
                        for="customRadioPaypal">
                        <input
                          name="customRadioTemp"
                          class="form-check-input"
                          type="radio"
                          value="paypal"
                          id="customRadioPaypal" />
                        <span class="custom-option-body">
                          <img
                            src="assets/img/icons/payments/paypal-light.png"
                            alt="paypal"
                            width="58"
                            data-app-light-img="icons/payments/paypal-light.png"
                            data-app-dark-img="icons/payments/paypal-dark.png" />
                          <span class="ms-4 fw-medium text-heading">Paypal</span>
                        </span>
                      </label>
                    </div>
                  </div> -->

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

                          <div class="col-md col-lg-12 col-xl-12 dsa">
                            <label class="form-label" for="month_limit">Months Plan</label>
                            <select id="month_limit" name="month_limit" class="form-select" data-allow-clear="true">
                              <?php
                              $monthsSubsList = monthsSubsList();
                              for($c=1; $c<=count($monthsSubsList); $c++){
                                $selected_month = " ";
                                if($c == $month_limit)
                                  $selected_month = " selected ";
                               ?>

                                <option value="<?=$c;?>"  <?=$selected_month;?> ><?=$monthsSubsList[$c];?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <input type="hidden" name="pm" id="pm" value="<?=$payment_mode?>">
                      </div>
                <!-- <h4 class="mb-6">Billing Details</h4> -->
                <!--   <div class="row g-6">
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
                                ;?>
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

                      <input type="hidden" name="billings_name" value="<?=$user_name?>">
                      <input type="hidden" name="billings_email" value="<?=$user_email?>">
                      <input type="hidden" name="billings_country" value="PK">
                      <input type="hidden" name="billings_zip" value="0000">
                  
                <!-- <div id="form-credit-card">
                  <h4 class="mt-8 mb-6">Credit Card Info</h4>
                  <form>
                    <div class="row g-6">
                      <div class="col-12">
                        <label class="form-label" for="billings-card-num">Card number</label>
                        <div class="input-group input-group-merge">
                          <input
                            type="text"
                            id="billings-card-num"
                            class="form-control billing-card-mask"
                            placeholder="7465 8374 5837 5067"
                            aria-describedby="paymentCard" />
                          <span class="input-group-text cursor-pointer p-1" id="paymentCard"
                            ><span class="card-type"></span
                          ></span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label" for="billings-card-name">Name</label>
                        <input type="text" id="billings-card-name" class="form-control" placeholder="John Doe" />
                      </div>
                      <div class="col-md-3">
                        <label class="form-label" for="billings-card-date">EXP. Date</label>
                        <input
                          type="text"
                          id="billings-card-date"
                          class="form-control billing-expiry-date-mask"
                          placeholder="MM/YY" />
                      </div>
                      <div class="col-md-3">
                        <label class="form-label" for="billings-card-cvv">CVV</label>
                        <input
                          type="text"
                          id="billings-card-cvv"
                          class="form-control billing-cvv-mask"
                          maxlength="3"
                          placeholder="965" />
                      </div>
                    </div>
                  </form>
                </div> -->

              </div>
              <div class="col-lg-5 card-body p-md-8">
                <h4 class="mb-2">Order Summary</h4>
                <p class="mb-8">
                  It can help you manage and service orders before,<br />
                  during and after fulfilment.
                </p>

                <div class="bg-lighter p-6 rounded">
                  <p><b><?php echo $package['name'] ? $package['name'] : "N/A"; ?></b> <br> <?php echo $package['description'] ? $package['description'] : "N/A"; ?></p>
                  <div class="d-flex align-items-center mb-4">
                    <h1 class="text-heading mb-0">$<?php echo $package['price'] ? number_format((float)$package['price']*$month_limit, 2, '.', '') : "0"; ?></h1>
                    <sub class="h6 text-body mb-n3">/month</sub>
                  </div>
                  <!-- <div class="d-grid">
                    <button
                      type="button"
                      data-bs-target="#pricingModal"
                      data-bs-toggle="modal"
                      class="btn btn-label-primary">
                      Change Plan
                    </button>
                  </div> -->
                </div>

                <div class="mt-5">
                  <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0">Subtotal</p>
                    <h6 class="mb-0">$<?php echo $package['price'] ? number_format((float)$package['price'], 2, '.', '') : "0"; ?></h6>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-2">
                    <p class="mb-0">Months</p>
                    <h6 class="mb-0">$<?php echo $package['price'] ? number_format((float)$package['price'], 2, '.', '') : "0"; ?> x <?=$month_limit;?></h6>
                  </div>
                  <hr />
                  <div class="d-flex justify-content-between align-items-center mt-4 pb-1">
                    <p class="mb-0">Total</p>
                    <h6 class="mb-0">$<?php echo $package['price'] ? number_format((float)$package['price']*$month_limit, 2, '.', '') : "0"; ?></h6>
                  </div>
                  <div class="d-grid mt-5">
                    <button class="btn btn-success">
                      <span class="me-2">Checkout </span>
                      <i class="ti ti-arrow-right scaleX-n1-rtl"></i>
                    </button>
                  </div>

                  <p class="mt-8">
                    By continuing, you accept to our Terms of Services and Privacy Policy. Please note that payments are
                    non-refundable.
                  </p>
                </div>
              </div>
            </div>
            <input type="hidden" name="order_id" value="<?=$order_id?>">
            <input type="hidden" name="user_id" value="<?=$user_id?>">
            <input type="hidden" name="package_id" value="<?=$package['id']?>">
            <input type="hidden" name="price" value="<?=$package['price']*$month_limit?>">
            <input type="hidden" name="update-subscription-request" value="1">
          </form>
        </div>
      </div>
    </section>

    <!-- Modal -->
    <!-- Pricing Modal -->
    <div class="modal fade" id="pricingModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-simple modal-pricing">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <!-- Pricing Plans -->
            <div class="rounded-top">
              <h4 class="text-center mb-2">Pricing Plans</h4>
              <p class="text-center mb-0">
                All plans include 40+ advanced tools and features to boost your product. Choose the best plan to fit
                your needs.
              </p>
              <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 pt-12 pb-4">
                <label class="switch switch-sm ms-sm-12 ps-sm-12 me-0">
                  <span class="switch-label fs-6 text-body">Monthly</span>
                  <input type="checkbox" class="switch-input price-duration-toggler" checked />
                  <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                  </span>
                  <span class="switch-label fs-6 text-body">Annually</span>
                </label>
                <div class="mt-n5 ms-n10 ml-2 mb-10 d-none d-sm-flex align-items-center gap-2">
                  <i class="ti ti-corner-left-down ti-lg text-muted scaleX-n1-rtl"></i>
                  <span class="badge badge-sm bg-label-primary rounded-1 mb-2">Save up to 10%</span>
                </div>
              </div>
              <div class="row gy-6">
                <!-- Basic -->
                <div class="col-xl mb-md-0">
                  <div class="card border rounded shadow-none">
                    <div class="card-body pt-12">
                      <div class="mt-3 mb-5 text-center">
                        <img
                          src="assets/img/illustrations/page-pricing-basic.png"
                          alt="Basic Image"
                          height="100" />
                      </div>
                      <h4 class="card-title text-center text-capitalize mb-1">Basic</h4>
                      <p class="text-center mb-5">A simple start for everyone</p>
                      <div class="text-center h-px-50">
                        <div class="d-flex justify-content-center">
                          <sup class="h6 text-body pricing-currency mt-2 mb-0 me-1">$</sup>
                          <h1 class="mb-0 text-primary">0</h1>
                          <sub class="h6 text-body pricing-duration mt-auto mb-1">/month</sub>
                        </div>
                      </div>

                      <ul class="list-group ps-6 my-5 pt-9">
                        <li class="mb-4">100 responses a month</li>
                        <li class="mb-4">Unlimited forms and surveys</li>
                        <li class="mb-4">Unlimited fields</li>
                        <li class="mb-4">Basic form creation tools</li>
                        <li class="mb-0">Up to 2 subdomains</li>
                      </ul>

                      <button type="button" class="btn btn-label-success d-grid w-100" data-bs-dismiss="modal">
                        Your Current Plan
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Pro -->
                <div class="col-xl mb-md-0">
                  <div class="card border-primary border shadow-none">
                    <div class="card-body position-relative pt-4">
                      <div class="position-absolute end-0 me-5 top-0 mt-4">
                        <span class="badge bg-label-primary rounded-1">Popular</span>
                      </div>
                      <div class="my-5 pt-6 text-center">
                        <img
                          src="assets/img/illustrations/page-pricing-standard.png"
                          alt="Standard Image"
                          height="100" />
                      </div>
                      <h4 class="card-title text-center text-capitalize mb-1">Standard</h4>
                      <p class="text-center mb-5">For small to medium businesses</p>
                      <div class="text-center h-px-50">
                        <div class="d-flex justify-content-center">
                          <sup class="h6 text-body pricing-currency mt-2 mb-0 me-1">$</sup>
                          <h1 class="price-toggle price-yearly text-primary mb-0">7</h1>
                          <h1 class="price-toggle price-monthly text-primary mb-0 d-none">9</h1>
                          <sub class="h6 text-body pricing-duration mt-auto mb-1">/month</sub>
                        </div>
                        <small class="price-yearly price-yearly-toggle text-muted">USD 480 / year</small>
                      </div>

                      <ul class="list-group ps-6 my-5 pt-9">
                        <li class="mb-4">Unlimited responses</li>
                        <li class="mb-4">Unlimited forms and surveys</li>
                        <li class="mb-4">Instagram profile page</li>
                        <li class="mb-4">Google Docs integration</li>
                        <li class="mb-0">Custom “Thank you” page</li>
                      </ul>

                      <button type="button" class="btn btn-primary d-grid w-100" data-bs-dismiss="modal">
                        Upgrade
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Enterprise -->
                <div class="col-xl">
                  <div class="card border rounded shadow-none">
                    <div class="card-body pt-12">
                      <div class="mt-3 mb-5 text-center">
                        <img
                          src="assets/img/illustrations/page-pricing-enterprise.png"
                          alt="Enterprise Image"
                          height="100" />
                      </div>
                      <h4 class="card-title text-center text-capitalize mb-1">Enterprise</h4>
                      <p class="text-center mb-5">Solution for big organizations</p>

                      <div class="text-center h-px-50">
                        <div class="d-flex justify-content-center">
                          <sup class="h6 text-body pricing-currency mt-2 mb-0 me-1">$</sup>
                          <h1 class="price-toggle price-yearly text-primary mb-0">16</h1>
                          <h1 class="price-toggle price-monthly text-primary mb-0 d-none">19</h1>
                          <sub class="h6 text-body pricing-duration mt-auto mb-1">/month</sub>
                        </div>
                        <small class="price-yearly price-yearly-toggle text-muted">USD 960 / year</small>
                      </div>

                      <ul class="list-group ps-6 my-5 pt-9">
                        <li class="mb-4">PayPal payments</li>
                        <li class="mb-4">Logic Jumps</li>
                        <li class="mb-4">File upload with 5GB storage</li>
                        <li class="mb-4">Custom domain support</li>
                        <li class="mb-0">Stripe integration</li>
                      </ul>

                      <button type="button" class="btn btn-label-primary d-grid w-100" data-bs-dismiss="modal">
                        Upgrade
                      </button>
                    </div>
                  </div>
                </div>

               

              </div>
            </div>
            <!--/ Pricing Plans -->
          </div>
        </div>
      </div>
    </div>
    <!--/ Pricing Modal -->

                     <div class="col-md-12 hide_by_position">
                          <form action="https://sandbox.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">
                          <!-- <form action="https://payments.bankalfalah.com/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">                                                               -->
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
                            <input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="hidden" value="<?php echo $bankorderId;?>">
                            <input autocomplete="off"  id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="hidden" value="<?php echo $TransactionAmount; ?>">  
                            <!-- <center><button type="submit" class="btn btn-custon-four btn-danger" id="run">PAY ONLINE</button></center>                                                                                                     -->
                          <button type="submit" class="btn btn-primary mt-4">Pay Now</button>
                        </form> 
                  </div>

    <!-- <script src="assets//js/pages-pricing.js"></script> -->

    <!-- /Modal -->

    <!-- / Sections:End -->

<?php include("inc/footer.php"); ?>
 <script type="text/javascript">
  $(document).ready(function() {


    $("#updateSubscriptionForm").on('submit', (function(e) {
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
                alert("Order Upated Successfully, Make payment to proceed further !");
                document.getElementById('PageRedirectionForm').submit();
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
        var pkg_id = "<?=$package_id?>";
        var month_limit = $("#month_limit").val();
        var user_id = "<?=$user_id?>";
        var order_id = "<?=$order_id?>";

        var url = 'pending_payment.php';
        var form = $('<form action="' + url + '" method="GET">' +
        '<input type="hidden" name="pkg_id" value="'+pkg_id+'" />' +
        '<input type="hidden" name="payment_mode" value="'+payment_mode+'" />' +
        '<input type="hidden" name="month_limit" value="'+month_limit+'" />' +
        '<input type="hidden" name="user_id" value="'+user_id+'" />' +
        '<input type="hidden" name="ord_id" value="'+order_id+'" />' +
        '</form>');
    
    $('body').append(form);
    form.submit();

  });

    $("#month_limit").change(function(){
      var payment_mode = "<?=$payment_mode?>";
      var pkg_id = "<?=$package_id?>";
      var month_limit = $(this).val();
      var user_id = "<?=$user_id?>";
      var order_id = "<?=$order_id?>";

      var url = 'pending_payment.php';
      var form = $('<form action="' + url + '" method="GET">' +
      '<input type="hidden" name="pkg_id" value="'+pkg_id+'" />' +
      '<input type="hidden" name="payment_mode" value="'+payment_mode+'" />' +
      '<input type="hidden" name="month_limit" value="'+month_limit+'" />' +
      '<input type="hidden" name="user_id" value="'+user_id+'" />' +
      '<input type="hidden" name="ord_id" value="'+order_id+'" />' +
      '</form>');

      $('body').append(form);
      form.submit();
    });

</script>