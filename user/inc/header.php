<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

if(!isset($_SESSION['loggedin_id'])){
  header("location:login.php");
}

function get_current_user_dett(){
    include("../db_config.php");
    $usr_id = $_SESSION['loggedin_id'];
    $query = "SELECT * from users where id = '".$usr_id."' ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res;
}

function get_current_user_roi(){
    include("../db_config.php");
    $usr_id = $_SESSION['loggedin_id'];

    $query = "SELECT * from orders where user_id = '".$usr_id."' AND roi_paid = 0  ";
    $run = mysqli_query($conn, $query);

    while($row=mysqli_fetch_assoc($run)){


        // GET ROI DAYS ________
        $query2 = "SELECT * from product where id = '".$row['product_id']."'";
        $run2 = mysqli_query($conn, $query2);
        $run2=mysqli_fetch_assoc($run2);
        $roi_days = $run2['roi_days'];
        $roi_perc = $run2['roi'];
        // GET ROI DAYS ________


        // DATE ELIGIBILITY FOR BALANCE ________
        $dateFromDatabase = new DateTime($row['created_at']);
        $dateFromDatabase->modify('+'.$roi_days.' day');
        $currentDate = new DateTime();

        if ($dateFromDatabase <= $currentDate) {
            echo 'The date from the database is greater than or equal to the current system date';
            $query3 = "UPDATE orders SET roi_paid = 1 where id = '".$row['id']."' ";
            $run3 = mysqli_query($conn, $query3);

            $balance = (($row['price']) + ($row['price'])*($roi_perc/100));
            $query4 = "UPDATE users SET balance = balance+".$balance." where id = '".$usr_id."' ";
            $run4 = mysqli_query($conn, $query4);

        } else {
            // echo 'The date from the database is less than the current system date';
        }
        // DATE ELIGIBILITY FOR BALANCE ________

    }

}
get_current_user_roi();
//   // exit();
//   echo '<script type="text/javascript">';
//   // echo 'window.location.href="login.php";';
//   echo 'alert("sad");';
//   echo '</script>';
//   exit;
// }

function get_single_user_subscriptionn($user_id=0){
    include("../db_config.php");
  $query = "SELECT * from subscription where user_id = '".$user_id."' AND active = '1' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

?>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template" data-style="light">

  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Product List - eCommerce | Vuexy - Bootstrap Admin Template</title>

    <meta name="description" content="" />

    

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />

    <link rel="stylesheet" href="assets/vendor/libs/quill/typography.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/editor.css" />
    

    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/vendor/js/template-customizer.js"></script>
    <script src="assets/js/config.js"></script>
<style>
    .top-bar-freetrial {
        text-align: center;
        position: relative;
        top: 85px;
        width: 100%;
        z-index: 1010;
        opacity: 0.9;
        color: #7367f0;
        margin: auto;
        box-shadow: 2px 2px 5px -4px black;
    }
</style>
  </head>
<?php
  $package = get_current_user_dett()['package'];
  if($package == 4){
  ?>
       <div class="alert alert-warning top-bar-freetrial" role="alert">
        <b>Recommended:</b> <a href="my_subscription.php" style="text-decoration:underline" class="cursor-pointer">Upgrade</a> your package to use more features, <a href="javascript:void(0)" style="text-decoration:underline" class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#addNewCCModalVid">Click here</a> to learn more..!
        </div>

<?php } 
    

    $usr_id = $_SESSION['loggedin_id'];
    $subscription_user = get_single_user_subscriptionn($usr_id);
    if($subscription_user != NULL){

    $month_limit = $subscription_user['month_limit'];
    $day_limit = 30 * $month_limit;
$created_at = $subscription_user['created_at'];
    $currentDate = new DateTime();
    $created_at = new DateTime($created_at);

    $interval = $created_at->diff($currentDate);
    $total_days = $interval->days; 

    if($total_days > $day_limit){
?>

       <div class="alert alert-warning top-bar-freetrial" role="alert">
        <b>LIMIT EXCEEDED: </b>  Your subscription has been expired, <a href="https://seller.egrocify.com/user/my_subscription.php" style="text-decoration:underline" class="cursor-pointer" target="_blank">Click here</a> to renew..!
        </div>
        <? } 
    }
        
        ?>


        <?php
        include("video_modal.php");
        ?>