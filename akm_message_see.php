<?php 
    session_start();
    $name = $_SESSION["username"];
    $akmid = $_SESSION["akmid"];
    if(!$_SESSION["akmid"] && !$_SESSION["username"]){
        header("location:userlogout.php");
    }
?> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $_SESSION["akmid"];?></title>
        <link type="png" rel="website icon" href ="images/message_4.png">
        <link href="IncludeFiles/D.css" rel="styleSheet">
        <script>
        function con(){
            var answer = confirm("ima");
            if(answer){
                alert("bye");
            }else{
               window.loaction='akm_mainPage.php';
            }
        }
        // history.forward();
    </script>
    </head>
    <body onunload="con();">
        <?php 
            include "IncludeFiles/function.php";
            include "IncludeFiles/javascriptfiles.php";
        ?>
        <script>
            function cancle_sidbar(){
                const sib_main = document.querySelector("#akm_sid_nav");
                const option1 = document.querySelector("#symbol1");
                const option2 = document.querySelector("#symbol2");
                const option3 = document.querySelector("#symbol3");
                const content = document.querySelector("#akm_content_message");
                content.style = "padding-left:06px";
                sib_main.style ="display:none";
            }
            function sib_bar_open(){
                const option1 = document.querySelector("#symbol1");
                const option2 = document.querySelector("#symbol2");
                const option3 = document.querySelector("#symbol3");
                const sib_main = document.querySelector("#akm_sid_nav");
                const content = document.querySelector("#akm_content_message");
                content.style = "padding-left:310px;";
                sib_main.style ="display:block";
                option1.style="display:none";
                option2.style="display:none";
                option3.style="display:none";
            }
        </script>
        <div id="">
            <div id="secoundtag">
                <div id="akm_sid_nav">
                    <ol id="sid_akm_ol">
                        <a id="sid_bar_cancle" onclick="cancle_sidbar()">&times;</a><br>
                        <div id='sid_bar_header'>
                            <center><h1>Welcome <?php echo $_SESSION["username"];?></h1></center>
                        </div>
                        <li id="sid_bar_akm"><a href='Akm_mainPage.php' id="sid_bar_a">Home</a></li>
                        <li id="sid_bar_akm"><a href="#account.php" id="sid_bar_a">Account</a></li>
                        <li id="sid_bar_akm"><a href="#spam.php" id="sid_bar_a">Spam</a></li>
                        <li id="sid_bar_akm"><a href="#setting" id="sid_bar_a">Setting</a></li>
                        <li id="sid_bar_akm"><a href="#sent.php" id="sid_bar_a">Sent</a></li>
                        <li id="sid_bar_akm"><a href="#importent.php" id="sid_bar_a">Importent</a></li>
                        <li id="sid_bar_akm"><a href="deleteAccount.php" id="sid_bar_a">Delete Account Request</a></li>
                        <li id="sid_bar_akm"><a href="userlogout.php" id="sid_bar_a">Logout</a></li>
                    </ol>
                </div>
                <div id="akm_main_body">
                <div id="akm_main_header">
                
                </div>
                <div id="akm_main_header1">
                
                </div>
                <div id="akm_main_header_message">
                        <h3 id="akm_main_tittle">AK_MESSAGES</h3>
                </div>
                </div>
                <div id="akm_body_content">
                    <?php 
                        $account = new accountchecking($name, $akmid);
                        $account->checkingaccountprocess();
                    ?>
                    <img src='userimages/<?php echo $account->getdp();?>' width="40px" height="40px" id="akmpage_image" onclick="sib_bar_open()">
                </div>
                
            </div>
            <div id="akm_content_message">
                <div id="akm_content_message1">
                    <marquee  id="alert_message" scrollamount="15"></marquee>
                    <span id='mani_spam'></span>
                    <?php 
                    $see_ak_message_id =0;
                    if(!$_GET["id"]){
                        header("location:akm_mainPage.php");
                    }else{
                        $see_ak_message_id = $_GET["id"];
                    }
                    $account = new akmMainAccount($name, $akmid);
                    $account->see_ak_message($see_ak_message_id, $name);
                    ?>
                    <a href="#" id="sendAKmessage" onclick="send_message_open()">&#9999;</a>
                </div>
            </div>
            <div id="send_ak_message">
                <center><h3 id='akm_header_text_box'>Ak message</h3></center>
                <p id="akm_cancle_message" onclick="send_message_close()">&times;</p>
                <form action="" method="post">
                    <input type="text" id="To_akm_id" name="to_akm_id" placeholder="To :-">
                    <textarea type="text" name="subject" placeholder="Subject" id="akm_subject_text"></textarea>
                    <textarea name="ak_message" id="ak_message" cols="59" rows="11" placeholder="Enter Message"></textarea>
                    <textarea name="akm_footer" id="akm_footer" cols="59" rows="2" placeholder="Enter footer message"></textarea>
                    <div id="div_akm_message_submit">
                        <input type="submit" name="ak_message_submit" id="akm_message_submit" value="Send">
                    </div>
                </form>
                <script>
                    const fd = document.querySelector("#forword_ak_message");
                    const RP = document.querySelector("#replay_ak_message");
                    const ak_subject = document.querySelector("#akm_sbj");
                    const ak_foot =document.querySelector("#akm_foot");
                    const ak_message = document.querySelector("#akm_sms");
                    const akm_message = document.querySelector("#ak_message");
                    const akm_sbj = document.querySelector("#akm_subject_text");
                    const akm_foot = document.querySelector("#akm_footer");
                    function forword(){
                        <?php 
                            $report=$account->gerakm_report();
                            if($report == "Sgakm"){
                                ?>
                                   fd.innerHTML="This is SG.<br>No forword!."
                                   fd.style="color:crimson";
                                <?php
                            }else{
                                ?>
                                    $("#send_ak_message").slideDown(1000);
                                    akm_message.value = ak_message.innerHTML;
                                    akm_sbj.value = ak_subject.innerHTML;
                                    akm_foot.value = ak_foot.innerHTML;
                                <?php
                            }
                        ?>
                    }
                    function replayakm(){
                        <?php 
                            $report=$account->gerakm_report();
                            if($report == "Sgakm"){
                                ?>
                                   RP.innerHTML="No Replay."
                                   RP.style="color:crimson";
                                <?php
                            }else{
                                ?>
                                    $("#send_ak_message").slideDown(1000);
                                    akm_message.value = ak_message.innerHTML;
                                    akm_sbj.value = ak_subject.innerHTML;
                                    akm_foot.value = ak_foot.innerHTML;
                                <?php
                            }
                        ?>
                    }
                </script>
                  <script>
                    function send_message_open(){
                        $("#send_ak_message").slideDown(1000);
                    }
                    function send_message_close(){
                        $("#send_ak_message").slideUp(1000);
                        akm_message.value = "";
                        akm_sbj.value = "";
                        akm_foot.value = "";
                    }
                </script>
                <?php 
                    if(isset($_POST["ak_message_submit"])){
                        @$akmidTo =  $_POST["to_akm_id"];
                        @$subject = $_POST["subject"];
                        @$message = $_POST["ak_message"];
                        @$footer = $_POST["akm_footer"];
                        $akmidTo = mysqli_real_escape_string($server_connection, $akmidTo);
                        $subject = mysqli_real_escape_string($server_connection, $subject);
                        $message =mysqli_real_escape_string($server_connection, $message);
                        $footer =mysqli_real_escape_string($server_connection, $footer);
                        $ReportEmptyFildes = 0;

                        if(empty($akmidTo)){

                            $ReportEmptyFildes = 1;

                        }elseif (empty($subject)){

                            $ReportEmptyFildes = 2;

                        }elseif (empty($message)){
                   
                            $ReportEmptyFildes =3;
                        }else{
                            $ReportEmptyFildes = 4;
                        }
                        switch ($ReportEmptyFildes) {
                            case 1:
                                # code...
                                ?>
                                    <script>
                                        var alert1 = document.querySelector("#akm_header_text_box");
                                        $("#send_ak_message").show(1000);
                                        alert1.style='color:crimson';
                                        alert1.innerHTML="The AKMID Empty..";
                                    </script>
                                <?php
                            break;
                            case 2:
                                ?>
                                    <script>
                                        var alert1 = document.querySelector("#akm_header_text_box");
                                        $("#send_ak_message").show(1000);
                                        alert1.style='color:crimson';
                                        alert1.innerHTML="The subject Empty..";
                                    </script>
                                <?php
                            break;
                            case 3:
                                ?>
                                    <script>
                                        var alert1 = document.querySelector("#akm_header_text_box");
                                        $("#send_ak_message").show(1000);
                                        alert1.style='color:crimson';
                                        alert1.innerHTML="The message Empty...";
                                    </script>
                                <?php
                            break;
                            case 4:
                                if($akmid == $akmidTo){
                                    ?>
                                        <script>
                                            var alert1 = document.querySelector("#mani_spam");
                                            alert1.style='color: white;background-color:crimson;border-radius:10px;padding:5px';                                                           //add url
                                            alert1.innerHTML="This is your akmid not allow to sent.";
                                        </script>
                                    <?php
                                }else{
                                    $sending_ak_message =new akmMainAccount($name, $akmid);
                                    $sending_ak_message->ak_message_send($message,$subject,$footer,$akmidTo,$name);
                                }
                            break;
                            default:
                               
                            break;
                        }
                       
                    }
                ?>
            </div>
        </div>
    </body>
</html>