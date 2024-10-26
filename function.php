<?php
  ini_set('display_errors', '0');
  ini_set('display_startup_errors', '0');
  error_reporting(E_ALL);

  function notification_add($msg = " "){
    include("db_config.php");
    $usr_id = $_SESSION['loggedin_id'];

    $query = "INSERT INTO notification (message, user_id)
    VALUES ('".$msg."', '".$usr_id."')";
    $run = mysqli_query($conn, $query);
    return;
  }

  function notification_get(){
    include("db_config.php");
    $query = "SELECT * from notification ";
    $run = mysqli_query($conn, $query);
    return $run;
  }

  function get_user_det($id=0){
    include("db_config.php");
    $query = "SELECT * from users where id = '".$id."' ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res;
  }
  
  function get_current_user_det(){
    include("db_config.php");
    $usr_id = $_SESSION['loggedin_id'];
    $query = "SELECT * from users where id = '".$usr_id."' ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res;
  }

  function system_date_format($date){
    return date('d M Y', strtotime($date) );
  }

  function countryList(){
  $countryList = array("Afghanistan","Albania","Algeria","Andorra","Angola","Antigua and Barbuda","Argentina","Armenia","Australia","Austria","Austrian Empire*","Azerbaijan","Baden*","Bahamas, The","Bahrain","Bangladesh","Barbados","Bavaria*","Belarus","Belgium","Belize","Benin (Dahomey)","Bolivia","Bosnia and Herzegovina","Botswana","Brazil","Brunei","Brunswick and Lüneburg","Bulgaria","Burkina Faso (Upper Volta)","Burma","Burundi","Cabo Verde","Cambodia","Cameroon","Canada","Cayman Islands, The","Central African Republic","Central American Federation*","Chad","Chile","China","Colombia","Comoros","Congo Free State, The*","Costa Rica","Cote d’Ivoire (Ivory Coast)","Croatia","Cuba","Cyprus","Czechia","Czechoslovakia","Democratic Republic of the Congo","Denmark","Djibouti","Dominica","Dominican Republic","Duchy of Parma, The*","East Germany (German Democratic Republic)*","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Eswatini","Ethiopia","Federal Government of Germany (1848-49)*","Fiji","Finland","France","Gabon","Gambia, The","Georgia","Germany","Ghana","Grand Duchy of Tuscany, The*","Greece","Grenada","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Hanover*","Hanseatic Republics*","Hawaii*","Hesse*","Holy See","Honduras","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kingdom of Serbia/Yugoslavia*","Kiribati","Korea","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Lew Chew (Loochoo)*","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mecklenburg-Schwerin*","Mecklenburg-Strelitz*","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Morocco","Mozambique","Namibia","Nassau*","Nauru","Nepal","Netherlands, The","New Zealand","Nicaragua","Niger","Nigeria","North German Confederation*","North German Union*","North Macedonia","Norway","Oldenburg*","Oman","Orange Free State*","Pakistan","Palau","Panama","Papal States*","Papua New Guinea","Paraguay","Peru","Philippines","Piedmont-Sardinia*","Poland","Portugal","Qatar","Republic of Genoa*","Republic of Korea (South Korea)","Republic of the Congo","Romania","Russia","Rwanda","Saint Kitts and Nevis","Saint Lucia","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Schaumburg-Lippe*","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands, The","Somalia","South Africa","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Sweden","Switzerland","Syria","Tajikistan","Tanzania","Texas*","Thailand","Timor-Leste","Togo","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Two Sicilies*","Uganda","Ukraine","Union of Soviet Socialist Republics*","United Arab Emirates, The","United Kingdom, The","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","Württemberg*","Yemen","Zambia","Zimbabwe");
  return $countryList;
}

