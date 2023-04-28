<?php 
    error_reporting(0);
?>
<div class="row userheader">
    <div class="col-sm-12">
        <div class="jamboton">
            <h1 class="userheader" style="padding:0px 0px 0px 70px;float:left">Online Chatting</h1>
        </div>
    </div>
</div>
<div class="row navbar"> 
    <div class="col-sm-12">
        <?php

            include "connection/ServerConnection.php";

            $navbar_stm = "SELECT *FROM navbar";

            $navbar_result = mysqli_query($server_connection, $navbar_stm);

            while( $navbar_row = mysqli_fetch_assoc($navbar_result)){

                echo "<a href='$navbar_row[navlink]' style='padding:0px 30px 0px 0px'>$navbar_row[navname]</a>";
            }
        ?>
    </div>
</div>