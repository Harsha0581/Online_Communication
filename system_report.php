<?php 
    include "connection/ServerConnection.php";
    session_start();
    $username = $_SESSION["username"];
    $stm = "SELECT * FROM newusers WHERE  username = '$username'";
    $res = mysqli_query($server_connection, $stm);
    $row = mysqli_fetch_assoc($res);
    //updating the systemuser erroe;
    $stm1 = "UPDATE  newusers SET system_report = $row[system_report]+1 WHERE username ='$username'";
    $res1= mysqli_query($server_connection, $stm1);
   header("location:userlogout.php");
?>