function get_all_companies($type="all"){
  include("db_config.php");

  if($type == 'approved')
    $query = "SELECT * from company WHERE active = '1' AND deleted <> 1";
  if($type == 'unapproved')
    $query = "SELECT * from company WHERE active = '0' AND deleted <> 1";
  if($type == 'all')
    $query = "SELECT * from company WHERE deleted <> 1 ";

  $data = mysqli_query($conn, $query);
  return $data;
}
function get_single_company($id=0){
  include("db_config.php");
  $query = "SELECT * from company where id = '".$id."' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function get_all_brands($type="all"){
  include("db_config.php");

  if($type == 'approved')
    $query = "SELECT * from brand WHERE active = '1' AND deleted <> 1";
  if($type == 'unapproved')
    $query = "SELECT * from brand WHERE active = '0' AND deleted <> 1";
  if($type == 'all')
    $query = "SELECT * from brand WHERE deleted <> 1 ";

  $data = mysqli_query($conn, $query);
  return $data;
}
function get_single_brand($id=0){
  include("db_config.php");
  $query = "SELECT * from brand where id = '".$id."' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function get_all_products($type="all", $brand=0){
  include("db_config.php");

  $brandCheck = " ";
  if($brand <>0)
    $brandCheck = " AND brand = ".$brand." ";

  if($type == 'approved')
    $query = "SELECT * from product WHERE active = '1' AND deleted <> 1 ".$brandCheck."";
  if($type == 'unapproved')
    $query = "SELECT * from product WHERE active = '0' AND deleted <> 1 ".$brandCheck."";
  if($type == 'all')
    $query = "SELECT * from product WHERE deleted <> 1 ".$brandCheck."";

  $data = mysqli_query($conn, $query);
  return $data;
}
function get_single_product($id=0){
  include("db_config.php");
  $query = "SELECT * from product where id = '".$id."' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function get_all_users($type="all"){
  include("db_config.php");

  if($type == 'approved')
    $query = "SELECT * from users WHERE active = '1' AND deleted <> 1";
  if($type == 'unapproved')
    $query = "SELECT * from users WHERE active = '0' AND deleted <> 1";
  if($type == 'all')
    $query = "SELECT * from users WHERE deleted <> 1 ";

  $data = mysqli_query($conn, $query);
  return $data;
}
function get_single_user($id=0){
  include("db_config.php");
  $query = "SELECT * from users where id = '".$id."' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function get_all_packages($type="all"){
  include("db_config.php");

  if($type == 'approved')
    $query = "SELECT * from package WHERE active = '1' AND deleted <> 1 ORDER BY sorting ";
  if($type == 'unapproved')
    $query = "SELECT * from package WHERE active = '0' AND deleted <> 1 ORDER BY sorting ";
  if($type == 'all')
    $query = "SELECT * from package WHERE deleted <> 1 ORDER BY sorting  ";

  $data = mysqli_query($conn, $query);
  return $data;
}
function get_single_package($id=0){
  include("db_config.php");
  $query = "SELECT * from package where id = '".$id."' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function getCompanyDropdown($ind_bus = 1){
  include("db_config.php");
  $query = "SELECT * from company where active = '1' AND deleted <> 1 ";
  $run = mysqli_query($conn, $query);
  
  $data = array();
  while($row=mysqli_fetch_assoc($run)){
    $data[$row['id']] = $row['name'];
  }
  return $data;
}

function getPackageDropdown($ind_bus = 1){
  include("db_config.php");
  $query = "SELECT * from package where active = '1' AND deleted <> 1 ";
  $run = mysqli_query($conn, $query);
  
  $data = array();
  while($row=mysqli_fetch_assoc($run)){
    $data[$row['id']] = $row['name'];
  }
  return $data;
}

function getBrandDropdown($ind_bus = 1){
  include("db_config.php");
  $query = "SELECT * from brand where active = '1' AND deleted <> 1 ";
  $run = mysqli_query($conn, $query);
  
  $data = array();
  while($row=mysqli_fetch_assoc($run)){
    $data[$row['id']] = $row['name'];
  }
  return $data;
}

function get_all_subscription($type="all", $user_id = 0){
  include("db_config.php");

  $userCheck = " ";
  if($user_id <> 0){
    $userCheck = " AND user_id = ".$user_id." ";
  }

  if($type == 'approved')
    $query = "SELECT * from subscription WHERE active = '1' AND deleted <> 1 ".$userCheck."";
  if($type == 'unapproved')
    $query = "SELECT * from subscription WHERE active = '0' AND deleted <> 1 ".$userCheck."";
  if($type == 'all')
    $query = "SELECT * from subscription WHERE deleted <> 1  ".$userCheck."";

  
  $data = mysqli_query($conn, $query);
  return $data;
}

