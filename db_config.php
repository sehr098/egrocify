<?php

 $hostName = "localhost";
 $userName = "root";
 $password = "";
 $dbName = "bestamzd_service_app";
 $conn= new mysqli($hostName,$userName,$password,$dbName);
 if($conn){
    // echo "connected";	
 }else{
    // echo "not connected";
 }
 
 ?>