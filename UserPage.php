<?php 
    session_start();
    $name =$_SESSION["username"];
    if(!$name){
        header("location:index.php");
    }else{
       
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php  include "IncludeFiles/links1.php";?>
        <link rel="stylesheet" href="IncludeFiles/links2.css">
        <title><?php echo $name; ?> Userpage</title>
        <?php include  "IncludeFiles/links1.php";?>
    </head>
    <body>
        <?php 
            include "includeFiles/userheader1.php"; 
        ?>
        <script>
            $(document).ready(function(){
                $(".notifications").show(1000);
            });
            function cancal(value){
                document.getElementById(value).style='display:none';
            }
        </script>
       <div class='row'>
            <div class='col-sm-12'>
                <?php 
                   $notifications = new notification($name);
                   $notifications->notification_process();
                ?>
            </div>
       </div>
    </body>
</html> 