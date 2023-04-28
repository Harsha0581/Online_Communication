<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <?php include "IncludeFiles/links1.php";?>
        <link rel="StyleSheet" href="IncludeFiles/D.css">
    </head>
    <body style="background-color:rgb(214, 169, 169)">
        <?php  include "IncludeFiles/function.php";?>
        <div class="jumboton text-center header">
            <h1 style="color:cornsilk">Forgot Password</h1>
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
        <script>
            if(window.history.replaceState){
                window.history.replaceState(null, null,window.history.href);
            }
        </script>
        <div class="forgotpassword">
            <div class="row">
                <div class="col-sm-3">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="username" id="username" class="form-control forpusername" placeholder="Username:" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="Akmid">Akmid:</label>
                            <input type="text" name="akmid" id="akmid" class="form-control forpakmid" placeholder="Akmid:">
                        </div>
                        <div class="form-group">
                            <button name="forgotSubmit" id="forgotsubmit" class="btn btn-default">Send link</button>
                        </div>
                    </form>
                    <span id="forgotspan"></span>
                </div>
            </div>
        </div>
        <?php 
          $link_sending=0;
        //   echo date("m");
            if(isset($_POST["forgotSubmit"])){
                $username = $_POST["username"];
                $akmid= $_POST["akmid"];
                $changepassword = new password($username, $akmid);
                $link_sending = $changepassword->Sendlink();
                ?>  
                    <script>    
                        const span = document.querySelector("span");
                        var value = <?php echo $link_sending;?>;
                        if(value ==1){
                            span.style="color:green";
                            span.innerHTML ="Your Password link Sent to akm account....<br>You have 200sec for changing password.<br>After login into akm account....";
                            $("#forgotspan").show(2000);
                        }else{
                            span.style="color:Crimson";
                            span.innerHTML ="Incorrect username or akmid....,";
                            $("#forgotspan").hide(2000);
                            $("#forgotspan").show(2000);
                        }
                    </script>
                <?php 
            }
        ?>
    </body>
</html>