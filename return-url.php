<?php 
include("inc/header.php"); 
include("function.php"); 

$ord_id = 0;
$detail = 0;

// var_dump($_POST);
// var_dump($_GET['O']);

if(isset($_GET['O'])){

  $ord_id = $_GET['O'];
  $ord_id = explode("_",$ord_id)[0];
  $detail = get_single_subscription($ord_id);
}

include("db_config.php");

if($ord_id <> 0 && $_GET['TS']=="P" ){
  
  $query_update = "UPDATE subscription
  SET paid = '1', active='1'
  WHERE id = '".$ord_id."'
  ";

  $query_update2 = "UPDATE subscription
  SET active='0'
  WHERE id <> '".$ord_id."' AND user_id = '".$detail['user_id']."'
  ";
  
  $query_update3 = "UPDATE users
  SET package = '".$detail['package_id']."',month_limit = '".$detail['month_limit']."', active = 1
  WHERE id = '".$detail['user_id']."'
  ";
  $run = mysqli_query($conn, $query_update);
  $run2 = mysqli_query($conn, $query_update2);
  $run3 = mysqli_query($conn, $query_update3);
  // $run3 = mysqli_query($conn, $query_update3);

  // var_dump($query_update3);
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
       
        
        <?php
        if($_GET['TS']=="P"){
          ?>
        <div class="row g-6">
          <div class="col-12 col-lg-8 mx-auto text-center mb-2">
            <h4>Thank You! </h4>
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
        <?php }else{ ?>
          <div class="row g-6">
          <div class="col-12 col-lg-8 mx-auto text-center mb-2">
            <h4>Sorry! payment failed due to some reasons! </h4>
            <p>
              Your order <a href="javascript:void(0)" class="text-heading fw-medium">#<?=$ord_id;?></a> has been
              failed!
            </p>
            <p>
              Please try again to proceed payment.
            </p>
            <p>
              <span><i class="ti ti-clock me-1 text-heading"></i> Time placed:&nbsp;</span> <?= system_date_format($detail['created_at']); ?>
            </p>
          </div>

        </div>
        <? } ?>


      </div>
    </section>
    <!--/ Pricing Plans -->




    <!-- / Sections:End -->

<?php include("inc/footer.php"); ?>