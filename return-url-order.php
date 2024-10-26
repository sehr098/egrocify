<?php 
include("inc/header.php"); 
include("function.php"); 

$ord_id = 0;
$detail = 0;

if(isset($_GET['O'])){

  $ord_id = $_GET['O'];
  $ord_id = explode("_",$ord_id)[0];
  $detail = get_single_order($ord_id);
}


include("db_config.php");

if($ord_id <> 0 && $_GET['TS'] == "P" ){
  $query_update = "UPDATE orders
  SET paid_status = '1'
  WHERE id = '".$ord_id."'
  ";
  
  $run = mysqli_query($conn, $query_update);

    $query2 = "SELECT inviter_code as inviter from users  
    WHERE id = '".$detail['user_id']."'
    ";
    $run2 = mysqli_query($conn, $query2);
    $res2=mysqli_fetch_assoc($run2);
    $inviter = $res2['inviter'];

    // IF ANY REFFERAL USER THEN REWARD QUERY ON ORDER SUBMISSION ON FIRSTTIME
    if($inviter <> 0){
      $query3 = "SELECT package as package from users  
      WHERE id = '".$inviter."'
      ";
      $run3 = mysqli_query($conn, $query3);
      $res3=mysqli_fetch_assoc($run3);
      $package = $res3['package'];

      $referal_reward = get_single_package($package)['referal_reward'];

      $query_update4 = "UPDATE users
      SET balance = balance + ".$referal_reward.", reward_paid = 1
      WHERE id = '".$detail['user_id']."' AND reward_paid = '0'
      ";

      $run4 = mysqli_query($conn, $query_update4);


      $insert_update5 = "INSERT INTO notification 
      (message, user_id, ord_id, qty, type, active)
      VALUES ('sold products', '".$detail['user_id']."', '".$ord_id."', '".$detail['qty']."', 'order_post', 1)
      ";
      $run5 = mysqli_query($conn, $insert_update5);

    }
    // IF ANY REFFERAL USER THEN REWARD QUERY ON ORDER SUBMISSION ON FIRSTTIME


}
  ?>

  <body>
    <script src="assets/vendor/js/dropdown-hover.js"></script>
    <script src="assets/vendor/js/mega-dropdown.js"></script>

    <?php include("inc/topbar.php"); ?>


    <!-- Sections:Start -->

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




    <!-- / Sections:End -->

<?php include("inc/footer.php"); ?>