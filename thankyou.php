<?php 
include("inc/header.php"); 
include("function.php"); 

$ord_id = 0;
$detail = 0;
if(isset($_GET['ord_id'])){
  $ord_id = $_GET['ord_id'];
  if($_GET['type'] == "product_order"){
    $detail = get_single_order($ord_id);
  }else{
    $detail = get_single_subscription($ord_id);
  }
}



  ?>

  <body>
    <script src="assets/vendor/js/dropdown-hover.js"></script>
    <script src="assets/vendor/js/mega-dropdown.js"></script>

    <?php include("inc/topbar.php"); ?>


    <!-- Sections:Start -->

    <!-- Pricing Plans -->
    <section class="section-py first-section-pt">
      <div class="container">
        <h2 class="text-center mb-2">Pricing Plans</h2>
        <p class="text-center mb-0">
          All plans include 40+ advanced tools and features to boost your product.<br />
          Choose the best plan to fit your needs.
        </p>
        

        <div class="row g-6">
          <div class="col-12 col-lg-8 mx-auto text-center mb-2">
            <h4>Thank You! 😇</h4>
            <p>
              Your order <a href="javascript:void(0)" class="text-heading fw-medium">#<?=$ord_id;?></a> has been
              placed!
            </p>
            <p>
              We sent an email to
              <a href="mailto:<?=$detail['billings_email']?>" class="text-heading fw-medium"><?=$detail['billings_email']?></a> with
              your order confirmation and receipt. If the email hasn't arrived within two minutes, please check
              your spam folder to see if the email was routed there.
            </p>
            <p>
              <span><i class="ti ti-clock me-1 text-heading"></i> Time placed:&nbsp;</span> <?= system_date_format($detail['created_at']); ?>
            </p>
          </div>

        </div>
      </div>
    </section>
    <!--/ Pricing Plans -->




    <!-- / Sections:End -->

<?php include("inc/footer.php"); ?>