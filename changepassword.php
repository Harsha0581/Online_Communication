<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Change password</title>
        <link rel="StyleSheet" href="IncludeFiles/D.css">
        <?php 
            include "IncludeFiles/function.php";
            include "IncludeFiles/links1.php";
            include "connection/ServerConnection.php"
        ?>
    </head>
    <body  class='maindiv_cgp'>
        <div>
            <p>back</p>
        </div>
        <div id='cp_main_div'>
            <h4 class='cg_header2'>Change password</h4>
            <form action="" method='post' id='form'>
                <div class="form-grop">
                    <label for="Otp" id='label'>Otp</label>
                    <input type="number" name="Otp" id='otp_text' placeholder="Enter otp" class='form-control'>
                </div>
                <div class="form-grop">
                    <label for='npass' id='label'>New password</label>
                    <input type="text" name = "npass" id='npass' placeholder="new password"  class='form-control'>
                </div>
                <div class="form-grop">
                    <label for="cpass" id='label'>Conform password</label>
                    <input type="password" name='cpass' id='cpass' placeholder="Conform password"  class='form-control'>
                </div>
                <div class="button">
                    <input type="submit" value='Change password' name='change_password' id='changePass' class='btn btn-default'>
                </div>
            </form>
            <span id='cg_countdown'></span>
        </div>
        <script>
        $(document).ready(function(){
            $("#form").submit(function(event){
                event.preventDefault();
                $.post("function.php",{name:name},function(data){

               });
            });
                var count=0;
                var time = setInterval(()=>{
                count++;
                if(count==10){
                    clearInterval(time);
                }
                var counttag = document.querySelector("#cg_countdown");
                counttag.innerHTML=count;
            },1000);
        });    
        window.onload=function(event){
            $(document).ready(function(event){
                event.preventDefault();
            });
        }
        </script>
    </body>
</html>