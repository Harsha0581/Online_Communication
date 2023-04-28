<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Exam Index</title>
        <?php include "IncludeFiles/links1.php";?>
        <link href='IncludeFiles/D.css' rel="StyleSheet">
        <script>
            var x = document.getElementById("login");
            function reg(){
                document.getElementById("reg").style.display="block";
                document.getElementById("login").style.display="none";
                document.getElementById("forp").style.display="none";

            }
            function login(){
                document.getElementById("login").style.display="block";
                document.getElementById("reg").style.display="none";
                document.getElementById("forp").style.display="none";

            }
            function forgotpassword(){
                document.getElementById("login").style.display="none";
                document.getElementById("reg").style.display="none";
                document.getElementById("forp").style.display="block";
            }
            if(window.history.replaceState){
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </head>
    <body class="backgroundimage" style="background-color:rgb(163, 178, 202);color:whitesmoke">
        <?php  
            include "IncludeFiles/function.php";
            include "connection/ServerConnection.php";    
        ?>
        <div class="jumboton text-center header">
            <h1>Online Chatting</h1>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href = "login.php">Login</a></li>
                    <li><a href = "About.php">About</a></li>
                </ul>
            </div>
        </div>
        <div class="row" style="margin-top:40px">
            <div class="col-sm-1">
            </div>
            <div class="col-sm-11">
                <button class="btn btn-default" onclick="login()" style="width:150px" id="btn1"><a href="#login">Login</a></button>
                &nbsp;
                <button class="btn btn-default" onclick="reg()"  style="width:150px" id="btn2"><a href="#reg">Regester</a></button>
            </div>
        </div>
        <div class="row" id="login" style="margin-top:10px">
            <div class="col-sm-1">
            </div>
            <div class="col-sm-11">
                <form method="post">
                    <div class="form-group">
                        <label for="Username">Username:</label>
                        <input type="username" class="form-control" id="username" placeholder="Enter username" name="username" style="width: 300px;" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="userpassword" placeholder="Enter password" name="userpassword" style="width: 300px">
                    </div>
                    <button type="submit" class="btn btn-default" name="Userlogin"  style="width: 300px" id="loginsubmit">login</button>
                </form><br>
                <a href = "forgotpassword.php" style = "color:cornflowerblue">Forgot Password</a>
                <spam></spam>
            </div>
        </div>
        <?php 
            if(isset($_POST["Userlogin"])){
                $username = $_POST["username"];
                $password = $_POST["userpassword"];
                $userlogin = new Userlogin($username, $password);
                $userlogin->login();
            }
        ?>
        <!-- regestration -->
        <div class="row" id="reg" style="display:none">
            <form method="post">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type ="text" name="R_Username" autofocus placeholder="Username" id="username" style="width:300px" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type ="Email" name="R_Email" placeholder="Email" id="Email" style="width:300px" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="Gender">Gender</label>
                        <select class="form-control" style="width:300px" name="R_Gender">
                            <option>---</option>
                            <option>male</option>
                            <option>Female</option>
                            <option>Others</option>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-sm-4">

                </div> -->
                <div class="col-sm-4">

                    <div class="form-group">
                        <label for="Number">Number</label>
                        <input type ="number" name="R_Number" placeholder="Number" id="Number" style="width:300px"class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password">password</label>
                        <input type ="password" name="R_Password" placeholder="Password" id="password" style="width:300px"class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="username">Conform password</label>
                        <input type ="password" name="c-password" placeholder="c-password" id="c-password" style="width:300px"class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-default" name="R_submit"  style="width: 700px">Regester</button>
            </form>
        </div> 
        <?php  

            $regprocess = new Regester();

            if(isset($_POST["R_submit"])){
                
                $R_username = $_POST["R_Username"];

                $R_email = $_POST["R_Email"];

                $R_gender = $_POST["R_Gender"];

                $R_number = $_POST["R_Number"];

                $R_password = $_POST["R_Password"];

                $R_c_password = $_POST["c-password"];
                
                $passwordlength = strlen($R_password);

                if($R_username !== "" && $R_email !=="" && $R_gender !=="" && $R_number && $R_c_password !=="" && $R_password !==""){

                  $phonenumberlength = strlen($R_number);

                  if($phonenumberlength === 10){

                        if($passwordlength >= 5){

                            if($R_password === $R_c_password){
                            
                                $regprocess->set_Rusername($R_username);
        
                                $regprocess->set_Remail($R_email);
        
                                $regprocess->set_Rpassword($R_password);
        
                                $regprocess->RegesterProcess($R_gender, $R_number);
        
                            }else{
        
                                die("<script>alert('The password not mached faild');window.location = 'login.php';</script>");
                            }
        
                        }else{
                            die("<script>alert('The password must be above 5 letters faild');window.location = 'login.php';</script>");
                        }
                    }else{
                        die("<script>alert('Enter Your 10 diget number..');window.location = 'login.php';</script>");
                    }
                }else{
                    die("<script>alert('The fild are empty faild');window.location = 'login.php';</script>");
                }
            }  
        ?>

    </body>
</html>