function get_single_subscription($id=0){
  include("db_config.php");
  $query = "SELECT * from subscription where id = '".$id."' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function get_single_user_subscription($user_id=0){
  include("db_config.php");
  $query = "SELECT * from subscription where user_id = '".$user_id."' AND active = '1' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function check_expiry(){

    $usr_id = $_SESSION['loggedin_id'];
    $subscription_user = get_single_user_subscription($usr_id);
    $month_limit = $subscription_user['month_limit'];
    $day_limit = 30 * $month_limit;
    $created_at = $subscription_user['created_at'];
    $currentDate = new DateTime();
    $created_at = new DateTime($created_at);

    $interval = $created_at->diff($currentDate);
    $total_days = $interval->days; 
      
    $output = false;
    if($total_days > $day_limit){
      $output = true;
    }
    return $output;
}

function count_products_by_package($package_id=0){
  include("db_config.php");
  $query = "SELECT COUNT(*) as rowNum FROM product WHERE package = '".$package_id."' AND active ='1' AND deleted <> 1   ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res['rowNum'];
}

function total_user_sale_by_pkg($package_id = 0){
  include("db_config.php");
  $query = "SELECT SUM(price) as total  FROM subscription WHERE package_id = '".$package_id."' AND active ='1' AND deleted <> 1 GROUP BY package_id   ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res['total'];
}

function get_all_withdraws($type="all", $user_id = 0){
  include("db_config.php");
  $userCheck = " ";
  if($user_id <> 0){
    $userCheck = " AND user_id = ".$user_id." ";
  }
  if($type == 'approved')
    $query = "SELECT * from withdraw WHERE active = '1' AND deleted <> 1 ".$userCheck."";
  if($type == 'unapproved')
    $query = "SELECT * from withdraw WHERE active = '0' AND deleted <> 1 ".$userCheck."";
  if($type == 'all')
    $query = "SELECT * from withdraw WHERE deleted <> 1 ".$userCheck."";

  $data = mysqli_query($conn, $query);
  return $data;
}

function currency_sym($package_id = 0){
  include("db_config.php");
  $user_id = $_SESSION['loggedin_id'];
  
  $query = "SELECT currency as currency  FROM currency WHERE user_id = '".$user_id."' AND active ='1' GROUP BY user_id   ";
  $run = mysqli_query($conn, $query);

  if($run->num_rows == 0){
    return '$';
    exit;
  }
  $res=mysqli_fetch_assoc($run);
  if($res['currency'] == "USD"){
    return '$';
  }else{
    return 'Rs.';
  }
}

