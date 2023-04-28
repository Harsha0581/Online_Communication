<?php 
    session_start();
     
    $name = $_SESSION["username"];

    if(!$name){                                                                     //logout alg
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
        <title> <?php echo $name ?> Edit profile</title>
        <?php include "links.php";?>
        <link rel="StyleSheet" href="D.css">
    </head>
    <body style="background-color:darkgrey" class="backgroundimage">
        <?php include "includeFiles/userheader.php"; ?>

        <div class="row" id="reg" style="color:white">
            <form method="post">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type ="text" name="R_Username" placeholder="Username" id="username" style="width:300px" class="form-control" readonly value="<?php echo $_GET['username'];?>">
                    </div>

                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input type ="Email" name="R_Email" placeholder="Email" id="Email" style="width:300px" class="form-control" value="<?php echo $_GET['email'] ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="Gender">Gender</label>
                    
                        <select class="form-control" style="width:300px" name="R_Gender" value="<?php echo $_GET['Gender'] ?>">
                            <option><?php echo $_GET['Gender'] ?></option>
                            <option>male</option>
                            <option>Female</option>
                            <option>Others</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">

                </div>
                <div class="col-sm-4">

                    <div class="form-group">
                        <label for="Number">Number</label>
                        <input type ="number" name="R_Number" placeholder="Number" id="Number" style="width:300px"class="form-control" value="<?php echo $_GET['Number'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">password</label>
                        <input type ="password" name="R_Password" placeholder="Password" id="password" style="width:300px"class="form-control"value="<?php echo $_GET['password'] ?>">
                    </div>

                    <div class="form-group">
                        <label for="username">Akm id</label>
                        <input type ="text" readonly  style="width:300px"class="form-control" value="<?php echo $_GET['akmid'] ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-default" name="R_submit"  style="width: 700px">UPDATE</button>
            </form>
        </div>  
        <?php 

            if(isset($_POST['R_submit'])){

                $email = $_POST["R_Email"];
                $number  = $_POST['R_Number'];
                $gender = $_POST["R_Gender"];
                $password = $_POST["R_Password"];
                
                $number = mysqli_escape_string($server_connection, $number);
                $email    = mysqli_escape_string($server_connection, $email);
                $password = mysqli_escape_string($server_connection, $password);

                if($email !=="" && $number !=="" && $gender !=="" && $password !==""){
                    $numberlength =  strlen($number);

                    if($numberlength === 10){

                        $profile_update_stm = "UPDATE newusers set email = '$email', number = '$number', gender = '$gender', password = '$password' where username = '$name'";

                        $profile_update_result = mysqli_query($server_connection, $profile_update_stm);

                        header('location:profile.php');

                    }else{
                        die("<script>alert('Enter the 10 digit mobile number');
                            document.getElementById('Nummber').value='';
                            window.location='profile.php';</script>")
                        ;
                    }
                }
            }
        
        ?>
    </body>
</html>