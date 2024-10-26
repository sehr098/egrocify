<?php

 $hostName = "localhost";
 $userName = "bestamzd_service_app";
 $password = "p7AAclIT(;GM";
 $dbName = "bestamzd_service_app";
 $conn= new mysqli($hostName,$userName,$password,$dbName);
 if($conn){
    // echo "connected";	
 }else{
    // echo "not connected";
 }
 
 ?>