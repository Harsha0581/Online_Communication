
<?php error_reporting();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php 
        include "IncludeFiles/function.php";
        include "IncludeFiles/links1.php";
        include "connection/ServerConnection.php"
    ?>
    <link href='IncludeFiles/D.css' rel="StyleSheet">
</head>
<body id='akmbody'>
    <?php 
        session_start();
        $name = @$_SESSION["username"];
        function akmfunction(){
            if(@$_SESSION["username"]){
                return 1;
            }else{
                return 0;
            }
        }
        if(akmfunction() == 1){
            $stm = "SELECT * FROM newusers WHERE username = '$name'";
            $res = mysqli_query($server_connection, $stm);
            $row = mysqli_fetch_assoc($res);
            $count = mysqli_num_rows($res);

            $stm1 = "SELECT * FROM akmaccounts WHERE username = '$name'";
            $res1 = mysqli_query($server_connection, $stm1);
            $row1 = mysqli_fetch_assoc($res1);

            if($row["akmid_password"]=="" && $row1["password"]==""){
                ?>
                    <h2 id="akmhidder">SET AKM PASSWORD</h2>
                    <div id="akm_maindiv">
                        <div class='row'>
                            <div class="col-sm-4">
                                <form action="" method='post'>
                                    <div class='form-group'>
                                        <label for="username">AKm id:</label>
                                        <input type="text" name='akmid' id='akmusername' placeholder="Username" class='form-control' onfocusout="validateusername()">
                                    </div>
                                    <div class='form-group'>
                                        <label for="password">Account Password:</label>
                                        <input type="password" name='account_password' id='akmAccount_password' placeholder="Account password" class='form-control' onfocusout="newpassword()">
                                    </div>
                                    <div class='form-grop'>
                                        <label for="password">Set AkmID password:</label>
                                        <input type="password" name='akmidpassword' id='akmidpassword', placeholder='Set AkmID Password' class='form-control'>
                                    </div>
                                    <div class='form-group' style="margin-top:10px">
                                        <button id='akmlogin23' name='akmchangelogin' class='btn btn-default' style="display:none;margin-top:10px">UPDATE</button>
                                    </div>
                                </form>
                                <span id='spanakm'></span>
                            </div>
                        </div>
                    </div>
                    <script>
                        const akmid = document.querySelector("#akmusername");
                        const npass = document.querySelector("#akmAccount_password");
                        const cpass = document.querySelector("#akmidpassword");
                        const btn = document.querySelector("#akmlogin23");
                        const span = document.querySelector("#spanakm");
                        var count =0;
                        function validateusername(){
                            var usernamelength = akmid.value;
                            if(usernamelength.length<20){
                                span.innerHTML="Enter Valid Akmid";
                                span.style="color:crimson";
                            }else{
                                const domainname_new= "@akm.online_message";
                                var inputdominname_new = akmid.value;
                                const finaldomin_new = inputdominname_new.substr(-19,19);
                                if(finaldomin_new != domainname_new){
                                    span.innerHTML ="Enter the currect akmid";
                                    span.style="color:crimson";
                                    npass.readOnly=true;
                                    cpass.readOnly=true;
                                }else{
                                    npass.readOnly=false;
                                    cpass.readOnly=false;
                                    span.innerHTML ="";
                                    btn.style = "display:block";
                                }
                            }
                        }
                        function newpassword(){
                            newpass = npass.value;
                            if(newpass == ""){
                                cpass.readOnly = true;
                                btn.style = "display:none";
                                span.innerHTML ="The password filed empty..";
                                span.style = "color:crimson";
                            }else{
                                btn.style = "display:block";
                                if(newpass.length<4){
                                    cpass.readOnly=true;
                                    btn.style = "display:none";
                                    span.innerHTML = "The password length must above 3 letters";
                                    span.style="color:crimson";
                                }else{
                                    cpass.readOnly=false;
                                    btn.style = "display:block";
                                    span.innerHTML = "";
                                }
                            }
                        }
                    </script>
                <?php
                if(isset($_POST["akmchangelogin"])){
                    $akmid  = $_POST["akmid"];
                    $account_pass = $_POST["account_password"];
                    $akm_pass = $_POST["akmidpassword"];
                    $akmid = mysqli_real_escape_string($server_connection, $akmid);
                    $account_pass = mysqli_real_escape_string($server_connection,$account_pass);
                    $akm_pass = mysqli_real_escape_string($server_connection,$akm_pass);
                    if($akmid =="" || $account_pass =="" || $akm_pass ==""){
                        ?>
                            <script>
                                span.innerHTML = "The fildes are empty";
                                span.style ="color:crimson";
                            </script>
                        <?php
                    }else{
                        if($_SESSION["akmid"] == $akmid){
                            $update = new akmpassword_update($name, $akm_pass,$account_pass, $akmid);
                            $update->password_update();
                        }else{
                            ?>
                                <script>
                                    span.innerHTML = "This is not your akmid";
                                    span.style ="color:crimson";
                                    btn.style = "block";
                                </script>
                            <?php
                        }
                    }
                }
            }else{
                header("location:akm_mainPage.php");
            }
        }else{
    ?>   
        <h2 id='akmhidder'>AKM_LOGIN</h2>
        <div id='akm_maindiv'>
            <h3>Akm login</h3>
            <div class='row'>
                <div class='col-sm-4'>
                    <form id='akmformlogin' method='post'>
                        <div class='form-group'>
                            <label for="akmid">AKm id:</label>
                            <input type="text" name='akmid' id='akmid' placeholder="AkmID" class='form-control' onfocusout="validation()">
                        </div>
                        <div class='form-grop'>
                            <label for="password">AkmID password:</label>
                            <input type="password" name='akmidpassword' id='akmidpassword' placeholder='akmID Password' class='form-control' onfocusout="validpasssword()" 
                                onfocus="onfocusfunction()">
                        </div>
                        <div class='form-group'>
                            <button id='akmlogin' name='akmlogin' class='btn btn-default'>Login</button>
                        </div>
                    </form>
                    <span id='akmpassword'></span>
                    <a href="login.php" class='akmchangepassword' style='float:right'>Generate password</a>
                </div>
            </div>
        </div>
    <?php 
        }
    ?>
    <script>
        if(window.history.replaceState){
            window.history.replaceState(null, null, window.location.href);
        }
        const x = document.querySelector("#akmid");
        const y = document.querySelector("#akmpassword");
        const z =  document.querySelector("#akm_maindiv");
        const a = document.querySelector("#akmidpassword");
        const button = document.querySelector("#akmlogin");
        function onfocusfunction(){
            button.style="display:block";
        }
        function validation(){
            if(x.value==""){
                x.style='background-color:red';
                y.style ="color:crimson";
                z.style="border:solid red";
                y.innerHTML ="Empty Field";
                button.style='display:none';
                a.readOnly = true;
            }else{
                z.style="border-radius:20%,border-left:solid rgb(218, 151, 151),border-right: solid  rgb(121, 206, 173),border-top:solid white";
                x.style="background-color: rgb(121, 206, 173)";
                var txt = x.value;
                if(txt.length < 3){
                    button.style="display:none";
                    y.innerHTML ="Enter the currect akmid";
                    y.style="color:red";
                    a.readOnly = true;
                }else{
                    button,style="display:block";
                    const domainname = "@akm.online_message";
                    var inputdominname = x.value;
                    const finaldomin = inputdominname.substr(-19,19);
                    if(domainname != finaldomin){
                        x.style ="background-color:crimson";
                        button.style='display:none';
                        y.innerHTML="Incurrect akmId";
                        y.style='color:crimson';
                        a.readOnly = true;
                    }else{
                        button.style='display:block';
                        x.style="background-color: rgb(121, 206, 173)";
                        a.readOnly = false;
                        y.style='color:crimson';
                    }
                }
            }
        }
        function validpasssword(){
            if(a.value==""){
                a.style='background-color:red';
                y.style ="color:crimson";
                z.style="border:solid red";
                y.innerHTML ="Empty Field";
                button.style='display:none';
                x.readOnly = false;
            }else{
                z.style="border-radius:20%,border-left:solid rgb(218, 151, 151),border-right: solid  rgb(121, 206, 173),border-top:solid white";
                a.style="background-color: rgb(121, 206, 173)";
                const pass = a.value;
                if(pass.length < 3){
                    button.style="display:none";
                    y.innerHTML ="Enter the currect akmid";
                    y.style="color:red";
                    x.readOnly = false;
                }else{
                    button.style="display:block";
                }
            }
        } 
    </script>
    <?php 
        if(isset($_POST["akmlogin"])){
            $akmid = $_POST["akmid"];
            $akmpassword = $_POST["akmidpassword"];
            $akmid =mysqli_real_escape_string($server_connection, $akmid);
            $akmpassword =mysqli_real_escape_string($server_connection,$akmpassword);
            if($akmid !="" && $akmpassword !=""){
                $login = new akmaccounts($akmid, $akmpassword);
                $login->login_to_mainPage();
            }else{
                ?>
                    <script>
                        span.style="color:crimson";
                        span.innerHTML="Empty Field can not process";
                    </script>
                <?php
            }
        }
    ?>
</body>
</html>