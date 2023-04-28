<?php 
    session_start();
    $name = $_SESSION["username"];
    if(!$name){
        header("location:index.php");
    }
    include "IncludeFiles/function.php";
    include "connection/ServerConnection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatting page</title>
    <?php include "IncludeFiles/links1.php";?>
    <link href = "Includefiles/links2.css" rel="styleSheet">
</head>
<body id="body" style="background: linear-gradient(100deg, hsla(158, 92%, 5%, 0.719),  crimson)">
    <script>
        if(window.history.replaceState){
                window.history.replaceState(null, null, window.location.href='#14');
            }
    </script>
    <div id="alertboxcpage" style='display:none'>
        <div class="row">
            <div class="col-sm-12">
                <center>
                    <div>
                        <p id='alertmessage'></p>
                    </div>
                </center>
            </div>
        </div>
    </div>
    <div id="maindiv">
        <div class="row">
            <div class = "col-sm-12">
                <div id="chatting_header"  class="container-fluid">
                    <div class="row">
                        <div class='col-sm-6'>
                            <?php 
                                $auser = $_GET['ausername'];
                                $aakmid = $_GET['aakmid'];
                                $obj1 = new accountchecking($auser, $aakmid); // account checking a user account
                                $obj1->checkingaccountprocess();
                            ?>
                            <img src='userimages/<?php echo $obj1->getdp();?>' id="chattinguserimage">
                            <div>
                                <p id='persionid'>(::<?php echo $obj1->getakmid()?>::)</p>
                            </div>
                        </div>
                        <div class="col-sm-6" id='buserprofile'>
                            <?php 
                                $buser = $_GET["busername"];    
                                $bakmid = $_GET["bakmid"];          // b user account checking
                                $obj2 = new accountchecking($buser, $bakmid);
                                $obj2->checkingaccountprocess();
                                $a= new chatting($auser, $aakmid, $buser, $bakmid);
                                $a->creatingtable();
                                $messagetablename = $a->Tableschecking();
                                $a->messageseen();//updating the message see by the user
                                $stm2 = "SELECT count(usermessage) FROM  $messagetablename";
                                $res2 = mysqli_query($message_db_connection, $stm2);
                                $row2 = mysqli_fetch_array($res2);
                                $maxmessage = $row2['count(usermessage)'];
                                $stm7 = "SELECT max(id) FROM $messagetablename";
                                $res7 = mysqli_query($message_db_connection, $stm7);
                                $row7 = mysqli_fetch_assoc($res7);
                            ?>
                            <p id=bpersionid>(::<?php echo $obj2->getakmid()?>::)</p>
                            <img src='userimages/<?php echo $obj2->getdp();?>' id="chattinguserimage1">
                            <div id='buserpro'>
                                <p class="navprofile"><a onclick="userdetails()" id='buser_profile1'>profile</a><br>Total message:-<?php echo $maxmessage;?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="contact_list">
        <div class="row">
            <div class="col-sm-3">
                <div id="contact_list_inchatting">
                    <?php 
                        $obj3 = new contacts($name);
                        echo $obj3->displayContacts();
                    ?>
                </div>
            </div>
            <div class="col-sm-9">
                <div id="messagebox1">
                   <span id="message_span"></span>
                   <?php echo $a->displayMessage(); ?>
                </div>
                <form action="" method="post">
                  <div class="form-group">
                    <div class='row'>
                        <div class='col-sm-10'>
                            <textarea name="textbox"  id="textbox" autofocus></textarea>
                        </div>
                        <div class='col-sm-2'>
                            <input type="submit" name="message_submit" value="Send" id="sendbutton">
                        </div>
                    </div>
                  </div>
                </form>
            </div>
        </div>
    </div>
    <div id="buser_profile">
        <div class="row">
            <div class="col-sm-12">
                <center>
                    <div id="b_image">
                        <img src="userimages/<?php echo $obj2->getdp();?>"alt='&#128512;' id='chatting_b_profile_image' onclick="zoomin()">
                    </div>
                    <p>
                        <b id="b">Streck:-</b><?php echo $obj2->getstreck(); ?><br>
                        <b id="b">Name: -</b> <?php echo $obj2->getfname();?><br>
                        <b id='b'>Akmid: -</b><?php echo $obj2->getakmid();?><br>
                        <b id='b'>About:-</b><?php echo $obj2->getabout();?><br>    
                        <a onclick="back()" id="back">Back</a>            
                    </p>
                </center>
            </div>
        </div>
    </div>
    <div id="bigimage">
        <div>
            <img src="userimages/<?php echo $obj2->getdp();?>"alt='&#128512;' id='zoomout'onclick="zoomout()">
        </div>
    </div>
    <script>
        function userdetails(){
            const a = Boolean(document.querySelector("#buser_profile1"));
            if(a == true){
                const c = document.querySelector("#buser_profile");
                const b = document.querySelector("#contact_list");
                c.style = "display:block";
                b.style = "display:none"
            }
        }
        function back(){
            const a = Boolean(document.querySelector("#back"));
            if(a == true){
                const c = document.querySelector("#buser_profile");
                const b = document.querySelector("#contact_list");
                const e = document.querySelector("#zoomout");
                const d = Boolean(document.querySelector("#bigimage"));
                e.style = "display:none";
                c.style = "display:none";
                b.style = "display:block";
                d.style = "display:none";
            }
        }
        function zoomin(){
            const a = Boolean(document.querySelector("#chatting_b_profile_image"));
            if( a == true){
                const b = document.querySelector("#buser_profile");
                const c = document.querySelector("#bigimage");
                const d = document.querySelector("#contact_list");
                const e = document.querySelector("#zoomout");
                e.style = "display:block";
                c.style="display:block";
                b.style ="display:none";
                d.style ="display:none";
            }
        }
        function zoomout(){
            const a = Boolean(document.querySelector("#zoomout"));
            if(a == true){
                const c = document.querySelector("#bigimage");
                const b = document.querySelector("#buser_profile");
                const d = document.querySelector("#contact_list");
                d.style ="display:none";
                b.style = "display:block";
                c.style = "display:none";
            }
        }
    </script>
    <?php
        $ausername = $_GET['ausername'];//main username and akm id url
        $aakmid = $_GET['aakmid'];
        $busername = $_GET["busername"];// secound username and akm id from url
        $bakmid = $_GET["bakmid"];
        if($aakmid=='' || $bakmid =='' || $ausername=='' || $busername==''){
            header("location:system_report.php");//checking url names
        }
        $useraccount = array("$ausername"=>"$aakmid", "$busername"=>"$bakmid");
        $totalaccount = 0;
        foreach($useraccount as $username => $username_akmid){
            $obj = new accountchecking($username, $username_akmid);
            $accountokay = $obj->checkingaccountprocess();
            if($accountokay == 1){
                $totalaccount++;
            }
        }
        if($totalaccount != 2){
           ?>
                <script>
                    $(document).ready(function(){
                        $("#body").css("background: linear-gradient(100deg, hsla(158, 92%, 5%, 0.719),  crimson)");
                        const c = document.querySelector("#alertmessage");
                        $("#alertboxcpage").show(2000, function(){
                            alert("The user is blocked");
                        });
                        $("#maindiv").hide(1500);
                        c.innerHTML = '<h4>The user is blocked Some thing went wrong in the url....reset the url<br>System Reported<br><a href="add_contact.php">Back</a></h4>';
                        $("#contact_list").hide(1500);
                    });
                </script>
           <?php
        }elseif ($totalaccount == 2) {
            # code...
            $obj4 = new chatting($ausername,$aakmid,$busername, $bakmid);
            if(isset($_POST["message_submit"])){
                $message = $_POST["textbox"];
                $message = mysqli_real_escape_string($message_db_connection, $message);
                if($message != ''){
                    $obj4->InsertingMessage($message);
                    ?>
                         <script>
                           window.location='chatting.php?ausername=<?php echo $auser ?>&aakmid=<?php echo $aakmid;?>&busername=<?php echo $buser;?>&bakmid=<?php echo $bakmid;?>';
                        </script>
                    <?php 
                }else{
                    ?>
                        <script>
                            if(window.history.replaceState){
                                window.history.replaceState(null, null,window.location.href);
                            }
                            const spamalert = document.querySelector("#message_span");
                            spamalert.innerHTML = 'EmptyMessage';
                            $(document).ready(function(){
                                $("#message_span").show(1000);
                                $("#message_span").hide(2000);
                            })
                        </script>
                    <?php
                }
            }
        }
    ?>
</body>
</html>