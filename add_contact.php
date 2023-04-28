<?php 
    session_start();
     
    $name = $_SESSION["username"];

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
        <script>
            if(window.history.replaceState){
                window.history.replaceState(null, null,window.history.href);
            }
        </script>
    </head>
    <body>
        <div class="body">
            <?php 
                include "includeFiles/userheader1.php"; 
                include "connection/ServerConnection.php";
            ?>
            <div class="row" id="contactadd">
                <div class="col-sm-3">
                    <form method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username" style="width:300px" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="akmmessage">Akm-Id</label>
                            <input type="text" name="akmId" id="akmId" class="form-control" placeholder="Akm id" style="width:300px" readonly>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="contactsubmit" id="contactsubmit" style="width:300px" class="form-control">
                        </div>
                    </form>
                    <form method="post">
                        <div class="form-group">
                            <label for="Group"> Group</label>
                            <input type="text" name="group" id="group" style="width:300px" class="form-control" placeholder="search group">
                        </div>
                        <div class="form-group">
                            <input type="submit" id="groupsubmit" style="width:300px" class="form-control">
                        </div>
                    </form>
                    <span id="spam"></span>
                    <script>
                        username.oninput = AkmId;
                        function AkmId(){
                            y = "@akm.online_message";
                            akmId.value = this.value+y; 
                        }
                    </script>
                    <?php 
                        $secoundcount = 0;
                        if(isset($_POST["contactsubmit"])){
                            $username = $_POST["username"];
                            $username = mysqli_real_escape_string($server_connection, $username);
                            $akmId = $_POST["akmId"];
                            if(empty($username)){
                                ?>
                                    <script>
                                        const x = document.querySelector("#spam");
                                        x.innerHTML = "<h4>The field are empty.....</h4>";
                                        x.style = "color:crimson";
                                    </script>
                                <?php 
                            }else{
                                if($name === $username){
                                    ?>
                                        <script>
                                             const c = document.querySelector("#requestfream");
                                            const x = document.querySelector("#spam");
                                            x.innerHTML = "<h4>No allowed add self contact..</h4>";
                                            x.style = "color:crimson";
                                            c.style = "display:none";
                                        </script>
                                    <?php
                                }else{
                                    $stm = "SELECT * FROM newusers where username='$username' and akm_message ='$akmId'";
                                    $res = mysqli_query($server_connection, $stm);
                                    $secoundrow = mysqli_fetch_assoc($res);
                                    $secoundcount = mysqli_num_rows($res);
                                    $stm12 = "SELECT * FROM newusers where username='$name'";
                                    $res12 = mysqli_query($server_connection, $stm12);
                                    $firstrow = mysqli_fetch_assoc($res12);
                                }
                            }
                        }
                    ?>
                </div>
                <div class="col-sm-8">
                    <div id="requestfream" style="display:none">
                        <center><h4><b>Send Request</b></h4></center>
                        <p><b>Id :-<?php echo $secoundrow['id'];?></b></p>
                        <p><b>Friend Name :- <?php echo $secoundrow['username'];?></b></p>
                        <p><b>Total strecks :- <?php echo $secoundrow['daily_streck'];?></b></p>
                        <p><b>Akm id :- <?php echo $secoundrow['akm_message'];?></b></p>
                        <form method="post">
                            <input type="hidden" name="rbusername" value='<?php echo $secoundrow['username'];?>'>
                            <input type="hidden" name="rusername" value='<?php echo $firstrow['username'];?>'>
                            <input type="hidden" name='rid' value='<?php echo $firstrow['id'];?>'>
                            <input type="hidden" name='rakmid' value='<?php echo $firstrow['akm_message'];?>'>
                            <input type="submit" name="rsubmit" style="float:right;width:100px;height:30px" value="send request" class='btn btn-default'>
                        </form>
                    </div>
                    <div style="padding:30px">
                        <div id="contactlist">
                            <div class="row">
                                <div class="col-sm-4" id="call">
                                    <h4 class="divname">Friends</h4>
                                    <?php 
                                        $obj3 =new contacts($name);
                                        $obj3->displayContacts();
                                    ?>
                                </div>
                                <div class="col-sm-4" id="call">
                                    <h4 class="divname">Friend Request</h4>
                                    <?php
                                        $obj2 = new accept($name);
                                        $obj2->friendrequest_display();
                                    ?>
                                </div>
                                <div class="col-sm-4" id="call">
                                    <h4 class="divname">Friends Blocked</h4>
                                    <?php 
                                        $obj3->blockcontactsdisplay()
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            if($secoundcount === 1){
                ?>
                    <script>
                        var frdreq = <?php echo $secoundcount;?>;
                        console.log(frdreq);
                        if(frdreq === 1){
                            const c = document.querySelector("#requestfream");
                            const b= document.querySelector("#contactlist");
                            console.log(c);
                            c.setAttribute("style", "display:inline-block");
                            b.setAttribute("style", "display:none");
                            const ab = document.querySelector("#spam");
                            ab.innerHTML = "Searching user.....<br>Username <?php echo $secoundrow['username'];?>  &check;";
                            ab.style="color:green";
                        }
                    </script>
                <?php 
            }elseif ($secoundcount === 0) {
                # code...
                ?>
                    <script>
                        const x = document.querySelector("#spam");
                        const b= document.querySelector("#contactlist");
                        x.innerHTML = "<h4>Searching user.....<br>No user Like <?php echo $username; ?>.....&#10060;</h4>";
                        x.style = "color:crimson";
                        b.setAttribute("style", "display:block");
                    </script>   
                <?php 
            }
            if(isset($_POST['rsubmit'])){
                $busername = $_POST["rusername"];
                $bid = $_POST['rid'];
                $bakmid = $_POST['rakmid'];
                $busernameb = $_POST["rbusername"];
                $obj = new sendrequest($busernameb, $name);
                $obj->contact($busername, $bid, $bakmid);
            }
        ?>
    </body>
</html>