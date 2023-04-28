<?php 
session_start();
unset($_SESSION['username']);
unset($_SESSION["akmid"]);
$name = $_SESSION['username'];
$akmid = $_SESSION["akmid"];
if(!$name && !$akmid){
    header("location:index.php");
}
?>