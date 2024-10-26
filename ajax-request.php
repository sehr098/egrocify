<?php
    include("db_config.php");
    include("function.php");
    header('Access-Control-Allow-Origin: *');

    session_start();
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

   if(isset($_POST['signup-request']))  /* This will be the hidden field for all conditions*/
    {
        $uname = $_POST['username'];
        $email = $_POST['email'];       
        $password = $_POST['password'];
        $role = $_POST['role'];
        $inviter_code = $_POST['inviter_code'];
        $package = $_POST['package'];

        $active = 0;
        if($package == 4){
            $active = 1;
        }

        $balance = 0; 
        $package_det = 1;

        // VALIDATIONS ___________________
        if($uname==""){
            echo json_encode(array('success' => 0, 'msg' => " Name can not be empty")); die();
        }
        if($email==""){
            echo json_encode(array('success' => 0, 'msg' => "email can not be empty")); die();
        }
        if($password==""){
            echo json_encode(array('success' => 0, 'msg' => "password can not be empty")); die();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('success' => 0, 'msg' => "Invalid email address.")); die();
        }
    
     
        // VALIDATIONS ___________________
        

        $queryMatch = "SELECT * from users where email = '".$email."' AND active =1 AND deleted =0";
        $dataMatch = mysqli_query($conn, $queryMatch);
        
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Email Already registered, Please try a different one";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data); die();
        }
        else
        {
            $query = "INSERT INTO users (uname, email, password, role, inviter_code, balance, package, active)
                    VALUES ('".$uname."', '".$email."', '".md5($password)."', '".$role."', '".$inviter_code."', '".$balance."', '".$package."', '".$active."')";

            // var_dump($query);
            $run = mysqli_query($conn, $query);

            if(isset($run)){

                $custID = $conn->insert_id;
                $custIDPopuplated = "EG".$conn->insert_id.rand(10,100);
                $custIDPopuplated = substr($custIDPopuplated,0,6);

                // SEND VERIFICATION EMAIL
                $send = send_email_verification_mail($custID);

                $query_update = "UPDATE users
                                SET customer_id = '".$custIDPopuplated."'
                                WHERE id = '".$custID."'
                                ";
                $run2 = mysqli_query($conn, $query_update);

              
                if($inviter_code != 0){
                    $package_id = get_user_det($inviter_code)['package'];
                    $referal_reward = get_single_package($package_id)['referal_reward'];
                    
                    $query_referral = "INSERT INTO pending_referal_awards (user_id, pending_amount, status)
                                    VALUES ($custID, $referal_reward, 'pending')";
                    mysqli_query($conn, $query_referral);
                }

                // notification_add("New User <b>".$uname."</b> created");
                
                $data = array('success' => 1, 'msg' => "Successfully Signed up!", 'custID' => $custID);
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong!");
                echo json_encode($data);
            }
        }
    }
    if(isset($_POST['signin-request']))  /* This will be the hidden field for all conditions*/
    {
        $user_email = $_POST['email'];
        $user_password = $_POST['password'];

        $queryMatch = "SELECT * from users where email = '".$user_email."' AND password = '".md5($user_password)."' AND active = 1 AND deleted = 0 ";
        $dataMatch = mysqli_query($conn, $queryMatch);

        if($dataMatch->num_rows > 0)
        {
            
            while($row=mysqli_fetch_assoc($dataMatch))
            {
                $_SESSION["loggedin_id"] = $row['id'];
                $_SESSION["loggedin_name"] = $row['uname'];
                $_SESSION["loggedin_email"] = $row['email'];
                $_SESSION["loggedin_role_id"] = $row['role'];

                // WHATSAPP NOTIFICATION
                //WP_login_message( $row['fname'], $row['lname'], $row['customer_id'], $whatsapp_no);

                if($row['role'] ==1 ){
                    echo json_encode(array('success'=>1, 'msg' => "Success login", 'role' => $row['role']));
                }
                else{
                    // if($row['verified'] == 0){
                    //     $send = send_email_verification_mail($row['id']);
                    // }
                    echo json_encode(array('success'=>1, 'msg' => "Success login", 'role' => $row['role']));
                }
            }
            
        }
        else
        {
            echo json_encode(array('success'=>0, 'msg' => "Email or Password is incorrect .."));
            
        }

    }

    if(isset($_POST['update-user-request'])){  /* This will be the hidden field for all conditions*/


        // $email = $_POST['email'];        
        $user_id = $_POST['user_id'];
        $role = $_POST['role'];

        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $package = $_POST['package'];
        $phone = $_POST['phone'];
        $inviter_code = $_POST['inviter_code'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        // VALIDATIONS ___________________
        if($uname==""){
            echo json_encode(array('success' => 0, 'msg' => "User Name can not be empty")); die();
        }
        if($email==""){
            echo json_encode(array('success' => 0, 'msg' => "Email can not be empty")); die();
        }
        // if($uname==""){
        //     echo json_encode(array('success' => 0, 'msg' => "User Name Name can not be empty")); die();
        // }
     
        // VALIDATIONS ___________________


        $query_update = "UPDATE users
        SET uname = '".$uname."', email ='".$email."'
        , package ='".$package."', phone ='".$phone."'
        , inviter_code ='".$inviter_code."', description ='".$description."'
        , active ='".$status."'
        WHERE id = '".$user_id."'
        ";
            
        $run = mysqli_query($conn, $query_update);

        if(isset($run)){
            $_SESSION['loggedin_email'] = $email;
            $_SESSION['loggedin_name'] = $uname;
            echo json_encode(array('success'=>1, 'msg' => "Update Successfully ..!"));
        }else{
            echo json_encode(array('success'=>0, 'msg' => "Something went wrong, please try again later ..!"));
        }
    }

    if(isset($_POST['update-user-password-request'])){  /* This will be the hidden field for all conditions*/

        $password = $_POST['newPassword'];
        $c_password = $_POST['confirmPassword'];
        $user_id = $_POST['user_id'];
        $role = $_POST['role'];


        // VALIDATIONS ___________________
        if($c_password != $password){
            echo json_encode(array('success' => 0, 'msg' => "Paswword Doesn't match")); die();
        }
        if(strlen($password) < 8){
            echo json_encode(array('success' => 0, 'msg' => "Paswword must be 8 characters")); die();
        }
     
        // VALIDATIONS ___________________

        $query_update = "UPDATE users
        SET password = '".md5($password)."'
        WHERE id = '".$user_id."'
        ";

        $run = mysqli_query($conn, $query_update);

        if($run){
            echo json_encode(array('success'=>1, 'msg' => "Update Successfully ..!"));
        }else{
            echo json_encode(array('success'=>0, 'msg' => "Something went wrong, please try again later ..!"));
        }
        
    }


    // COMPANY FUNCTIONS ________________________________________________


     if(isset($_GET['company-publish-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['company_id'];
        $approved = $_GET['approved'];

        $query_update = "UPDATE company
            SET active = '".$approved."'
            WHERE id = '".$id."'
            ";
            // var_dump($query_update);
            // die();
        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/companies.php");
        }
            else{
            header("location: admin/companies.php");
        }
    }

    if(isset($_GET['company-delete-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['company_id'];

        $query_update = "UPDATE company
            SET deleted = '1'
            WHERE id = '".$id."'
            ";

        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/companies.php");
        }
            else{
            header("location: admin/companies.php");
        }
    }

    if(isset($_POST['add-company-request'])){  /* This will be the hidden field for all conditions*/

        $name = $_POST['name'];
        $description = $_POST['description'];
        $approved = $_POST['status'];

         // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "Title can not be empty")); die();
        }
        // if($description==""){
        //     echo json_encode(array('success' => 0, 'msg' => "description can not be empty")); die();
        // }
        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from company where name = '".$name."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Company title  ".$name." already Added..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query = "INSERT INTO company (
                name,
                description,
                active
            )
            VALUES (
                '".mysqli_real_escape_string($conn,$name)."',
                '".mysqli_real_escape_string($conn,$description)."',
                '".$approved."'
            )"; 
            
            $run = mysqli_query($conn, $query);

            $updated_ID = $conn->insert_id;

            $valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
            $path = "assets/images/company_images/";
            $total_images= count($_FILES['logo']['name']);
            $image_name_all = array();

            for($i=0; $i <= $total_images-1; $i++){

                $fileName2 = $_FILES['logo']['name'][$i];
                $fileSize2 = $_FILES['logo']['size'][$i];
                $filetmpName = $_FILES['logo']['tmp_name'][$i];
                $ext2 = explode("/",$_FILES['logo']['type'][$i]);



                if(in_array($ext2[1],$valid_file_formats)) {
                    if($fileSize2<(1502444*1502444)) {
                        $image_name = time().$i.".".$ext2[1];
                        if(move_uploaded_file($filetmpName, $path.$image_name)){
                            $image_name_all[] = $image_name;
                        }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo Upload failed..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo file size maximum 1 MB..!"));die();}
                }
                else
                    {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
            }
            $image_name_all = implode(",",$image_name_all);
            $sql2 = "UPDATE company SET logo='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
            $result2 = mysqli_query($conn, $sql2) or die("error to update image data");

            if(isset($run) && isset($result2)){
                $data = array('success' => 1, 'msg' => "Successfully Added !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }
    }

    if(isset($_POST['update-company-request']))  /* This will be the hidden field for all conditions*/
    {
        $id = $_POST['company_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $approved = $_POST['status'];


        // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "company Name can not be empty")); die();
        }
        // if($description==""){
        //     echo json_encode(array('success' => 0, 'msg' => "Description can not be empty")); die();
        // }
        // VALIDATIONS ___________________

        
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from company where name = '".$name."' AND id <> '".$id."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "company ".$name." already registered..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query_update = "UPDATE company
                SET name = '".$name."',
                description = '".mysqli_real_escape_string($conn,$description)."',
                active = '".$approved."',
                deleted = '0'
                WHERE id = '".$id."'
                ";
            $run = mysqli_query($conn, $query_update);

            $updated_ID = $id;

            $valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
            $path = "assets/images/company_images/";
            $total_images= count($_FILES['logo']['name']);
            $image_name_all = array();

            $imageSelected = strlen($_FILES['logo']['name'][0]);
            if($imageSelected > 0 ){
                for($i=0; $i <= $total_images-1; $i++){

                    $fileName2 = $_FILES['logo']['name'][$i];
                    $fileSize2 = $_FILES['logo']['size'][$i];
                    $filetmpName = $_FILES['logo']['tmp_name'][$i];
                    $ext2 = explode("/",$_FILES['logo']['type'][$i]);



                    if(in_array($ext2[1],$valid_file_formats)) {
                        if($fileSize2<(1502444*1502444)) {
                            $image_name = time().$i.".".$ext2[1];
                            if(move_uploaded_file($filetmpName, $path.$image_name)){
                                $image_name_all[] = $image_name;
                            }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Logo Upload failed..!"));die();}
                        }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Logo file size maximum 1 MB..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
                }
                $image_name_all = implode(",",$image_name_all);
                $sql2 = "UPDATE company SET logo='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
                $result2 = mysqli_query($conn, $sql2) or die("error to update image data");
            }


            if(isset($run)){
                $data = array('success' => 1, 'msg' => "Successfully Updated !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }

    }
    // COMPANY FUNCTIONS ________________________________________________



    // BRAND FUNCTIONS ________________________________________________

     if(isset($_GET['brand-publish-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['brand_id'];
        $approved = $_GET['approved'];

        $query_update = "UPDATE brand
            SET active = '".$approved."'
            WHERE id = '".$id."'
            ";
            // var_dump($query_update);
            // die();
        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/brands.php");
        }
            else{
            header("location: admin/brands.php");
        }
    }

    if(isset($_GET['brand-delete-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['brand_id'];

        $query_update = "UPDATE brand
            SET deleted = '1'
            WHERE id = '".$id."'
            ";

        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/brands.php");
        }
            else{
            header("location: admin/brands.php");
        }
    }

    if(isset($_POST['add-brand-request'])){  /* This will be the hidden field for all conditions*/

        $name = $_POST['name'];
        $company_id = $_POST['company_id'];
        $description = $_POST['description'];
        $approved = $_POST['status'];

         // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "brand can not be empty")); die();
        }
        if($company_id==""){
            echo json_encode(array('success' => 0, 'msg' => "company_id can not be empty")); die();
        }
        // if($description==""){
        //     echo json_encode(array('success' => 0, 'msg' => "description can not be empty")); die();
        // }
        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from brand where name = '".$name."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Brand title  ".$name." already Added..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query = "INSERT INTO brand (
                name,
                company_id,
                description,
                active
            )
            VALUES (
                '".mysqli_real_escape_string($conn,$name)."',
                '".mysqli_real_escape_string($conn,$company_id)."',
                '".mysqli_real_escape_string($conn,$description)."',
                '".$approved."'
            )"; 
            
            $run = mysqli_query($conn, $query);

            $updated_ID = $conn->insert_id;

            $valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
            $path = "assets/images/brand_images/";
            $total_images= count($_FILES['logo']['name']);
            $image_name_all = array();

            for($i=0; $i <= $total_images-1; $i++){

                $fileName2 = $_FILES['logo']['name'][$i];
                $fileSize2 = $_FILES['logo']['size'][$i];
                $filetmpName = $_FILES['logo']['tmp_name'][$i];
                $ext2 = explode("/",$_FILES['logo']['type'][$i]);



                if(in_array($ext2[1],$valid_file_formats)) {
                    if($fileSize2<(1502444*1502444)) {
                        $image_name = time().$i.".".$ext2[1];
                        if(move_uploaded_file($filetmpName, $path.$image_name)){
                            $image_name_all[] = $image_name;
                        }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo Upload failed..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo file size maximum 1 MB..!"));die();}
                }
                else
                    {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
            }
            $image_name_all = implode(",",$image_name_all);
            $sql2 = "UPDATE brand SET logo='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
            $result2 = mysqli_query($conn, $sql2) or die("error to update image data");

            if(isset($run) && isset($result2)){
                $data = array('success' => 1, 'msg' => "Successfully Added !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }
    }

    if(isset($_POST['update-brand-request']))  /* This will be the hidden field for all conditions*/
    {
        $id = $_POST['brand_id'];
        $name = $_POST['name'];
        $company_id = $_POST['company_id'];
        $description = $_POST['description'];
        $approved = $_POST['status'];


        // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "Brand can not be empty")); die();
        }
        if($company_id==""){
            echo json_encode(array('success' => 0, 'msg' => "Company can not be empty")); die();
        }
        // VALIDATIONS ___________________

        
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from brand where name = '".$name."' AND id <> '".$id."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Brand ".$name." already registered..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query_update = "UPDATE brand
                SET name = '".$name."',
                company_id = '".mysqli_real_escape_string($conn,$company_id)."',
                description = '".mysqli_real_escape_string($conn,$description)."',
                active = '".$approved."',
                deleted = '0'
                WHERE id = '".$id."'
                ";
            $run = mysqli_query($conn, $query_update);

            $updated_ID = $id;

            $valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
            $path = "assets/images/brand_images/";
            $total_images= count($_FILES['logo']['name']);
            $image_name_all = array();

            $imageSelected = strlen($_FILES['logo']['name'][0]);
            if($imageSelected > 0 ){
                for($i=0; $i <= $total_images-1; $i++){

                    $fileName2 = $_FILES['logo']['name'][$i];
                    $fileSize2 = $_FILES['logo']['size'][$i];
                    $filetmpName = $_FILES['logo']['tmp_name'][$i];
                    $ext2 = explode("/",$_FILES['logo']['type'][$i]);



                    if(in_array($ext2[1],$valid_file_formats)) {
                        if($fileSize2<(1502444*1502444)) {
                            $image_name = time().$i.".".$ext2[1];
                            if(move_uploaded_file($filetmpName, $path.$image_name)){
                                $image_name_all[] = $image_name;
                            }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Logo Upload failed..!"));die();}
                        }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Logo file size maximum 1 MB..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
                }
                $image_name_all = implode(",",$image_name_all);
                $sql2 = "UPDATE brand SET logo='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
                $result2 = mysqli_query($conn, $sql2) or die("error to update image data");
            }


            if(isset($run)){
                $data = array('success' => 1, 'msg' => "Successfully Updated !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }

    }
    // BRAND FUNCTIONS ________________________________________________


    // PRODUCT FUNCTIONS ________________________________________________

     if(isset($_GET['product-publish-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['product_id'];
        $approved = $_GET['approved'];

        $query_update = "UPDATE product
            SET active = '".$approved."'
            WHERE id = '".$id."'
            ";
            // var_dump($query_update);
            // die();
        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/products.php");
        }
            else{
            header("location: admin/products.php");
        }
    }

    if(isset($_GET['product-delete-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['product_id'];

        $query_update = "UPDATE product
            SET deleted = '1'
            WHERE id = '".$id."'
            ";

        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/products.php");
        }
            else{
            header("location: admin/products.php");
        }
    }

    if(isset($_POST['add-product-request'])){  /* This will be the hidden field for all conditions*/

        $name = $_POST['name'];
        $sku = $_POST['sku'];
        $barcode = $_POST['barcode'];
        $package = $_POST['package'];
        $brand = $_POST['brand'];
        $roi = $_POST['roi'];
        $roi_days = $_POST['roi_days'];
        $short_description = $_POST['short_description'];
        $long_description = $_POST['long_description'];
        $in_stock = $_POST['in_stock'];
        $cost = $_POST['cost'];
        $price = $_POST['price'];
        $approved = $_POST['status'];

         // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "Product can not be empty")); die();
        }
        if($barcode==""){
            echo json_encode(array('success' => 0, 'msg' => "Barcode can not be empty")); die();
        }
        // if($description==""){
        //     echo json_encode(array('success' => 0, 'msg' => "description can not be empty")); die();
        // }
        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from product where name = '".$name."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Product  ".$name." already Added..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query = "INSERT INTO product (
                name,
                sku,
                barcode,
                package,
                brand,
                roi,
                roi_days,
                short_description,
                long_description,
                cost,
                price,
                in_stock,
                active
            )
            VALUES (
                '".mysqli_real_escape_string($conn,$name)."',
                '".mysqli_real_escape_string($conn,$sku)."',
                '".mysqli_real_escape_string($conn,$barcode)."',
                '".mysqli_real_escape_string($conn,$package)."',
                '".mysqli_real_escape_string($conn,$brand)."',
                '".mysqli_real_escape_string($conn,$roi)."',
                '".mysqli_real_escape_string($conn,$roi_days)."',
                '".mysqli_real_escape_string($conn,$short_description)."',
                '".mysqli_real_escape_string($conn,$long_description)."',
                '".mysqli_real_escape_string($conn,$cost)."',
                '".mysqli_real_escape_string($conn,$price)."',
                '".mysqli_real_escape_string($conn,$in_stock)."',
                '".$approved."'
            )"; 
            
            $run = mysqli_query($conn, $query);

            $updated_ID = $conn->insert_id;

            $valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
            $path = "assets/images/product_images/";
            $total_images= count($_FILES['f_image']['name']);
            $image_name_all = array();

            for($i=0; $i <= $total_images-1; $i++){

                $fileName2 = $_FILES['f_image']['name'][$i];
                $fileSize2 = $_FILES['f_image']['size'][$i];
                $filetmpName = $_FILES['f_image']['tmp_name'][$i];
                $ext2 = explode("/",$_FILES['f_image']['type'][$i]);



                if(in_array($ext2[1],$valid_file_formats)) {
                    if($fileSize2<(1502444*1502444)) {
                        $image_name = time().$i.".".$ext2[1];
                        if(move_uploaded_file($filetmpName, $path.$image_name)){
                            $image_name_all[] = $image_name;
                        }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo Upload failed..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo file size maximum 1 MB..!"));die();}
                }
                else
                    {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
            }
            $image_name_all = implode(",",$image_name_all);
            $sql2 = "UPDATE product SET featured_image='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
            $result2 = mysqli_query($conn, $sql2) or die("error to update image data");

            $total_images2= count($_FILES['f_image']['name']);
            $image_name_all = array();

            for($i=0; $i <= $total_images2-1; $i++){

                $fileName2 = $_FILES['images']['name'][$i];
                $fileSize2 = $_FILES['images']['size'][$i];
                $filetmpName = $_FILES['images']['tmp_name'][$i];
                $ext2 = explode("/",$_FILES['images']['type'][$i]);



                if(in_array($ext2[1],$valid_file_formats)) {
                    if($fileSize2<(1502444*1502444)) {
                        $image_name = time().$i.".".$ext2[1];
                        if(move_uploaded_file($filetmpName, $path.$image_name)){
                            $image_name_all[] = $image_name;
                        }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo Upload failed..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Logo file size maximum 1 MB..!"));die();}
                }
                else
                    {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
            }
            $image_name_all = implode(",",$image_name_all);
            $sql3 = "UPDATE product SET images='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
            $reslt3 = mysqli_query($conn, $sql3) or die("error to update image data");


            if(isset($run) && isset($result2)){
                $data = array('success' => 1, 'msg' => "Successfully Added !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }
    }

    if(isset($_POST['update-product-request']))  /* This will be the hidden field for all conditions*/
    {
        $id = $_POST['product_id'];
  
        $name = $_POST['name'];
        $sku = $_POST['sku'];
        $barcode = $_POST['barcode'];
        $package = $_POST['package'];
        $brand = $_POST['brand'];
        $roi = $_POST['roi'];
        $roi_days = $_POST['roi_days'];
        $short_description = $_POST['short_description'];
        $long_description = $_POST['long_description'];
        $in_stock = $_POST['in_stock'];
        $cost = $_POST['cost'];
        $price = $_POST['price'];
        $approved = $_POST['status'];


        // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "Item Name can not be empty")); die();
        }
        if($barcode==""){
            echo json_encode(array('success' => 0, 'msg' => "Barcode can not be empty")); die();
        }
        // VALIDATIONS ___________________

        
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from product where name = '".$name."' AND id <> '".$id."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Product ".$name." already registered..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query_update = "UPDATE product
                SET name = '".$name."',
                sku = '".mysqli_real_escape_string($conn,$sku)."',
                barcode = '".mysqli_real_escape_string($conn,$barcode)."',
                package = '".mysqli_real_escape_string($conn,$package)."',
                brand = '".mysqli_real_escape_string($conn,$brand)."',
                roi = '".mysqli_real_escape_string($conn,$roi)."',
                roi_days = '".mysqli_real_escape_string($conn,$roi_days)."',
                short_description = '".mysqli_real_escape_string($conn,$short_description)."',
                long_description = '".mysqli_real_escape_string($conn,$long_description)."',
                in_stock = '".mysqli_real_escape_string($conn,$in_stock)."',
                cost = '".mysqli_real_escape_string($conn,$cost)."',
                price = '".mysqli_real_escape_string($conn,$price)."',

                active = '".$approved."',
                deleted = '0'

                WHERE id = '".$id."'
                ";
            $run = mysqli_query($conn, $query_update);

            $updated_ID = $id;

            $valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
            $path = "assets/images/product_images/";
            $total_images= count($_FILES['f_image']['name']);
            $total_images2= count($_FILES['f_image']['name']);
            $image_name_all = array();

            $imageSelected = strlen($_FILES['f_image']['name'][0]);
            if($imageSelected > 0 ){
                for($i=0; $i <= $total_images-1; $i++){

                    $fileName2 = $_FILES['f_image']['name'][$i];
                    $fileSize2 = $_FILES['f_image']['size'][$i];
                    $filetmpName = $_FILES['f_image']['tmp_name'][$i];
                    $ext2 = explode("/",$_FILES['f_image']['type'][$i]);



                    if(in_array($ext2[1],$valid_file_formats)) {
                        if($fileSize2<(1502444*1502444)) {
                            $image_name = time().$i.".".$ext2[1];
                            if(move_uploaded_file($filetmpName, $path.$image_name)){
                                $image_name_all[] = $image_name;
                            }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Featured Image Upload failed..!"));die();}
                        }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Featured Image file size maximum 1 MB..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
                }
                $image_name_all = implode(",",$image_name_all);
                $sql2 = "UPDATE product SET featured_image='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
                $result2 = mysqli_query($conn, $sql2) or die("error to update image data");
            }

            $imageSelected2 = strlen($_FILES['images']['name'][0]);
            if($imageSelected2 > 0 ){
                for($i=0; $i <= $total_images2-1; $i++){

                    $fileName2 = $_FILES['images']['name'][$i];
                    $fileSize2 = $_FILES['images']['size'][$i];
                    $filetmpName = $_FILES['images']['tmp_name'][$i];
                    $ext2 = explode("/",$_FILES['images']['type'][$i]);



                    if(in_array($ext2[1],$valid_file_formats)) {
                        if($fileSize2<(1502444*1502444)) {
                            $image_name = time().$i.".".$ext2[1];
                            if(move_uploaded_file($filetmpName, $path.$image_name)){
                                $image_name_all[] = $image_name;
                            }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Images Upload failed..!"));die();}
                        }
                        else
                            {echo json_encode(array('error'=>1, 'msg' => " Images file size maximum 1 MB..!"));die();}
                    }
                    else
                        {echo json_encode(array('error'=>1, 'msg' => " Invalid file format..!"));die();}
                }
                $image_name_all = implode(",",$image_name_all);
                $sql3 = "UPDATE product SET images='".$image_name_all."',location='".$path."' WHERE id='".$updated_ID."' ";
                $result3 = mysqli_query($conn, $sql3) or die("error to update image data");
            }


            if(isset($run)){
                $data = array('success' => 1, 'msg' => "Successfully Updated !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }

    }
    // PRODUCT FUNCTIONS ________________________________________________


// USER FUNCTIONS ________________________________________________

     if(isset($_GET['user-publish-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['user_id'];
        $approved = $_GET['approved'];

        $query_update = "UPDATE users
            SET active = '".$approved."'
            WHERE id = '".$id."'
            ";
            // var_dump($query_update);
            // die();
        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/users.php");
        }
            else{
            header("location: admin/users.php");
        }
    }

    if(isset($_GET['user-delete-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['user_id'];

        $query_update = "UPDATE users
            SET deleted = '1'
            WHERE id = '".$id."'
            ";

        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/users.php");
        }
            else{
            header("location: admin/users.php");
        }
    }

    if(isset($_POST['add-user-request'])){  /* This will be the hidden field for all conditions*/

        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $package = $_POST['package'];
        $phone = $_POST['phone'];
        $description = ' ';
        $inviter_code = $_POST['inviter_code'];
        $month_limit = $_POST['month_limit'];

        $password = $_POST['password'];
        $c_password = $_POST['c_password'];

        $role = $_POST['role'];

        $approved = $_POST['status'];

         // VALIDATIONS ___________________
        if($uname==""){
            echo json_encode(array('success' => 0, 'msg' => "Name can not be empty")); die();
        }
        if($email==""){
            echo json_encode(array('success' => 0, 'msg' => "Email can not be empty")); die();
        }
        if($password != $c_password){
            echo json_encode(array('success' => 0, 'msg' => "Password doen not match")); die();
        }
        // if($description==""){
        //     echo json_encode(array('success' => 0, 'msg' => "description can not be empty")); die();
        // }
        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from users where uname = '".$uname."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "User  ".$uname." already Added..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query = "INSERT INTO users (
                uname,
                email,
                package,
                phone,
                description,
                inviter_code,
                password,
                role,
                month_limit,
                active
            )
            VALUES (
                '".mysqli_real_escape_string($conn,$uname)."',
                '".mysqli_real_escape_string($conn,$email)."',
                '".mysqli_real_escape_string($conn,$package)."',
                '".mysqli_real_escape_string($conn,$phone)."',
                '".mysqli_real_escape_string($conn,$description)."',
                '".mysqli_real_escape_string($conn,$inviter_code)."',
                '".md5($password)."',
                '".mysqli_real_escape_string($conn,$role)."',
                '".mysqli_real_escape_string($conn,$month_limit)."',
                '".$approved."'
            )"; 
            
            $run = mysqli_query($conn, $query);


            if(isset($run)){
                $data = array('success' => 1, 'msg' => "Successfully Added !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }
    }

    if(isset($_POST['admin-update-user-request']))  /* This will be the hidden field for all conditions*/
    {
        $id = $_POST['user_id'];
  
        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $package = $_POST['package'];
        $phone = $_POST['phone'];
        $description = ' ';
        $inviter_code = $_POST['inviter_code'];
        $month_limit = $_POST['month_limit'];

        $password = $_POST['password'];
        $c_password = $_POST['c_password'];

        $role = $_POST['role'];

        $approved = $_POST['status'];

         // VALIDATIONS ___________________
        if($uname==""){
            echo json_encode(array('success' => 0, 'msg' => "Name can not be empty")); die();
        }
        if($email==""){
            echo json_encode(array('success' => 0, 'msg' => "Email can not be empty")); die();
        }
        // if($password != $c_password){
        //     echo json_encode(array('success' => 0, 'msg' => "Password doen not match")); die();
        // }
        // VALIDATIONS ___________________

        
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from users where uname = '".$uname."' AND id <> '".$id."' AND role = 2 ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "User name for this name ".$uname." already registered..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query_update = "UPDATE users
                SET uname = '".$uname."',
                email = '".mysqli_real_escape_string($conn,$email)."',
                package = '".mysqli_real_escape_string($conn,$package)."',
                phone = '".mysqli_real_escape_string($conn,$phone)."',
                description = '".mysqli_real_escape_string($conn,$description)."',
                inviter_code = '".mysqli_real_escape_string($conn,$inviter_code)."',
                month_limit = '".mysqli_real_escape_string($conn,$month_limit)."',
                active = '".$approved."',
                deleted = '0'

                WHERE id = '".$id."'
                ";
            $run = mysqli_query($conn, $query_update);


            if(isset($run)){
                $data = array('success' => 1, 'msg' => "Successfully Updated !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }

    }
    // USER FUNCTIONS ________________________________________________



    // PACKAGE FUNCTIONS ________________________________________________

     if(isset($_GET['package-publish-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['package_id'];
        $approved = $_GET['approved'];

        $query_update = "UPDATE package
            SET active = '".$approved."'
            WHERE id = '".$id."'
            ";
            // var_dump($query_update);
            // die();
        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/packages.php");
        }
            else{
            header("location: admin/packages.php");
        }
    }

    if(isset($_GET['package-delete-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['package_id'];

        $query_update = "UPDATE package
            SET deleted = '1'
            WHERE id = '".$id."'
            ";

        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/packages.php");
        }
            else{
            header("location: admin/packages.php");
        }
    }

    if(isset($_POST['add-package-request'])){  /* This will be the hidden field for all conditions*/

        $name = $_POST['name'];
        $price = $_POST['price'];
        $referal_reward = $_POST['referal_reward'];
        $withdraw_limit = $_POST['withdraw_limit'];
        $description = $_POST['description'];
        $short_description = $_POST['short_description'];

        $sorting = $_POST['sorting'];
        $approved = $_POST['status'];

         // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "Package Name can not be empty")); die();
        }
        if($price==""){
            echo json_encode(array('success' => 0, 'msg' => "Price can not be empty")); die();
        }
         if($withdraw_limit==""){
            echo json_encode(array('success' => 0, 'msg' => "Limit can not be empty")); die();
        }
        // if($description==""){
        //     echo json_encode(array('success' => 0, 'msg' => "description can not be empty")); die();
        // }
        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from package where name = '".$name."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Package  ".$name." already Added..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query = "INSERT INTO package (
                name,
                price,
                referal_reward,
                withdraw_limit,
                description,
                short_description,
                sorting
                active
            )
            VALUES (
                '".mysqli_real_escape_string($conn,$name)."',
                '".mysqli_real_escape_string($conn,$price)."',
                '".mysqli_real_escape_string($conn,$referal_reward)."',
                '".mysqli_real_escape_string($conn,$withdraw_limit)."',
                '".mysqli_real_escape_string($conn,$description)."',
                '".mysqli_real_escape_string($conn,$short_description)."',
                '".$sorting."'
                '".$approved."'
            )"; 
            
            $run = mysqli_query($conn, $query);


            if(isset($run)){
                $data = array('success' => 1, 'msg' => "Successfully Added !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }
    }

    if(isset($_POST['update-package-request']))  /* This will be the hidden field for all conditions*/
    {
        $id = $_POST['package_id'];
  
        $name = $_POST['name'];
        $price = $_POST['price'];
        $referal_reward = $_POST['referal_reward'];
        $withdraw_limit = $_POST['withdraw_limit'];
        $description = $_POST['description'];
        $short_description = $_POST['short_description'];

        $sorting = $_POST['sorting'];
        $approved = $_POST['status'];

         // VALIDATIONS ___________________
        if($name==""){
            echo json_encode(array('success' => 0, 'msg' => "Package Name can not be empty")); die();
        }
        if($price==""){
            echo json_encode(array('success' => 0, 'msg' => "Price can not be empty")); die();
        }
         if($withdraw_limit==""){
            echo json_encode(array('success' => 0, 'msg' => "Limit can not be empty")); die();
        }
        // VALIDATIONS ___________________

        
        // DUPLICATE CHECK _________________
        $queryMatch = "SELECT * from package where name = '".$name."' AND id <> '".$id."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        if($dataMatch->num_rows > 0)
        {
            $errorMsg = "Package for this name ".$uname." already registered..";
            $data = array('success' => 0, 'msg' => $errorMsg);
            echo json_encode($data);
        }else{

            $query_update = "UPDATE package
                SET name = '".$name."',
                price = '".mysqli_real_escape_string($conn,$price)."',
                referal_reward = '".mysqli_real_escape_string($conn,$referal_reward)."',
                withdraw_limit = '".mysqli_real_escape_string($conn,$withdraw_limit)."',
                description = '".mysqli_real_escape_string($conn,$description)."',
                short_description = '".mysqli_real_escape_string($conn,$short_description)."',
                sorting = '".$sorting."',
                active = '".$approved."',
                deleted = '0'

                WHERE id = '".$id."'
                ";
            $run = mysqli_query($conn, $query_update);


            if(isset($run)){
                $data = array('success' => 1, 'msg' => "Successfully Updated !");
                echo json_encode($data);
            }else{
                $data = array('success' => 0, 'msg' => "Something went Wrong !");
                echo json_encode($data);
            }
        }

    }
    // PACKAGE FUNCTIONS ________________________________________________
    
    
    // SUBSCRIPTION FUNCTIONS ________________________________________________
    if(isset($_POST['subscription-request'])){  /* This will be the hidden field for all conditions*/


        $user_id = $_POST['user_id'];
        $package_id = $_POST['package_id'];
        $price = $_POST['price'];
        $billings_name = $_POST['billings_name'];
        $billings_email = $_POST['billings_email'];
        $billings_country = $_POST['billings_country'];
        $billings_zip = $_POST['billings_zip'];
        $month_limit = $_POST['month_limit'];


         // VALIDATIONS ___________________
        if($billings_name==""){
            echo json_encode(array('success' => 0, 'msg' => "billings_name Name can not be empty")); die();
        }
        if($billings_email==""){
            echo json_encode(array('success' => 0, 'msg' => "billings_email can not be empty")); die();
        }

        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        

        $query = "INSERT INTO subscription (
            user_id,
            package_id,
            price,
            billings_name,
            billings_email,
            billings_country,
            billings_zip,
            month_limit,
            active
        )
        VALUES (
            '".mysqli_real_escape_string($conn,$user_id)."',
            '".mysqli_real_escape_string($conn,$package_id)."',
            '".mysqli_real_escape_string($conn,$price)."',
            '".mysqli_real_escape_string($conn,$billings_name)."',
            '".mysqli_real_escape_string($conn,$billings_email)."',
            '".mysqli_real_escape_string($conn,$billings_country)."',
            '".mysqli_real_escape_string($conn,$billings_zip)."',
            '".mysqli_real_escape_string($conn,$month_limit)."',
            '0'
        )"; 
        
        $run = mysqli_query($conn, $query);
        $ord_id = $conn->insert_id;


        


        //  MOVE this to after payment _______
        // $query_update = "UPDATE users
        //     SET package = '".$package_id."'
        //     WHERE id = '".$user_id."'
        //         ";
        // $run2 = mysqli_query($conn, $query_update);
        //  MOVE this to after payment _______
        


        if(isset($run)){
            $data = array('success' => 1, 'msg' => "Successfully Added !", 'ord_id' => $ord_id);
            echo json_encode($data);
        }else{
            $data = array('success' => 0, 'msg' => "Something went Wrong !");
            echo json_encode($data);
        }
    }

     if(isset($_POST['update-subscription-request'])){  /* This will be the hidden field for all conditions*/

        $order_id = $_POST['order_id'];
        $user_id = $_POST['user_id'];
        $price = $_POST['price'];
        $month_limit = $_POST['month_limit'];

        $query_update = "UPDATE subscription
            SET price = '".$price."',
            month_limit = '".$month_limit."'
            WHERE id = '".$order_id."'
                ";
        $run = mysqli_query($conn, $query_update);


        if(isset($run)){
            $data = array('success' => 1, 'msg' => "Order Upated Successfully, Make payment to proceed further !");
            echo json_encode($data);
        }else{
            $data = array('success' => 0, 'msg' => "Something went Wrong !");
            echo json_encode($data);
        }
    }
    // SUBSCRIPTION FUNCTIONS ________________________________________________

    // USER SUBSCRIPTION UPGRADE FUNCTIONS ________________________________________________
    if(isset($_POST['user-upgrade-package']))  /* This will be the hidden field for all conditions*/
    {
        $subs_id = $_POST['subs_id'];
        $user_id = $_POST['user_id'];

        $query_user_update = "UPDATE users
            SET package = '".$subs_id."'
            WHERE id = '".$user_id."'
            ";
        $run = mysqli_query($conn, $query_user_update);

        $query_package_update = "UPDATE subscription
            SET package_id = '".$subs_id."'
            WHERE user_id = '".$user_id."'
            ";
        $run2 = mysqli_query($conn, $query_package_update);


        if(isset($run) && isset($run2)){
            echo 1;
        }else{
            echo 0;
            }

    }
    // USER SUBSCRIPTION UPGRADE FUNCTIONS ________________________________________________


    // WITHDRAW FUNCTIONS ________________________________________________
    if(isset($_POST['add-withdraw-request'])){  /* This will be the hidden field for all conditions*/


        $user_id = $_POST['user_id'];
        $account_name = $_POST['acc_name'];
        $account_type = $_POST['acc_type'];
        $bank_name = $_POST['bank_name'];
        $account_number = $_POST['acc_no'];
        $amount = $_POST['amount'];

        
         // VALIDATIONS ___________________
        if($account_name==""){
            echo json_encode(array('success' => 0, 'msg' => "acc_name Name can not be empty")); die();
        }
        if($account_type==""){
            echo json_encode(array('success' => 0, 'msg' => "acc_type can not be empty")); die();
        }
        if($bank_name==""){
            echo json_encode(array('success' => 0, 'msg' => "bank_name can not be empty")); die();
        }
        if($account_number==""){
            echo json_encode(array('success' => 0, 'msg' => "account_number can not be empty")); die();
        }
        if($amount==""){
            echo json_encode(array('success' => 0, 'msg' => "amount can not be empty")); die();
        }

        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        

        $query = "INSERT INTO withdraw (
            user_id,
            account_name,
            account_type,
            bank_name,
            account_number,
            amount,
            active
        )
        VALUES (
            '".mysqli_real_escape_string($conn,$user_id)."',
            '".mysqli_real_escape_string($conn,$account_name)."',
            '".mysqli_real_escape_string($conn,$account_type)."',
            '".mysqli_real_escape_string($conn,$bank_name)."',
            '".mysqli_real_escape_string($conn,$account_number)."',
            '".mysqli_real_escape_string($conn,$amount)."',
            '1'
        )"; 
        
        $run = mysqli_query($conn, $query);


        if(isset($run)){
            $data = array('success' => 1, 'msg' => "Successfully Added !");
            echo json_encode($data);
        }else{
            $data = array('success' => 0, 'msg' => "Something went Wrong !");
            echo json_encode($data);
        }
    }

    if(isset($_GET['withdraw-publish-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['withdraw_id'];
        $approved = $_GET['approved'];

        $query_update = "UPDATE withdraw
            SET active = '".$approved."'
            WHERE id = '".$id."'
            ";
            // var_dump($query_update);
            // die();
        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/withdraw_request.php");
        }
            else{
            header("location: admin/withdraw_request.php");
        }
    }

    if(isset($_GET['withdraw-delete-request'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['withdraw_id'];

        $query_update = "UPDATE withdraw
            SET deleted = '1'
            WHERE id = '".$id."'
            ";

        $run = mysqli_query($conn, $query_update);

        if($run){
            header("location: admin/withdraw_request.php");
        }
            else{
            header("location: admin/withdraw_request.php");
        }
    }
    // WITHDRAW FUNCTIONS ________________________________________________

    // CURRENCY FUNCTIONS ________________________________________________
    if(isset($_GET['currency-changes-request'])){  /* This will be the hidden field for all conditions*/

        $currency = $_GET['currency'];
        $user_id = $_GET['user_id'];

        $queryMatch = "SELECT * from currency where user_id = '".$user_id."' ";
        $dataMatch = mysqli_query($conn, $queryMatch);
        
        if($dataMatch->num_rows > 0)
        {
            $query_update = "UPDATE currency
                SET currency = '".$currency."',active = '1'
                WHERE user_id = '".$user_id."'
                ";
            $run = mysqli_query($conn, $query_update);

        }else{
            $query = "INSERT INTO currency (
            currency,
            user_id,
            active
            )
            VALUES (
                '".mysqli_real_escape_string($conn,$currency)."',
                '".mysqli_real_escape_string($conn,$user_id)."',
                '1'
            )"; 
            $run = mysqli_query($conn, $query);


        }


        if($run){
            $previous_url = $_SERVER['HTTP_REFERER'];
            header("location: $previous_url");
        }
            else{
            header("location: admin/index.php");
        }
    }
    // CURRENCY FUNCTIONS ________________________________________________

    // CSV IMPORT PRODUCT FUNCTIONS ________________________________________________
    if(isset($_POST['product-csv-request'])){  /* This will be the hidden field for all conditions*/
        if($_FILES['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name'])) {
        // Get file details
        $file_tmp = $_FILES['file']['tmp_name'];
        
        // Read CSV file
        if (($handle = fopen($file_tmp, "r")) !== FALSE) {
            // Assuming CSV format: name, sku, barcode, package, brand, roi, roi_days, short_description, long_description, in_stock, cost, price, f_image, images
            $i=1;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($i<>1){

                    $name = $data[0];
                    $sku = $data[1];
                    $barcode = $data[2];
                    $package = $data[3];
                    $brand = $data[4];
                    $roi = $data[5];
                    $roi_days = $data[6];
                    $short_description = $data[7];
                    $long_description = $data[8];
                    $in_stock = $data[9];
                    $cost = $data[10];
                    $price = $data[11];
                    $featured_image = $data[12]; // File name of the single JPG image
                    $images = $data[13]; // Comma-separated list of bulk image file names
                    $location = $data[14]; 
                    $active = $data[15]; 
                    

                    $upload_dir = 'assets/images/product_images/';
                              
                    // Download the featured image and save it to a specific directory (example: 'uploads/')
                    $f_image_filename = basename($featured_image); // Extract filename from URL
                    download_image($featured_image, $upload_dir . $f_image_filename);
                    
                    // Download and save bulk images (assuming they are in CSV as comma-separated URLs)
                    $bulk_images_urls = explode(',', $images);
                    $image_filenames = array();
                    foreach ($bulk_images_urls as $image) {
                        $image_filename = basename($image); // Extract filename from URL
                        download_image($image, $upload_dir . $image_filename);
                        $image_filenames[] = $image_filename;
                    }
                    $image_filenames = implode(',', $image_filenames);


                    // Example: Insert data into your database (adjust according to your database schema)
                    $pdo = new PDO('mysql:host=localhost;dbname=bestamzd_service_app', 'bestamzd_service_app', 'p7AAclIT(;GM');
                    $stmt = $pdo->prepare("INSERT INTO product (name, sku, barcode, package, brand, roi, roi_days, short_description, long_description, in_stock, cost, price, featured_image, images, location, active) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $sku, $barcode, $package, $brand, $roi, $roi_days, $short_description, $long_description, $in_stock, $cost, $price, $f_image_filename, $image_filenames, $location, $active]);
                    
                    // Move uploaded images to a specific directory (example: 'uploads/')
                 

                }
                $i++;
            }
            fclose($handle);
            $data = array('success' => 1, 'msg' => "Product Importasd Successfully !");
            echo json_encode($data);
        } else {
            $data = array('success' => 0, 'msg' => "database issue");
            echo json_encode($data);
        }
        } else {
                $data = array('success' => 0, 'msg' => "Error uploading file csv");
                echo json_encode($data);
        }
    }

    // CSV IMPORT PRODUCT FUNCTIONS ________________________________________________



    // ADD ORDER FUNCTIONS ________________________________________________
    if(isset($_POST['add-order-request'])){  /* This will be the hidden field for all conditions*/


        $user_id = $_POST['user_id'];
        $package_id = $_POST['package_id'];
        $product_id = $_POST['product_id'];
        $type = $_POST['type'];
        $qty = $_POST['qty'];
        $price = $_POST['price'];
        $billings_name = $_POST['billings_name'];
        $billings_email = $_POST['billings_email'];
        $billings_country = $_POST['billings_country'];
        $billings_zip = $_POST['billings_zip'];


         // VALIDATIONS ___________________
        if($billings_name==""){
            echo json_encode(array('success' => 0, 'msg' => "billings_name Name can not be empty")); die();
        }
        if($billings_email==""){
            echo json_encode(array('success' => 0, 'msg' => "billings_email can not be empty")); die();
        }

        // VALIDATIONS ___________________

                
        // DUPLICATE CHECK _________________
        

        $query = "INSERT INTO orders (
            user_id,
            package_id,
            product_id,
            type,
            qty,
            price,
            billings_name,
            billings_email,
            billings_country,
            billings_zip,
            active
        )
        VALUES (
            '".mysqli_real_escape_string($conn,$user_id)."',
            '".mysqli_real_escape_string($conn,$package_id)."',
            '".mysqli_real_escape_string($conn,$product_id)."',
            '".mysqli_real_escape_string($conn,$type)."',
            '".mysqli_real_escape_string($conn,$qty)."',
            '".mysqli_real_escape_string($conn,$price)."',
            '".mysqli_real_escape_string($conn,$billings_name)."',
            '".mysqli_real_escape_string($conn,$billings_email)."',
            '".mysqli_real_escape_string($conn,$billings_country)."',
            '".mysqli_real_escape_string($conn,$billings_zip)."',
            '1'
        )"; 
        
        $run = mysqli_query($conn, $query);
        $ord_id = $conn->insert_id;


        if(isset($run)){
            $data = array('success' => 1, 'msg' => "Successfully Added !", 'ord_id' => $ord_id);
            echo json_encode($data);
        }else{
            $data = array('success' => 0, 'msg' => "Something went Wrong !");
            echo json_encode($data);
        }
    }
    // ADD ORDER FUNCTIONS ________________________________________________

    // EMAIL VERIFICATION FUNCTIONS ________________________________________________

    if(isset($_GET['user_verification_id'])){  /* This will be the hidden field for all conditions*/

        $id = $_GET['user_verification_id'];
        

        $query_update = "UPDATE users
            SET verified = '1'
            WHERE id = '".$id."'
            ";
        $run = mysqli_query($conn, $query_update);

        if($run){
            echo "<h3>EMAIL VERIFIED</h3>";
        }
            else{
            echo "<h3>EMAIL NOT VERIFIED</h3>";
        }
    }

    // EMAIL VERIFICATION FUNCTIONS ________________________________________________



    // CSV EXPORT PRODUCT FUNCTIONS ________________________________________________
    if(isset($_POST['product-csv-export-request'])){  /* This will be the hidden field for all conditions */
        $run =1;
        $get_all_products = get_all_products_for_csv();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=products.csv');
        $filename = 'products.csv';
        $output = fopen($filename, 'w');
        // $output = fopen('php://output', 'w');

        fputcsv($output, array('name', 'sku', 'barcode', 'package', 'brand', 'roi', 'roi_days', 'short_description', 'long_description', 'in_stock', 'cost', 'price', 'featured_image', 'images', 'location', 'active'));


        // Write the data rows

        if ($get_all_products->num_rows > 0) {
            while($row = $get_all_products->fetch_assoc()) {
                fputcsv($output, $row);
            }
        }

        fclose($output);
        $conn->close();


        if(isset($run)){
            $data = array('success' => 1, 'msg' => "Successfully Added !");
            echo json_encode($data);
        }else{
            $data = array('success' => 0, 'msg' => "Something went Wrong !");
            echo json_encode($data);
        }

    }

    // CSV EXPORT PRODUCT FUNCTIONS ________________________________________________



    // SEND REFERRAL FUNCTIONS ________________________________________________
    if(isset($_POST['send-referral-email-request'])){  /* This will be the hidden field for all conditions*/

        $data = array();

        $data['user_id'] = $_POST['user_id'];
        $data['user_email'] = $_POST['user_email'];
        $data['email'] = $_POST['email'];

        $referralCode = $_POST['user_id'];
        $baseURL = "http://seller.egrocify.com/user/register.php";

        $referralLink = $baseURL."?inviter=".urlencode(encryptStringShort($referralCode,32));

        $data['referralLink'] = $referralLink;


        $send = send_email_referral_link($data);

       $send = 1;


        if($send){
            $data = array('success' => 1, 'msg' => "Referral Link Sent Successfully !", 'ref' => $referralLink);
            echo json_encode($data);
        }
        else{
            $data = array('success' => 0, 'msg' => "Something went Wrong !", 'ref' => $referralLink);
            echo json_encode($data);
        }
    }
    // SEND REFERRAL FUNCTIONS ________________________________________________

?>