// Function to download an image from URL and save it locally
function download_image($url, $save_to_path) {
    $ch = curl_init($url);
    $fp = fopen($save_to_path, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return;
}

function get_single_order($id=0){
  include("db_config.php");
  $query = "SELECT * from orders where id = '".$id."' ";
  $run = mysqli_query($conn, $query);
  $res=mysqli_fetch_assoc($run);
  return $res;
}

function get_all_orders($type="all", $user_id = 0){
  include("db_config.php");

  $userCheck = " ";
  if($user_id <> 0){
    $userCheck = " AND user_id = ".$user_id." ";
  }

  if($type == 'approved')
    $query = "SELECT * from orders WHERE active = '1' AND deleted <> 1 ".$userCheck."";
  if($type == 'unapproved')
    $query = "SELECT * from orders WHERE active = '0' AND deleted <> 1 ".$userCheck."";
  if($type == 'all')
    $query = "SELECT * from orders WHERE deleted <> 1  ".$userCheck."";

  
  $data = mysqli_query($conn, $query);
  return $data;
}

function send_email_verification_mail($id=0){
        if($id <> 0){
          $user_data = get_user_det($id);
        }

        include("email_templates/verification_email.php");
        $to = $user_data['email'];
        // $to = $email;
        $subject ="Email Verification - EGROCIFY RESELLER CENTER";
        // $message ="Your OTP Verification code is :";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= 'From:EgrocifyResellerCenter<info@seller.egrocify.com>' . "\r\n";

        if(mail($to,$subject,$message,$headers))
          return 1;
        else
          return 0;
}

function get_all_products_for_csv($type="all"){
  include("db_config.php");

  if($type == 'approved')
    $query = "SELECT name, sku, barcode, package, brand, roi, roi_days, short_description, long_description, in_stock, cost, price, featured_image, images, location, active from product WHERE active = '1' AND deleted <> 1";
  if($type == 'unapproved')
    $query = "SELECT name, sku, barcode, package, brand, roi, roi_days, short_description, long_description, in_stock, cost, price, featured_image, images, location, active from product WHERE active = '0' AND deleted <> 1";
  if($type == 'all')
    $query = "SELECT name, sku, barcode, package, brand, roi, roi_days, short_description, long_description, in_stock, cost, price, featured_image, images, location, active from product WHERE deleted <> 1 ";

  $data = mysqli_query($conn, $query);
  return $data;
}

function send_email_referral_link($data = array()){

  include("email_templates/referral_email.php");
  $to = $data['email'];
  // $to = $email;
  $subject ="Referral Link - EGROCIFY RESELLER CENTER";
  // $message ="Your OTP Verification code is :";

  // Always set content-type when sending HTML email
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

  // More headers
  $headers .= 'From:EgrocifyResellerCenter<'.$data['user_email'].'>' . "\r\n";

  if(mail($to,$subject,$message,$headers))
    return 1;
  else
    return 0;
}

function encryptString($plaintext, $key) {
  $ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
  $iv = openssl_random_pseudo_bytes($ivlen);
  $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
  $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
  return base64_encode($iv.$hmac.$ciphertext_raw);
}

function decryptString($ciphertext, $key) {
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    $plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    if (hash_equals($hmac, $calcmac)) {
        return $plaintext;
    }
    return 0;
}
function display_amount($amount =0, $decimal = 2) {
  
  $amount_format = currency_sym().number_format((float)$amount, $decimal, '.', '');
  return $amount_format;
}

function get_all_reffered_users($user_id=0, $type= "all"){
  include("db_config.php");
  if($user_id == 0){
    return 0;
  }

  if($type == 'approved')
    $query = "SELECT * from users WHERE active = '1' AND deleted <> 1 AND inviter_code = ".$user_id."";
  if($type == 'unapproved')
    $query = "SELECT * from users WHERE active = '0' AND deleted <> 1 AND inviter_code = ".$user_id."";
  if($type == 'all')
    $query = "SELECT * from users WHERE deleted <> 1 AND inviter_code = ".$user_id." ";

  $data = mysqli_query($conn, $query);
  return $data;
}

  function get_last_order_id(){
    include("db_config.php");
    $usr_id = $_SESSION['loggedin_id'];
    $query = "SELECT max(id)+1 as order_id from orders ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res['order_id'];
  }


  function get_last_subs_id(){
    include("db_config.php");
    $usr_id = $_SESSION['loggedin_id'];
    $query = "SELECT max(id)+1 as order_id from subscription ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res['order_id'];
  }


  function encryptStringShort($num) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $base = strlen($chars);
    $encoded = '';
    while ($num > 0) {
        $encoded = $chars[$num % $base] . $encoded;
        $num = (int)($num / $base);
    }
    return str_pad($encoded, 6, '0', STR_PAD_LEFT); // Ensure the length is 6 characters
}

function decryptStringShort($encoded) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $base = strlen($chars);
    $decoded = 0;
    $len = strlen($encoded);
    for ($i = 0; $i < $len; $i++) {
        $decoded = $decoded * $base + strpos($chars, $encoded[$i]);
    }
    return $decoded;
}

  function monthsSubsList(){
  $monthsSubsList = array( '1' => '1 Month', '2' => '2 Months', '3' => '3 Months', '4' => '4 Months', '5' => '5 Months', '6' => '6 Months', '7' => '7 Months', '8' => '8 Months', '9' => '9 Months', '10'  => '10 Months', '11'  => '11 Months', '12'  => '12 Months');
  return $monthsSubsList;
}

  function get_total_prod_in_brand($brand = 0){
    include("db_config.php");
    $query = "SELECT count(*) as tot_prod from product WHERE brand =  ".$brand." ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res['tot_prod'];

  }
  
  
  // USER STATS

  function total_user_sale($user_id = 0){
    include("db_config.php");
    if($user_id == 0)
      $user_id = $_SESSION['loggedin_id'];

    $query = "SELECT SUM(price) as total  FROM orders WHERE user_id = '".$user_id."' AND active ='1' AND deleted <> 1 AND paid_status =1  ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res['total'];
  }

  function total_user_withdraw($user_id = 0){
    include("db_config.php");
    if($user_id == 0)
      $user_id = $_SESSION['loggedin_id'];

    $query = "SELECT SUM(amount) as total  FROM withdraw WHERE user_id = '".$user_id."' AND active ='3' AND deleted <> 1   ";
    $run = mysqli_query($conn, $query);
    $res=mysqli_fetch_assoc($run);
    return $res['total'];
  }
// USER STATS

?>