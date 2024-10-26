<?php

session_start();
if(isset($_SESSION['loggedin_id'])){ session_destroy();}
header("location:login.php");

?>