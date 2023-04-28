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
        <link rel="stylesheet" href="IncludeFiles/links2.css">
        <?php  include "IncludeFiles/links1.php";?>
        <title><?php echo $name ?> Profile</title>
    </head>
    <body>
        <div id="hide">
            <div id="header" class="container-fluid">
                <!-- userheader main head -->
                    <a href="UserPage.php">Back</a>
                    <h2>Hiii...<?php echo $name ?><b id="hiiimage">&#128075;</b></h2>     
            </div>
            <?php include "IncludeFiles/function.php"?>
            <div id="navbar">
                    <ul>
                        <?php 
                        // nav bar
                            $stm = "SELECT * FROM navbar";
                            $res = mysqli_query($server_connection, $stm);
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "
                                    <li><a href = $row[navlink]>$row[navname]</a></li> 
                                ";
                            }
                        ?>
                    </ul>
            </div>
            <?php 
                $stm = "SELECT * FROM newusers where username = '$name'";
                $res = mysqli_query($server_connection, $stm);
                $row2= mysqli_fetch_assoc($res);
                if(empty($row2["username"]) && empty($row2["gender"]) &&empty($row2["number"])
                &&empty($row2["email"])&&empty($row2["daily_streck"])){
                    ?>
                        <script>
                            const x = document.querySelector("#spam");
                            const y = document.querySelector("#name");
                            y.style = "display:none";
                            x.innerHTML = "No data found";
                            x.style="color:blue";
                        </script>
                    <?php
                }
            ?>
            <div class="row">
                <div class="col-sm-3">
                    <div id="frame">
                        <div id="frame1">
                            <a onclick="bigimage()"style="cursor:zoom-in"><img src ="userimages/<?php echo $row2['dp'];?>" id="userimages"></a>
                        </div><br>
                        <div id = "profile-details">
                            <p id="name"><b>Id:</b>&nbsp; &nbsp;<?php echo $row2['id']?></p></p>
                            <p id="name" class="status"><b>Streck:</b>&nbsp; &nbsp;<?php echo $row2['daily_streck']?></p></p>
                            <p id="name"><b>Name :</b> &nbsp; &nbsp;<?php echo $row2['username']?></p>
                            <p id="name"><b>Gender:</b>&nbsp; &nbsp;<?php echo $row2['gender']?></p></p>
                            <p id="name"><b>Number:</b>&nbsp; &nbsp;<?php echo $row2['number']?></p></p>
                            <p id="name"><b>Email :</b>&nbsp; &nbsp;<?php echo $row2['email']?></p></p>
                            <p id="name"><b>Akm ID:</b>&nbsp; &nbsp;<?php echo $row2['akm_message']?></p></p>
                            <p id="span">All Set</p>
                        </div>
                    </div>
                    <script>
                        const streckNumber = <?php echo $row2['daily_streck']?>;
                        const streckinnertext = document.querySelector(".status");
                        // console.log(streckNumber);
                        if(streckNumber < 1){
                            streckinnertext.innerHTML += "&#128545;";
                            streckinnertext.style = "color:red";
                        }
                        if(streckNumber >= 1){
                            streckinnertext.innerHTML += " "+"&#128512;";
                            streckinnertext.style = "color:yellowgreen";
                        }
                        if(streckNumber >10){
                            streckinnertext.innerHTML += " "+"&#128131;	&#128378;";
                            streckinnertext.style = "color:coral";
                        }
                    </script>
                </div><br>
                <div class="col-sm-9">
                    <div  id="formgroup">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="from-grop">
                                <center id="uploadheader"><b>Upload dp</b></center>
                                <input type="file" name="ImageDp12" id="imageupload">
                            </div>
                            <div class="form-group" id = "submitup">
                                <input type="submit" name="dpupload" id="submitimage" name="Upload">
                            </div>  
                        </form>
                    </div>
                </div>
                <div class="upperdivsubmit">
                    <div class="about">
                        <p><b>About</b></p>
                        <form action="" method="post">
                            <div class="form-content">
                                    <textarea name="About" id="about" cols="80" rows="10" placeholder="write some thing:---"><?php echo $row2['about'];?></textarea>
                            </div>
                            <div class="form-content">
                                <input type="submit" name="AboutSubmit" value="Update" id="aboutsubmit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                    if(window.history.replaceState){
                        window.history.replaceState(null, null, window.history.href);
                    }
            </script>
        </div>
        <div id="bigimage1">
            <a onclick="profile()" style="float:right;font-size:15px;padding:60px;cursor: pointer">Go Back</a>
            <div id="bigimage2">
                <img src = "userimages/<?php echo $row2['dp'];?>" id="userimages2" style="border-radius:10px">
            </div>
        </div>
        <script>
            const hide = document.querySelector("#hide");
            const mainimage = document.querySelector("#bigimage1");
            const bodycolor =document.querySelector("body");
            function bigimage(){
                hide.style = "display:none";
                mainimage.style="display:block;";
                bodycolor.style="background-color:darkgray";
            }   
            function profile(){
                hide.style = "display:block";
                mainimage.style="display:none";
                bodycolor.style="background-color:green";
            }
        </script>
       <?php 
            if(isset($_POST['dpupload'])){
                
                if($_FILES["ImageDp12"]["name"] !==""){
                    $dp_name = $_FILES['ImageDp12']['name'];                                           //coding for uploading profile pick
                    $dp_tmp = $_FILES['ImageDp12']["tmp_name"];
                    $dp_type =$_FILES['ImageDp12']["type"];
                    $location ="userimages/";
                    $real_image_ext =pathinfo($dp_name, PATHINFO_EXTENSION);
                    $image_ext = array("png", "jpg", "jpeg");
                    $search_image_ext = in_array($real_image_ext, $image_ext);
                    $new_location = $location.$dp_name;
                    if( $search_image_ext == 1){
                       move_uploaded_file($dp_tmp, $new_location);
                       $upload_stm = "UPDATE newusers set dp = '$dp_name' where username = '$name'";
                       $upload_result = mysqli_query($server_connection, $upload_stm);
                       ?>
                            <script>
                                const x = document.querySelector("#span");
                                const y = document.querySelector("img");
                                y.setAttribute("src","userimages/<?php echo $dp_name;?>");
                                x.innerHTML = "Your profile pick uploade";
                                x.style="color:darkgray;font-size:19px";
                                alert("uploades");
                            </script>
                        <?php
                       if(!$upload_result){
                         die("some thing went wrong for uploading");
                       }
                    }else{
                        die("<script>
                            alert('This is not image file');
                            document.getElementById('imagedp').value='';
                            </script>
                        ");
                    }
                }else{
                   ?>
                        <script>
                            const x = document.querySelector("#span");
                            x.innerHTML = "The file is empty";
                            x.style="color:red;font-size:19px";
                            alert("The fild is empty");
                        </script>
                    <?php
                }
            }
            if(isset($_POST['AboutSubmit'])){
                if($_POST['About'] !==""){
                    $about = $_POST['About'];
                    $about_stm = "UPDATE newusers set about = '$about' where username = '$name'";
                    $about_result = mysqli_query($server_connection, $about_stm);
                    ?>
                        <script>
                            window.location="profile.php";
                        </script>
                    <?php
                }
            }
        ?>
    </body>
</html>