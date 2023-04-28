<?php 
    // error_reporting(0);
    include "connection/ServerConnection.php";
    class logindates1{
        public function logindate($username, $password){
            global $server_connection;
            $name = $username;
            $pass = $password;
            $curdate = date('Y-m-d H:i:s');
            $login_stm = "SELECT * FROM newusers where username = '$name' and password = '$pass'";
            $login_res = mysqli_query($server_connection, $login_stm);
            $login_row = mysqli_fetch_assoc($login_res);
            $logindate = $login_row["login_time"];
            $logintimes = $login_row["Total_login"];
            if(empty($logindate)){
                $stm = "UPDATE newusers set login_time = sysdate() where username  = '$name' and password = '$pass'";
                $res  = mysqli_query($server_connection, $stm);     
                $nof_times = "UPDATE newusers set total_login = 1 where username  = '$name' and password = '$pass'";
                $nof_res = mysqli_query($server_connection, $nof_times);
            }else{
                $stm2 = "UPDATE newusers set login_time = sysdate() where username  = '$name' and password = '$pass'";
                $res  = mysqli_query($server_connection, $stm2);
                // $stm3 = "UPDATE newusers set regester_date = sysdate()";
                // $res = mysqli_query($server_connection, $stm3);
                if(empty($logintimes)){
                    $nof_times = "UPDATE newusers set total_login = 1 where username  = '$name' and password = '$pass'";
                    $nof_res = mysqli_query($server_connection, $nof_times);
                }else{
                    $nof_times = "UPDATE newusers set total_login = $logintimes + 1 where username  = '$name' and password = '$pass'";
                    $nof_res = mysqli_query($server_connection, $nof_times);
                }

            }
        }
        public function regtime($name){
            global $server_connection;
            $stm = "UPDATE newusers set regester_date = sysdate() where username  = '$name'";
            $res  = mysqli_query($server_connection, $stm);
        }
    }
    interface accountTables{
        public function tablechecking();
        public function tableprocess();
    }
    
    class account_tables_name{
        private $name;
        private $akmid;
        private $contacts;
        private $contactsblock;
        private $friendrequest;
        private $akmaccount;
        function __construct($name){
            $this->name=$name;
            $this->contacts =$name."contacts";
            $this->contactsblock=$name."block";
            $this->friendrequest=$name."request";
            $this->akmaccount=$name."akmaccount";
        }
        public function getcontacts(){
            return $this->contacts;
        }
        public function getcontactsblock(){
            return $this->contactsblock;
        }
        public function getfriendrequest(){
            return $this->friendrequest;
        }
        public function getakmaccount(){
            return $this->akmaccount;
        }
    }
    class account_name_process extends account_tables_name implements accountTables{
        private $name;
        function __construct($name){
            $this->name=$name;
        }
        private function getname(){
            return $this->name;
        }
        function tablechecking(){
          $tablename = new account_tables_name($this->getname());
          $tableArray = array();
          $nontables =array();
          $arraytablename =array();
          array_push($tableArray, $tablename->getakmaccount());
          array_push($tableArray, $tablename->getcontacts());
          array_push($tableArray, $tablename->getcontactsblock());
          array_push($tableArray, $tablename->getfriendrequest());
          for($i=0; $i<=3; $i++){
            
          }
          return $nontables;
        }
        function tableprocess(){
            
        }
    }
    class Userlogin{
        private $username;
        private $password;
        private $contacttable;
        private $requesttable;
        private $contactblocktable;
        private $akmaccount;
        public function __construct($username, $password){
            $this->username = $username;
            $this->password = $password;
            $this->contactblocktable = $username."block";
            $this->requesttable = $username."request";
            $this->contacttable = $username."contacts";
            $this->akmaccount=$username."akmaccount";
        }
        function login(){
            session_start();
            global $server_connection;
            global $contact_db_connection;
            global $request_db_connection;   
            global $block_contact_db_connection;     
            global $akm_account_db_connection;                          //taking the value from the main class to login
            $username = $this->username;
            $password = $this->password;
            // session_start();
            $username = mysqli_real_escape_string($server_connection, $username);
            $password = mysqli_real_escape_string($server_connection, $password);
            $stm1 = "SELECT * FROM newusers WHERE username='$username' and password='$password' and userStatus='unblock'";
            $res1 = mysqli_query($server_connection, $stm1);
            $row = mysqli_fetch_assoc($res1);
            $usercount = mysqli_num_rows($res1);
            if($usercount == 1){
                $blocktablename = $this->contactblocktable;
                $requesttablename = $this->requesttable;
                $contacttablename = $this->contacttable;
                $akmaccount = $this->akmaccount;
                $stm2 = "SHOW TABLES LIKE '$blocktablename'";
                $res2 = mysqli_query($block_contact_db_connection, $stm2);
                $count1 = mysqli_num_rows($res2);
                if($count1 != 1){
                    $stm3 = "CREATE TABLE $blocktablename(blockid bigint(8) primary key AUTO_INCREMENT not null, blockuserid int(80) not null, 
                        blockusername varchar(60) not null, blockakmid varchar(70) not null, 
                        foreign key(blockuserid) references indiansongs.newusers(id))"
                    ;
                    $res3 = mysqli_query($block_contact_db_connection, $stm3);
                }
                $stm4 = "SHOW TABLES LIKE '$requesttablename'";
                $res4 = mysqli_query($request_db_connection, $stm4);
                $count2 = mysqli_num_rows($res4);
                if($count2 != 1){
                    $stm5 = "CREATE TABLE $requesttablename(id int(11) AUTO_INCREMENT primary Key not null, request_friend_id int(40) not null ,
                        friend_request_name varchar(60) not null, friend_request_akm_id varchar(90) not null,
                        foreign key(request_friend_id) references indiansongs.newusers(id))"
                    ;
                    $res5 = mysqli_query($request_db_connection, $stm5); //creating the friend request table
                }
                $stm6 = "SHOW TABLES LIKE '$contacttablename'";
                $res6 = mysqli_query($contact_db_connection, $stm6);
                $count3 = mysqli_num_rows($res4);
                if($count3 !=1){
                    $stm7 = "CREATE TABLE $contacttablename(id int(11) AUTO_INCREMENT primary Key not null,
                        contactnameid int(11) not null, contactname varchar(50) not null, akmid varchar(90) not null,
                        foreign key(contactnameid) references indiansongs.newusers(id))"
                    ;
                    $res7 = mysqli_query($contact_db_connection, $stm7);       //creating the contact table if the contact table is absent
                }
                $stm11 = "SHOW TABLES LIKE '$akmaccount'";
                $res11  = mysqli_query($akm_account_db_connection, $stm11);
                $count11 = mysqli_num_rows($res11);
                if($count11 !=1){
                    $stm12 = "CREATE TABLE $akmaccount(id int(60) AUTO_INCREMENT PRIMARY KEY not null, username varchar(50) not null,userid int(61) not null,
                    time1 varchar(30) not null,subject_akm text(80) not null,ak_message text(90),akm_report varchar(20) not null default 'normal',
                    toakmid varchar(80) not null, seen varchar(10) default 'unseen', footer text(80), message_status varchar(20) default 'unsent',
                    ak_message_id bigint(30),replayId bigint(10), fromakmid varchar(80),
                    foreign key(fromakmid) references indiansongs.akmaccounts(akmid),
                    foreign key(toakmid) references indiansongs.akmaccounts(akmid),
                    foreign key(username) references indiansongs.akmaccounts(username),
                    foreign key(userid) references indiansongs.newusers(id))";
                    $res12 = mysqli_query($akm_account_db_connection, $stm12);
                }
                $stm8 = "SHOW TABLES LIKE '$blocktablename'";//account re checking process
                $res8 = mysqli_query($block_contact_db_connection, $stm8);
                $count8 = mysqli_num_rows($res8);
                $stm9 = "SHOW TABLES LIKE '$requesttablename'";
                $res9 = mysqli_query($request_db_connection, $stm9);
                $count9 = mysqli_num_rows($res9);
                $stm10 = "SHOW TABLES LIKE '$contacttablename'";
                $res10 = mysqli_query($contact_db_connection, $stm10);
                $count10 = mysqli_num_rows($res10);
                $stm13 = "SHOW TABLES LIKE '$akmaccount'";
                $res13 = mysqli_query($akm_account_db_connection, $stm13);
                $count13 = mysqli_num_rows($res13);
                if($count8 ==1 && $count9 == 1 && $count10 ==1 && $count13 == 1){
                    $account_name = "SELECT accountname FROM akmaccounts WHERE username ='$username'";
                    $account_nema_res = mysqli_query($server_connection, $account_name);
                    $account_row = mysqli_fetch_assoc($account_nema_res);
                    if(empty($account_row["accountname"])){
                        $stm_account_name  = "UPDATE akmaccounts SET accountname ='$akmaccount' WHERE username = '$username'";
                        $res_account_res = mysqli_query($server_connection, $stm_account_name);
                        $accountdate = new logindates1();
                        $accountdate->logindate($username, $password);
                        $streck = new daily_status($username, $password);
                        $streck->daily_update();
                        $_SESSION["username"] = $username;
                        $_SESSION["akmid"] = $row["akm_message"];
                        header("location:UserPage.php");//user main page
                    }else{
                        $accountdate = new logindates1();
                        $accountdate->logindate($username, $password);
                        $streck = new daily_status($username, $password);
                        $streck->daily_update();
                        $_SESSION["username"] = $row["username"];
                        $_SESSION["akmid"] = $row["akm_message"];
                        header("location:UserPage.php");//user main page
                    }
                }else{
                    ?>
                        <script>
                            const span = document.querySelector("#spam");
                            span.innerHTML = "Some thing went Wrong....<br>Help";
                            span.style = "color:red";
                        </script>

                    <?php
                }
            }else{
                $blockstm = "SELECT * FROM newusers where username = '$username' and password = '$password' and userStatus ='block'";
                $blockResult = mysqli_query($server_connection, $blockstm);
                $blockCount = mysqli_num_rows($blockResult);
                if($blockCount === 1){
                    ?>
                        <script>
                            const span = document.querySelector("spam");
                            span.innerHTML = "<h4>The user is blocked....<br>You can check Your Statement in akm account..</h4>";
                            span.style = "color:red";
                        </script>

                    <?php
                }else{
                    ?>
                        <script>
                            const span = document.querySelector("spam");
                            span.innerHTML += "<h4>invalid username and password....</h4>";
                            span.style = "color:crimson;padding-left:30px";
                        </script>
                    <?php
                }
            }
        }
    }
    class Regester{

            private $Rusername;

            private $Remail;                                            //Regesteration  values 

            private $Rpassword;

            function set_Rusername($R_username){
                $this->Rusername = $R_username;
            }                                                                   
            function get_Rusername(){
                return $this->Rusername;
            }
            function set_Remail($Remail){
                $this->Remail = $Remail;
            }
            function get_Remail(){
                return $this->Remail;
            }
            function set_Rpassword($Rpassword){
                $this->Rpassword = $Rpassword;
            }
            function get_Rpassword(){
                return $this->Rpassword;
            }

        function RegesterProcess($gender, $number){
            global $server_connection;
            global $request_db_connection;
            global $contact_db_connection;
            global $akm_account_db_connection;

            $gender = $gender;

            $number = $number;

            $username = $this->Rusername;                                   //taking the value from the above class for regestration

            $email  = $this->Remail;

            $password = $this->Rpassword;

            $akm_id = "@akm.online_message";

            $akm_message = $username.$akm_id;

            $username = mysqli_escape_string($server_connection, $username);
            $number = mysqli_escape_string($server_connection, $number);
            $email    = mysqli_escape_string($server_connection, $email);
            $password = mysqli_escape_string($server_connection, $password);

            if($username === 3){
                die("<script>alert('The user name must above 3 letters faild')window.location = 'login.php';</script>");
            }
            
            $blocked_stm = "SELECT * FROM newusers where username ='$username' and userStatus='block'";              //checking username in block list

            $blocked_user_result = mysqli_query($server_connection, $blocked_stm);

            $blocked_count = mysqli_num_rows($blocked_user_result);

            $blocked_row = mysqli_fetch_assoc($blocked_user_result);

            if($blocked_count === 1){

                die("<script>alert('The username in block list failed');window.location = 'login.php';</script>");

            }

            $exist_user_stm = "SELECT*FROM newusers where username  = '$username'";
                                                                                                
            $exist_user_result = mysqli_query($server_connection, $exist_user_stm);         // checking the user name in the regester list

            $exist_user_count = mysqli_num_rows($exist_user_result);

            $exist_user_row = mysqli_fetch_row($exist_user_result);

            if($exist_user_count === 1){

                die("<script>alert('The username in used failed');window.location = 'login.php';</script>");

            }
            
            $stm = "SELECT * FROM newusers";

            $users_result =mysqli_query($server_connection, $stm);

            $stm = "SELECT*FROM newusers";

            $block_result = mysqli_query($server_connection, $stm);

            while($block_row = mysqli_fetch_assoc($block_result)){

                "<br>".$block_row['email'];

                if($block_row['email'] === $email){
                    
                    die("<script>alert('The email is taken');window.location = 'login.php'</script>");          //search for number and email in blocked list
                }                                                                                                               
                if($block_row['number'] === $number){                                                               

                    die("<script>alert('the nummber is taken');window.location = 'login.php';</script>");
                }
            }
            while( $users_row = mysqli_fetch_assoc($users_result)){

                if($users_row['email']  === $email){

                    die("<script>alert('The email is taken');window.location = 'login.php'</script>");          //search for the email and number is presert
                }                                                                                                           //newnuser list
                if($users_row['number'] === $number){

                    die("<script>alert('the nummber is taken');window.location = 'login.php';</script>");
                }
            }

            $new_user_reg_stm = "INSERT INTO newusers(username, email, gender, number, password, akm_message, dp, atp_times) values('$username', '$email', '$gender', '$number','$password', '$akm_message', null, 0)";

            $new_user_reg_result = mysqli_query($server_connection, $new_user_reg_stm);                         //adding the new user

            $akmaccount_stm = "INSERT INTO akmaccounts(username, akmid)value('$username', '$akm_message')";
            $akmaccount_res = mysqli_query($server_connection, $akmaccount_stm);


            $new_user_reg_backup_stm = "INSERT INTO backup_user_data(username, email, gender, number, password, akm_message, atp_times) values('$username', '$email', '$gender', '$number','$password', '$akm_message',0)";

            $new_user_reg_result = mysqli_query($server_connection, $new_user_reg_backup_stm);                  //backup database for users
            $account = new logindates1();
            $account->regtime($username);                          
            echo "<script>alert('Regester Down');window.location = 'login.php';</script>";
            if(!$new_user_reg_result){
                die("<script>alert('Some thing went wrong in regestration');</script>");
            }
            echo "okay";
        }
    }
    class sendrequest{                             //adding the contact
        private $contact_name;
        private $mainsuer;
        private $BUserRequestTableName;
        private $AUserRequestTableName;
        private $AUserContactsTableName;
        private $BUsercontactTableName;
        private $BBlockTablename;
        private $ABlockTablename;

        function __construct($contact_name, $mainsuer){
            $this->contact_name = $contact_name;                 //Taking the value from key board
            $this->mainsuer = $mainsuer;
            $this->AUserRequestTableName = $mainsuer."request";
            $this->BUserRequestTableName = $contact_name."request";
            $this->AUserContactsTableName = $mainsuer."contacts";
            $this->BUsercontactTableName = $contact_name."contacts";
            $this->BBlockTablename = $contact_name."block";
            $this->ABlockTablename = $mainsuer."block";
        }
        function contact($Busername, $Bid, $Bakmid){               //server connection     
            global $request_db_connection;
            global $contact_db_connection;
            global $block_contact_db_connection;
            $secounduser =  $this->BUserRequestTableName;
            $firstuser = $this->AUserRequestTableName;
            $secoundusername = $this->contact_name;
            $firstusername = $this->mainsuer;
            $FirstUserContactTableName = $this->AUserContactsTableName;
            $secoundUserContactTableName = $this->BUsercontactTableName;
            $Bblocktablename = $this->BBlockTablename;
            $Ablocktablename = $this->ABlockTablename;
            $stm = "SHOW TABLES LIKE '$secounduser'";
            $res = mysqli_query($request_db_connection, $stm);
            $count = mysqli_num_rows($res);
            if($count != 1){
                die("
                    <script>
                        const x = document.querySelector('#spam');
                        x.innerHTML = '<h4> No logins from the user  &nbsp; $secoundusername.....<br> Have to login once..</h4>';
                        x.style = 'color:red';
                    </script>
                ");
            }
            $searchContacts = "SELECT * FROM $FirstUserContactTableName WHERE contactname = '$secoundusername'";
            $searchres = mysqli_query($contact_db_connection, $searchContacts);
            $countcont = mysqli_num_rows($searchres);
            if($countcont == 1){
                die("
                    <script>
                        const x = document.querySelector('#spam');
                        x.innerHTML = '<h4>$secoundusername is in your friend list..<br></h4>';
                        x.style = 'color:green'; 
                    </script>
                ");
            }
            $stm3 = "SELECT * FROM $Bblocktablename WHERE blockusername = '$firstusername'";
            $res3 = mysqli_query($block_contact_db_connection, $stm3);
            $count3 = mysqli_num_rows($res3);
            if($count3 == 1){
               
               die("
                    <script>
                        const a = document.querySelector('#spam');
                        a.innerHTML = '<h4>Not allowed to send request to <br>$secoundusername</h4>';
                        a.style = 'color:crimson';
                    </script>
               ");
            }
            $stm4 = "SELECT * FROM $Ablocktablename  WHERE blockusername ='$secoundusername'";
            $res4 = mysqli_query($block_contact_db_connection, $stm4);
            $count4 = mysqli_num_rows($res4);
            if($count4 == 1){
                die("
                    <script>
                        const a =  document.querySelector('#spam');
                        a.innerHTML ='<h4>You friend $secoundusername<br> In your block list....</h4>';
                        a.style = 'color:crimson';
                    </script>
                ");
            }
            $stm1 = "SELECT*FROM $firstuser where friend_request_name = '$secoundusername'";
            $res1 = mysqli_query($request_db_connection, $stm1);
            $count1 = mysqli_num_rows($res1);
            $stm2 =  "SELECT*FROM $secounduser where friend_request_name = '$firstusername'";
            $res2 = mysqli_query($request_db_connection, $stm2);
            $count2 = mysqli_num_rows($res2);
            if($count1 === 0){
                if($count2 === 0){
                    $stm4 = "INSERT INTO $secounduser(request_friend_id,friend_request_name,friend_request_akm_id)
                    value($Bid, '$Busername', '$Bakmid')";
                    $res4 = mysqli_query($request_db_connection, $stm4);
                    ?>
                        <script>
                            const x = document.querySelector("#spam");
                            x.innerHTML = "<h4>The request send To.<br><?php echo $secoundusername; ?>.....</h4>";
                            x.style = "color:green";
                        </script>
                    <?php
                }else{
                    ?>
                        <script>
                            const x = document.querySelector("#spam");
                            x.innerHTML = "<h4>The request all ready send To.<br><?php echo $secoundusername; ?>.....</h4>";
                            x.style = "color:green";
                        </script>
                    <?php  
               }
            }else{
                ?>
                    <script>
                        const x = document.querySelector("#spam");
                        x.innerHTML = "<h4>The all ready reseved request.<br><?php echo $firstusername;?>.....</h4>";
                        x.style = "color:green";
                    </script>
                <?php  
            }
        }
    }
    class accept{
        private $AUsername;
        private $requestTableName;
        private $contactTableName;
        public function __construct($AUsername){
            $this->AUsername =$AUsername;
            $this->requestTableName = $AUsername."request";
            $this->contactTableName = $AUsername."contacts";
        }
        function friendrequest_display(){           // request display function
            global $server_connection;
            global $request_db_connection;
            $acceptUserTable = $this->requestTableName;
            $stm = "SELECT * FROM  $acceptUserTable";
            $res = mysqli_query($request_db_connection, $stm);
            $count = mysqli_num_rows($res);
            while($requestrow = mysqli_fetch_assoc($res)){
                $stm1 = "SELECT * FROM  newusers where username ='$requestrow[friend_request_name]'";
                $res1 = mysqli_query($server_connection, $stm1);
                $row1 = mysqli_fetch_assoc($res1);
                echo "
                    <p id='requestpara'><img src='userimages/$row1[dp]' class='rimage'alt='&#128512'>
                    id :- $requestrow[request_friend_id]<br>
                    username :- $requestrow[friend_request_name]<br>
                    Akm Id :- $requestrow[friend_request_akm_id]</p>
                    <form method = 'post'>
                        <input type='hidden' name ='busername' value='$requestrow[friend_request_name]'>
                        <input type='hidden' name ='buserid' value='$requestrow[request_friend_id]'> 
                        <input type='hidden' name ='buserakmid' value='$requestrow[friend_request_akm_id]'>
                        <input type='submit' name ='fallow' value='Accept' id='rsubmit'>
                        <input type='submit' name ='unfallow' value='Unfallow' id='rsubmit1'>
                    </form><hr>
                ";
            }
            if(isset($_POST["unfallow"])){
                $unfallowuserid = $_POST["buserid"];
                $unfallowusername = $_POST["busername"];
                echo $acceptUserTable;
                $stm2 = "DELETE FROM $acceptUserTable WHERE request_friend_id='$unfallowuserid'";
                $res2 = mysqli_query($request_db_connection, $stm2);
                ?>
                    <script>
                        const x =  document.querySelector("#spam");
                        x.innerHTML = "<h4>You rejected <?php echo $unfallowusername?> request</h4>";
                        x.style = "color:red";
                        alert("You rejected <?php echo $unfallowusername?> request..")
                        window.location = "add_contact.php";
                    </script>
                <?php
            }
            if(isset($_POST["fallow"])){
                $BUsername = $_POST["busername"];
                $this->fallow($BUsername);
            }
        }
        function fallow($BUsername){
            global $server_connection;
            global $contact_db_connection;
            global $request_db_connection;
            $fallowusername = $BUsername;
            $AUsername = $this->AUsername;
            $BUsercontactTableName = $BUsername."contacts";
            $AUsercontactTableName = $this->contactTableName;
            $AUserRequestTableName = $this->requestTableName;
            //a_user request table...
            $stmB = "SELECT * FROM $AUserRequestTableName WHERE friend_request_name='$fallowusername'";
            $resB = mysqli_query($request_db_connection, $stmB);
            $rowB = mysqli_fetch_assoc($resB);
            //inserting into first user contact table...
            $stm1 = "INSERT INTO $AUsercontactTableName(contactnameid, contactname, akmid)
                value($rowB[request_friend_id], '$rowB[friend_request_name]', '$rowB[friend_request_akm_id]')
            ";
            $res1 = mysqli_query($contact_db_connection, $stm1);
            //taking first user value
            $stmA = "SELECT * FROM newusers WHERE username = '$AUsername'";
            $resA = mysqli_query($server_connection, $stmA);
            $rowA = mysqli_fetch_assoc($resA);
            echo $rowA["username"];
            //inserting first user request to secound user request
            $stm2 =  "INSERT INTO $BUsercontactTableName(contactnameid, contactname, akmid)
                value($rowA[id], '$rowA[username]', '$rowA[akm_message]')
            ";
            $res2 = mysqli_query($contact_db_connection, $stm2);//inserting complited
            //starting deleting process
            $stm3 = "DELETE FROM $AUserRequestTableName WHERE friend_request_name =  '$fallowusername'";
            $res3 = mysqli_query($request_db_connection, $stm3);
            ?>
                <script>
                    window.location="add_contact.php";
                </script>
            <?php
        }
    }
    class contacts{
        private $AUsername;
        private $AUserContactsTableName;
        private $AUserBlockTableName;
        private $BUsercontactTableName;
        public function __construct($Auser){
            $this->AUsername = $Auser;
            $this->AUserContactsTableName = $Auser."contacts";
            $this->AUserBlockTableName = $Auser."block";
        }
        public function setbctablename($value){
            $this->BUsercontactTableName = $value."contacts";
        }
        function displayContacts(){
            global $contact_db_connection;
            global $server_connection;
            global $message_db_connection;
            $Atablename = $this->AUserContactsTableName;
            $Ausername = $this->AUsername;
            $stm3 = "SELECT * FROM newusers  WHERE username ='$Ausername'";
            $res3 = mysqli_query($server_connection, $stm3);
            $row3 = mysqli_fetch_assoc($res3);

            $stm1 = "SELECT * FROM $Atablename order by contactname";
            $res1 = mysqli_query($contact_db_connection, $stm1);
            while($row1 = mysqli_fetch_assoc($res1)){
                $stm2 = "SELECT * FROM newusers WHERE username = '$row1[contactname]'";
                $res2 = mysqli_query($server_connection, $stm2);
                $row2 = mysqli_fetch_assoc($res2);
                $lastmessage = new chatting($row3["username"], $row3["akm_message"], $row1["contactname"], $row1["akmid"]);
                $tablename = $lastmessage->Tableschecking();
                $lastmessagenumber = 0;
                if($tablename ==0 || $tablename ==1){
                    $lastmessagenumber = 1;
                }else{
                    $stm4 = "SELECT max(id) FROM $tablename";
                    $res4 = mysqli_query($message_db_connection, $stm4);
                    $row4 = mysqli_fetch_assoc($res4);
                    $lastmessagenumber = $row4["max(id)"];
                }
                echo "<p id='requestpara' class='streckimg'><a><img src='userimages/$row2[dp]' class='rimage'alt='&#128512'></a>
                        <b><j class='strecknum'>streck:- $row2[daily_streck]</j><br>login Time:- $row2[login_time]</b><br>
                        <b>username :- $row1[contactname]<br>akmid :- $row1[akmid]</b>

                    </p>
                    <form method = 'post'>
                    <input type='hidden' name ='blockname' value='$row1[contactname]'>
                    <input type='hidden' name ='blockid' value='$row1[contactnameid]'> 
                    <input type='hidden' name ='blockakmid' value=' $row1[akmid]'>
                    <a class='chatting_button' href='chatting.php?ausername=$row3[username]&aakmid=$row3[akm_message]&busername=$row1[contactname]&bakmid=$row1[akmid]#$lastmessagenumber'>Chat</a>
                    <input type='submit' name ='Block' value='Block       ' id='rsubmit1'>
                </form><br>"
                ;
                if(isset($_POST['Block'])){
                       $Busername = $_POST["blockname"];
                    $this->contactblock($Busername);
                }
            }
        }
        function contactblock($BUsername){
            $this->setbctablename($BUsername);
            $busercontacttablename = $this->BUsercontactTableName;
            $mainuser = $this->AUsername;
            //database connection
            global $block_contact_db_connection;
            global $contact_db_connection;
            //GATING TABLE NAME
            $contacttablename = $this->AUserContactsTableName;
            $blocktablename =$this->AUserBlockTableName;
            //inserting into block table
            $stm2 = "INSERT INTO $blocktablename(blockuserid, blockusername, blockakmid)
                SELECT contactnameid, contactname, akmid FROM usercontacts.$contacttablename
                where contactname = '$BUsername'
            ";
            $res2 = mysqli_query($block_contact_db_connection, $stm2);
            //deleting contact from Auser contact table
            $stm3 = "DELETE FROM $contacttablename WHERE contactname='$BUsername'";
            $res3 = mysqli_query($contact_db_connection, $stm3);

            $stm4 = "DELETE FROM $busercontacttablename WHERE contactname = '$mainuser'";
            $res4 = mysqli_query($contact_db_connection, $stm4);
            ?>
                <script>
                    window.location = "add_contact.php";
                </script>
            <?php
        }
        function blockcontactsdisplay(){
            global $block_contact_db_connection;
            global $contact_db_connection;
            global $server_connection;
            $AcontactsBlockTableName = $this->AUserBlockTableName;
            $AcontactsTableName = $this->AUserContactsTableName;
            $AUsername = $this->AUsername;
            $stm1 = "SELECT * FROM $AcontactsBlockTableName";
            $res1 = mysqli_query($block_contact_db_connection, $stm1);
            while($row1 = mysqli_fetch_assoc($res1)){
                $stm2 = "SELECT * FROM newusers where username = '$row1[blockusername]'";
                $res2 = mysqli_query($server_connection, $stm2);
                $row2 = mysqli_fetch_assoc($res2);
                echo "
                    <p id='requestpara'><img src='userimages/$row2[dp]' class='rimage' alt='&#128512'>
                    id :- $row1[blockuserid]<br>
                    username :-<pg id='usernama'> $row1[blockusername]</pg><br>
                    Akm Id :- $row1[blockakmid]</p>
                    <form method = 'post'>
                        <input type='hidden' name ='buserid' value='$row1[blockuserid]'>
                        <input type='hidden' name ='busername' value='$row1[blockusername]'> 
                        <input type='hidden' name ='buserakmid' value='$row1[blockakmid]'>
                        <input type='submit' name ='unblock' value='unblock' id='rsubmit'>
                    </form><hr>
                ";
                if(isset($_POST["unblock"])){
                    $Busername = $_POST["busername"];
                    $this->unblock($Busername);
                }
            }
        }
        function unblock($value){
            $this->setbctablename($value);
            global $contact_db_connection;
            global $block_contact_db_connection;
            echo $BlockUserName = $value;
            $A_user_con_tab_nam = $this->AUserContactsTableName;
            $A_use_blo_tab_nam = $this->AUserBlockTableName;
            $busercontacttablename = $this->BUsercontactTableName;
            $mainuser = $this->AUsername;
            $stm1 = "INSERT INTO $A_user_con_tab_nam(contactnameid, contactname, akmid)select blockuserid, blockusername, blockakmid from contactblock.$A_use_blo_tab_nam
                where blockusername ='$BlockUserName'"
            ;
            $res1 = mysqli_query($contact_db_connection, $stm1);
            $stm2 = "DELETE FROM $A_use_blo_tab_nam where blockusername='$BlockUserName'";
            $res2 = mysqli_query($block_contact_db_connection, $stm2);

            $stm3 = "INSERT INTO $busercontacttablename(contactnameid, contactname, akmid)
                select id, username, akm_message FROM indiansongs.newusers WHERE username = '$mainuser'"    
            ;
            $res3 = mysqli_query($contact_db_connection, $stm3);
            ?>
                <script>
                    window.location = "add_contact.php";
                </script>
            <?php
        }
    }


//    chatting started

  
   class accountchecking{

        private $fname;
        private $akmid;
        private $id;
        private $username;
        private $dp;
        private $email;
        private $about;
        private $streck;
        private $otp;
        private $otp_times;
        private $logintimes;
        private $dailystreckdate;
        public function __construct($fname, $akmid){
            $this->fname = $fname;
            $this->akmid = $akmid;
        }
        public function setotp($value){
            $this->otp = $value;
        }
        public function getotp(){
            return $this->otp;
        }
        public function setotp_times($value){
            $this->otp_times = $value;
        }
        public function getotp_times(){
            return $this->otp_times;
        }
        public function settotallogin($value){
            $this->logintimes = $value;
        }
        public function gettotallogin(){
            return $this->logintimes;
        }
        public function setdailystreckdate($value){
            $this->dailystreckdate = $value;
        }
        public function getdailystreckdate(){
            return $this->dailystreckdate;
        }
        public function setabout($value){
            $this->about=$value;
        }
        public function getabout(){
            return $this->about;
        }
        public function setname($value){
            $this->fname = $value;
        }
        public function getfname(){
            return $this->fname;
        }
        public function setakmid($value){
            $this->akmid = $value;
        }
        public function getakmid(){
            return $this->akmid;
        }
        public function setdp($value){
            $this->dp = $value;
        }
        public function getdp(){
            return $this->dp;
        }
        public function setusername($value){
            $this->username= $value;
        }
        public function getusername(){
            return $this->username;
        }
        public function setid($value){
            $this->id=$value;
        }
        public function getid(){
            return $this->id;
        }
        public function setstreck($value){
            $this->streck = $value;
        }
        public function getstreck(){
            return $this->streck;
        }
        function checkingaccountprocess(){
            global $server_connection;
            $name =  $this->getfname();
            $akmid = $this->getakmid();
            $account_check = "SELECT * FROM newusers where username = '$name' and akm_message = '$akmid' and  userStatus = 'unblock'";
            $account_check_res = mysqli_query($server_connection, $account_check);
            $account_row = mysqli_fetch_assoc($account_check_res);
            $account_count = mysqli_num_rows($account_check_res);
            $id="";
            if($account_count === 1){
               $id = $account_row['id'];
               $this->setakmid($account_row["akm_message"]);
               $this->setdp($account_row["dp"]);
               $this->setusername($account_row["username"]);
               $this->setid($id);
               $this->setabout($account_row["about"]);
               $this->setstreck($account_row["daily_streck"]);
               $this->setotp($account_row["otp"]);
               $this->setotp_times($account_row["atp_times"]);
               $this->settotallogin($account_row["Total_login"]);
               $this->setdailystreckdate($account_row["daily_streck_date"]);
                return 1;
            }else{
                return 0;
            }
        }
    }
    class daily_status{
        private $username; 
        private $password;
        public function __construct($username, $password){
            $this->username = $username;
            $this->password = $password;
        }
        function daily_update(){
            global $server_connection;
            $username = $this->username;
            $password = $this->password;
            $stm = "SELECT * from newusers where username = '$username' and password = '$password'";
            $res = mysqli_query($server_connection, $stm);
            $row = mysqli_fetch_assoc($res);
            $count = mysqli_num_rows($res);
            if($count == 1){
                $streckDate = $row["daily_streck_date"];
                $loginTime = $row["login_time"];
                if(empty($loginTime)){
                }else{
                    $today = substr($loginTime, 8,2);//0
                    $nextday = $today+1;    //1
                    if($streckDate == 0){
                        $stm = "UPDATE newusers set daily_streck_date = $today where username = '$username' and password = '$password'";
                        $res = mysqli_query($server_connection, $stm);
                        mysqli_errno($server_connection);
                        $streckDate = $today;
                    }    
                    $oddmonths = array(1,5,7,9,11);
                    $evenmonths = array(4,6,8,10,12);
                    $year = date("Y");
                    $presentmonth = date("m");
                    $searchoddmonth = in_array($presentmonth, $oddmonths);
                    $searchevenmonth = in_array($presentmonth, $evenmonths);
                    $totalstreck = $row["daily_streck"];
                    if($presentmonth == 2){
                        if($year%4 == 0){
                            if($today == 1){          //in leap year feb month
                                if($streckDate == 32){
                                    $stm1 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = $totalstreck+1 
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res1 = mysqli_query($server_connection, $stm1);
                                }elseif ($streckDate == 2 && $presentmonth == 2) {//2==1 condition is true.....
                                # code...
                                //nothing all update.....
                                }else{
                                    $stm2 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = 1
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res2 = mysqli_query($server_connection, $stm2);
                                }
                            }else{
                                if($streckDate == $today){// remaning days
                                    $stm4 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = $totalstreck+1 
                                    WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res4 = mysqli_query($server_connection, $stm4);
                                }elseif ($streckDate <= $today) {
                                    # code...
                                    $stm5 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = 1
                                    WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res5 = mysqli_query($server_connection, $stm5);
                                }
                            }
                        }elseif ($year%4 != 0){
                            if($today == 1){          //not leap year in feb month
                                    if($streckDate == 32){
                                        $stm6 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = $totalstreck+1 
                                            WHERE username = '$username' AND password = '$password';
                                        ";
                                        $res6 = mysqli_query($server_connection, $stm6);
                                    }elseif ($streckDate == 2 && $presentmonth == 2) {
                                    # code...
                                    //nothing all update.....
                                    }else{
                                    $stm6 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = 1
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res6 = mysqli_query($server_connection, $stm6);
                                }
                            }else{
                                if($streckDate == $today){ // remaning days
                                    $stm9 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = $totalstreck+1 
                                    WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res9 = mysqli_query($server_connection, $stm9);
                                }elseif ($streckDate <= $today) {
                                    $stm10 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = 1 
                                    WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res10 = mysqli_query($server_connection, $stm10);
                                }
                            }
                        }
                    }if($presentmonth == 3){        //leap year in march month.....
                        if($year%4 == 0){                   
                            if($today == 1){
                                if($streckDate == 30){
                                    $stm19 = "UPDATE newusers SET  daily_streck_date = 2, daily_streck = $totalstreck+1
                                        WHERE username = '$username' AND password ='$password'
                                    ";
                                    $res19 = mysqli_query($server_connection, $stm19);
                                }elseif ($streckDate == 2 && $presentmonth == 3) {
                                    # code...
                                    //nothing all update.....
                                }else{
                                    $stm20 = "UPDATE newusers SET  daily_streck_date = 2, daily_streck = 1
                                    WHERE username = '$username' AND password ='$password'
                                    ";
                                    $res20 = mysqli_query($server_connection, $stm20);
                                }
                            }else{
                                if($streckDate == $today){
                                    $stm23 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = $totalstreck+1 
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res23 = mysqli_query($server_connection, $stm23);
                                }elseif ($streckDate <= $today) {
                                    $stm24 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = 1 
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res24 = mysqli_query($server_connection, $stm24);
                                }
                            }
                        }elseif ($year%4 !=0) {             //not leap year march month....
                            if($today == 1){
                                if($streckDate == 29){
                                    $stm21 = "UPDATE newusers SET  daily_streck_date = 2, daily_streck = $totalstreck+1
                                        WHERE username = '$username' AND password ='$password'
                                    ";
                                    $res21 = mysqli_query($server_connection, $stm21);
                                }elseif ($streckDate == 2 && $presentmonth == 3) {
                                    # code...
                                    //nothing all update.....
                                  }else{
                                    $stm22 = "UPDATE newusers SET  daily_streck_date = 2, daily_streck = $totalstreck+1
                                        WHERE username = '$username' AND password ='$password'
                                    ";
                                    $res22 = mysqli_query($server_connection, $stm22);
                                }   
                            }else{
                                if($streckDate == $today){
                                    $stm25 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = $totalstreck+1 
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res25 = mysqli_query($server_connection, $stm25);
                                }elseif ($streckDate <= $today) {
                                    $stm25 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = 1 
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res25 = mysqli_query($server_connection, $stm25);
                                }
                            }
                        }
                    }
                    if($searchoddmonth == 1){ // all (1,5,7,9,11)months
                        if($today ==1){
                            if($streckDate ==31){
                                $stm11 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = $totalstreck+1 
                                    WHERE username = '$username' AND password = '$password';
                                ";
                                $res11 = mysqli_query($server_connection, $stm11);

                            }elseif ($streckDate == 2 && $searchoddmonth == 1) {
                                # code...
                            
                            }else{
                                $stm12 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = 1
                                WHERE username = '$username' AND password = '$password';
                                ";
                                $res12 = mysqli_query($server_connection, $stm12);
                            }
                        }else{
                            if($streckDate == $today){
                                $stm13 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = $totalstreck+1 
                                WHERE username = '$username' AND password = '$password';
                                ";
                                $res13 = mysqli_query($server_connection, $stm13);
                            }elseif ($streckDate <= $today) {
                                # code...
                                $stm14 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = 1 
                                WHERE username = '$username' AND password = '$password';
                                ";
                                $res14 = mysqli_query($server_connection, $stm14);
                            }
                        }
                    }elseif ($searchevenmonth == 1) {       //all (4,6,8,10,12) months
                        if($today ==1){
                            if($streckDate ==32){
                                $stm15 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = $totalstreck+1 
                                        WHERE username = '$username' AND password = '$password';
                                    ";
                                    $res15 = mysqli_query($server_connection, $stm15);
                            }elseif ($streckDate = 2 && $searchevenmonth == 1) {
                                # code...
                                //nothing all update.....
                            }else{
                                $stm16 = "UPDATE newusers SET daily_streck_date = 2, daily_streck = 1
                                WHERE username = '$username' AND password = '$password';
                                ";
                                $res16 = mysqli_query($server_connection, $stm16);
                            }
                        }else{
                            if($streckDate === $today){
                                $stm17 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = $totalstreck+1 
                                WHERE username = '$username' AND password = '$password';
                                ";
                                $res17 = mysqli_query($server_connection, $stm17);
                            }elseif ($streckDate <= $today) {
                                # code...
                                $stm18 = "UPDATE newusers SET daily_streck_date = $nextday, daily_streck = 1 
                                WHERE username = '$username' AND password = '$password';
                                ";
                                $res18 = mysqli_query($server_connection, $stm18);
                            }
                        }
                    }
                }
            }
        }
    }
    // chatting
    class chatting{
        private $mainuser;
        private $mainakmid;
        private $secounduser;
        private $secoundakmid;
        private $tablename1;
        private $tablename2;
        private $usermessage;
        public function __construct($mainuser,$mainakmid, $secounduser, $secoundakmid)
        {
            $this->mainuser = $mainuser;
            $this->mainakmid = $mainakmid;
            $this->secounduser = $secounduser;
            $this->secoundakmid = $secoundakmid;
            $this->tablename1 = $mainuser.$secounduser;
            $this->tablename2 = $secounduser.$mainuser;
        }
        public function setusermessage($value){
            $this->usermessage = $value;
        }
        public function getusermessage(){
            return $this->usermessage;
        }
        function Tableschecking(){//table checking
            //conecting to database
            global $server_connection;
            global $message_db_connection;
            //tables names;
            $table1 = $this->tablename1;
            $table2 = $this->tablename2;
            //searching tables;
            $tablearray = array($table1, $table2);
            $arraylength = count($tablearray);
            $numberoftables = 0;
            $tablename = '';
            for($i = 0; $i <= $arraylength-1; $i++){
                $stm = "SHOW TABLES LIKE '$tablearray[$i]'";
                $res = mysqli_query($message_db_connection, $stm);
                $tablecount = mysqli_num_rows($res);
                if($tablecount == 1){
                    $numberoftables++;
                    $tablename = $tablearray[$i];
                }
            }
            if($numberoftables == 1){

                return $tablename;

            }elseif ($numberoftables >1){

                return $numberoftables;

            }else{
                return 0;
            }
        }
        function creatingtable(){//creating the message table
            global $message_db_connection;
            $ausername = $this->mainuser;
            $busername = $this->secounduser;
            $counttable = $this->Tableschecking();
            $tablename1 = $this->tablename1;
            if($counttable == 0){
                $stm = "CREATE TABLE $tablename1(id bigint(40) auto_increment not null primary key, 
                    userid int(40),username varchar(70) not null,  akmid varchar(80) not null,
                    time1 varchar(40) not null, usermessage text(100) not null,
                    $ausername varchar(5) not null default 'unr', $busername varchar(5) not null default 'unr',
                    foreign key(userid) references indiansongs.newusers(id),
                    foreign key(username) references indiansongs.newusers(username),
                    foreign key(akmid) references indiansongs.newusers(akm_message)
                )";
                $res = mysqli_query($message_db_connection, $stm);
                return $tablename1;
            }elseif($counttable > 1){
                return $counttable;
            }else{
                return $tablename1;
            }
        }
        function InsertingMessage($messagevalue){//inserting message
            global $message_db_connection;
            $tablename = $this->creatingtable();
            $time = date("h:i:s");
            $message_username = $this->mainuser;
            $message_akmid = $this->mainakmid;
            $bmessage_username = $this->secounduser;
            $this->setusermessage($messagevalue);
            $message = $this->getusermessage();
            $tablecount = 0;
            if($tablename !=""){
                $stm1 = "SHOW TABLES LIKE '$tablename'";
                $res1 = mysqli_query($message_db_connection, $stm1); 
                $count1 = mysqli_num_rows($res1);
                $tablecount = 1;
            }else{
                $tablecount = -1;
            }
            $usertable1 = $this->tablename1;
            $usertable2 = $this->tablename2;
            $tablearray = (array) null;
            $tablearray[0]=$usertable1;
            $tablearray[1]=$usertable2;
            $tablematch = '';
            if($tablecount == 1){
                $arraylength = count($tablearray);
                for($i= 0; $i<=$arraylength-1; $i++){
                    $usertables = $tablearray[$i];
                    if($tablename == $usertables){
                        $tablematch = $usertables;
                    }
                }
            }
            if($tablematch != ''){
                $stm2 = "SELECT count(usermessage) FROM $tablematch";
                $res2 = mysqli_query($message_db_connection, $stm2);
                $row2 = mysqli_fetch_array($res2);
                $maxmessage = $row2['count(usermessage)'];
                $accountChecking = new accountchecking($message_username, $message_akmid);
                $accountChecking->checkingaccountprocess();
                $accountid = $accountChecking->getid();
                $accountusername = $accountChecking->getusername();
                $accountakmid = $accountChecking->getakmid();
                // inserting into message table
                ?>
                    <script>
                        const today = new Date();
                        var houre = today.getHours();
                        var min = today.getMinutes();
                    </script>
                <?php
                $stm3 = "INSERT INTO $tablematch(userid, username, akmid, time1, usermessage, $message_username, $bmessage_username )
                    value($accountid,'$accountusername', '$accountakmid',sysDate(), '$message', 1,0)"
                ;
                $res3 = mysqli_query($message_db_connection, $stm3);
            }
        }
        function displayMessage(){//display message
            $message_navagation = 1;
            $tablename = $this->creatingtable();
            $user = $this->mainuser;
            $buser = $this->secounduser;
            global $message_db_connection;
            global $server_connection;
            $stm = "SELECT * FROM $tablename";
            $res = mysqli_query($message_db_connection, $stm);


            // $stm7 ="SELECT max(id) FROM $tablename";
            // $res7 = mysqli_query($message_db_connection, $stm7);
            // $row7 = mysqli_fetch_assoc($res7);
            // $maxid = $row7["max(id)"];

            while($row = mysqli_fetch_assoc($res)){
                if($row["username"] == $user){
                    echo "<div class='message_div' id='$row[id]'>
                            <p class='message_para'>$row[usermessage]
                            <span class='message_time'> $row[time1]</span></p>
                        </div>
                    ";
                }else{
                    echo "<div class='message_div1' id='$message_navagation'>
                            <p class='message_para1'>$row[usermessage]</p>
                            <span class='message_time1'>$row[time1]</span>
                        </div>
                    ";
                }
                $message_navagation++;
            }
        }
        function messageseen(){//code for message seen
            $mainuser = $this->mainuser;
            $tablename = $this->creatingtable();
            global $message_db_connection;
            $stm = "SELECT $mainuser, id FROM $tablename";
            $res = mysqli_query($message_db_connection, $stm);
            while($row = mysqli_fetch_array($res)){
                if($row[$mainuser] == 0){
                    $stm1 = "UPDATE $tablename SET $mainuser = 1 WHERE id = $row[id]";
                    $res1 = mysqli_query($message_db_connection, $stm1);
                }
            }
        }
    }
    class notification{
        private $name;
        function __construct($value){
            $this->name = $value;
        }
        public function setname($value){
            $this->name = $value;
        }
        public function getname(){
            return $this->name;
        }
        function notification_process(){
            global $message_db_connection;
            global $server_connection;
            $name = $this->name;
            $stm ="SHOW TABLES LIKE '$name%'";//storing the first name tables
            $res = mysqli_query($message_db_connection, $stm);
            $count = mysqli_num_rows($res);
            $Chat_tables = (array)null;
            $i=0;
            $m=1;
            $n = 0;
            while($row = mysqli_fetch_assoc($res)){
                $Chat_tables[$i] = $row["Tables_in_user_messages ($name%)"];
                $i++;
            }
            $stm2 = "SHOW TABLES LIKE '%$name'";//storing the last name tables
            $res2 = mysqli_query($message_db_connection, $stm2);
            while($row2 = mysqli_fetch_assoc($res2)){
                $Chat_tables[$i] = $row2["Tables_in_user_messages (%$name)"];
                $i++;
            }
            $chat_array_length = count($Chat_tables);
            $unread_messages =0;
            $zeroid = 0;
            for($j = 0; $j<= $chat_array_length-1; $j++){
                $stm3 = "SELECT $name, id from  $Chat_tables[$j]";
                $res3 = mysqli_query($message_db_connection, $stm3);
                while($row3 = mysqli_fetch_assoc($res3)){
                    if($row3[$name] == 0){
                        $unread_messages++;
                        $zeroid = $row3["id"];
                        break;
                    }
                }
                $stm5 = "SELECT count($name) FROM $Chat_tables[$j] WHERE $name = 0";
                $res5 =  mysqli_query($message_db_connection, $stm5);
                $row5 = mysqli_fetch_assoc($res5);
                $totalmessages = $row5["count($name)"];
                
                $stm4 = "SELECT usermessage, time1, username, akmid FROM $Chat_tables[$j] where id = 
                    (SELECT max(id) FROM $Chat_tables[$j] where $name = 0)";
                ;
                $res4 = mysqli_query($message_db_connection, $stm4);
                $row4 =mysqli_fetch_assoc($res4);
                $count4 = mysqli_num_rows($res4);

                $stm6 = "SELECT * FROM newusers WHERE username = '$name'";
                $res6 = mysqli_query($server_connection, $stm6);
                $row6 = mysqli_fetch_assoc($res6);

                $stm7 ="SELECT max(id) FROM $Chat_tables[$j]";
                $res7 = mysqli_query($message_db_connection, $stm7);
                $row7 = mysqli_fetch_assoc($res7);
                $maxid = $row7["max(id)"];

                while($m<= $unread_messages){
                    echo "
                        <div class='notifications' id='$m'>
                            <div class='notifications1'>
                                <span id='notification_username'>$row4[username]<t id='total_message'>$totalmessages</t></span>
                                <span id='cancal' onclick='cancal($m)'>&times;</span>
                                <a href='chatting.php?ausername=$name&aakmid=$row6[akm_message]&busername=$row4[username]&bakmid=$row4[akmid]#$zeroid'><p>
                                $row4[usermessage]<span id='notification_time'>$row4[time1]</span></p></a>
                            </div>
                        </div>
                    ";
                    $m++;
                }
            }
        }
    }
    // Forgot Password
    abstract class changepassword{
        protected $username;
        private $password;
        protected $akmid;
        protected $tablename;
        private $otp;
        function getpassword(){
            return $this->password;
        }
        function setpassword($value){
            $this->password=$value;
        }
        function setotp($value){
            $this->otp = $value;
        }
        function getotp(){
            return $this->otp;
        }
        function __construct($username, $akmid)
        {
            $this->username = $username;
            $this->akmid = $akmid;
            $this->tablename = $username."akmaccount";
        }
        public function setusername($value){
            $this->username=$value;
        }
        public function setakmid($value){
            $this->akmid=$value;
        }
        abstract function GenatatingOtp();
        abstract function Sendlink();
    }
    class password extends changepassword{
        public function GenatatingOtp(){
            $username = $this->username;
            $akmid = $this->akmid;
            $account  =new accountchecking($username, $akmid);
            $account1 =$account->checkingaccountprocess();//account achecking function
            //getting data
            $username = $account->getusername();
            $akmid = $account->getakmid();
            $streck = $account->getstreck();
            $streckdate = $account->getdailystreckdate();
            $id= $account->getid();
            $totallogin =$account->gettotallogin();
            if($account1 ==1){
                $usernamelength = strlen($username);
                $akmidlength = strlen($akmid);
                $stringlength = $usernamelength+$akmidlength+date("Y");
                $last = $streck+$streckdate+$id+$totallogin+date("s");
                $sum = $last+$stringlength;
                $otp = rand(1000, $sum);
                return $otp;
            }else{
                return 0;
            }
        }
        public function Sendlink(){
            $username = $this->username;
            $akmid = $this->akmid;
            $tablename = $this->tablename;
            $otp= $this->GenatatingOtp();
            global $akm_account_db_connection;
            global $server_connection;
            if($otp != 0){
                $account  =new accountchecking($username, $akmid);
                $account1 =$account->checkingaccountprocess();
                $id = $account->getid();
                $otp_times = $account->getotp_times(); 
                if($account1 == 1){
                    $adminusername = 'Onlinehelpexam';
                    $ak_message = "<p id='akm_sms'>You otp number is $otp...<br>Change message account Password.<br><a href='changepassword.php'>Change Password.</a></p>";
                    $adminakmid = "onlinehelpexam@akm.online_message";
                    $subject_akm ="<p id='akm_sbj'>Forgot Password</p>";
                    $report = "Sgakm";
                    $footer ="<p id='akm_foot'>This message is system generated, Ak_message.<br> Done`t replay for this ak_message..</p>";
                    $time = date("Y/M/d,H:i");
                    $stm4 = "SHOW TABLES LIKE '$tablename'";
                    $res4 = mysqli_query($akm_account_db_connection, $stm4);
                    $count4 = mysqli_num_rows($res4);
                    if($count4 == 1){
                        $ak_message = mysqli_real_escape_string($server_connection, $ak_message);
                        $subject_akm = mysqli_real_escape_string($server_connection,  $subject_akm);
                        $footer = mysqli_real_escape_string($server_connection, $footer);
                        $stm1 = "INSERT INTO $tablename(username, userid, time1, subject_akm, ak_message, akm_report, toakmid, seen, footer, message_status, fromakmid)
                            value('$adminusername', $id, sysdate(), '$subject_akm', '$ak_message', '$report', '$akmid', 0, '$footer','Reseved','$adminakmid')"
                        ;
                        $res1 = mysqli_query($akm_account_db_connection, $stm1);
    
                        $stm2 = "UPDATE newusers SET otp = $otp, atp_times = $otp_times+1 WHERE username = '$username' and akm_message='$akmid' and userStatus='unblock'";
                        $res2 = mysqli_query($server_connection, $stm2); 
    
                        $stm3 = "INSERT INTO Onlinehelpexam(username, userid, time1, subject_akm, ak_message, akm_report, akmid, seen, footer, message_status)
                            value('$username', $id, '$time', '$subject_akm', '$ak_message', '$report', '$akmid', 0, '$footer','Sent')"
                        ;
                        $res3 = mysqli_query($server_connection, $stm3); 
                        return 1;
                    }
                }
            }else{

                return 0;
            }
        }
    }//akm password update
    class akmpassword_update{
        private $username, $password, $akmid, $accountPassword;
        function __construct($username, $akmpassword, $account_password, $akmid){
            $this->username =$username;
            $this->password =$akmpassword;
            $this->accountPassword = $account_password;
            $this->akmid =$akmid;            
        } 
        function password_update(){
            global $server_connection;
            $akmid = $this->akmid;
            $username = $this->username;
            $password= $this->password;
            $accountPassword = $this->accountPassword;
            $stm1 ="SELECT * FROM newusers where username ='$username' and password='$accountPassword' and userStatus='unblock'";
            $res1 =mysqli_query($server_connection, $stm1);
            $row1 =mysqli_fetch_assoc($res1);
            $count1 =mysqli_num_rows($res1); 
            $stm2 ="SELECT * FROM akmaccounts where username ='$row1[username]' AND akmid ='$akmid' AND userStatus='unblock'";
            $res2 =mysqli_query($server_connection, $stm2);
            $row2 =mysqli_fetch_assoc($res2);
            $count2 =mysqli_num_rows($res2); 
            if($count1 ==1 && $count2==1){
                $numberupdate=0;
                if(($row1["username"]==$row2["username"])&&($row1["akm_message"]==$row2["akmid"])){
                    $stm3 ="UPDATE akmaccounts SET password='$password',password_update_times=1 WHERE username ='$username' AND akmid='$akmid'  AND userStatus='unblock'";
                    $res3 =mysqli_query($server_connection, $stm3);
                    if(!$res3){
                        ?>  
                            <script>
                                span.innerHTML='password not updated for akmaccount';
                                span.style='color:crimson';
                            </script>
                        <?php
                    }else{
                        ?>  
                            <script>
                                span.innerHTML='password Updated for akm account';
                                span.style='color:green';
                            </script>
                        <?php
                        $numberupdate = 1;
                    }
                    if($numberupdate ==1){
                        $stm4 ="UPDATE newusers SET akmid_password='$password' WHERE username='$username' AND akm_message='$akmid' AND userStatus='unblock'";
                        $res4 =mysqli_query($server_connection, $stm4); 
                        if(!$res4){
                            ?>  
                                <script>
                                    span.innerHTML='password not updated for you account';
                                    span.style='color:crimson';
                                </script>
                            <?php
                        }else{
                            ?>  
                                <script>
                                    span.innerHTML='password Updated for your account';
                                    span.style='color:green';
                                    var countsec = 0;
                                    var counttime=setInterval(()=>{
                                        countsec++;
                                        if(countsec==2){
                                            window.location.reload();
                                        }
                                    },1000)
                                </script>
                            <?php
                        }
                    }else{
                        ?>  
                            <script>
                                span.innerHTML='password not updated for akm account';
                                span.style='color:crimson';
                            </script>
                        <?php
                    }
                }else{
                    ?>  
                        <script>
                            span.innerHTML='account not matching get help';
                            span.style='color:crimson';
                        </script>
                    <?php
                }
            }elseif(($count1==1&&$count2==0)||($count1==0&&$count2==1)){
                ?>  
                    <script>
                        span.innerHTML='Incurrect Account password';
                        span.style='color:crimson';
                    </script>
                <?php
            }else{
                ?>  
                    <script>
                        span.innerHTML='account not matching';
                        span.style='color:crimson';
                    </script>
                <?php
            }
        }
    }
    // akm accounts
    class akmaccounts{
        private $akmid;
        private $password;
        function __construct($akmid, $password){
            $this->akmid=$akmid;
            $this->password=$password;
        }
        function akm_account_check(){   //account checking
            global $server_connection;
            $id = $this->akmid;
            $password =$this->password;
            $accountcheck_stm = "SELECT * FROM akmaccounts WHERE akmid='$id' and password = '$password' and userStatus='unblock'";
            $accountcheck_res = mysqli_query($server_connection, $accountcheck_stm);
            $accountcheck_count = mysqli_num_rows($accountcheck_res);
            $accountcheck_row = mysqli_fetch_assoc($accountcheck_res);

            $accountcheck_stm1 = "SELECT * FROM newusers WHERE akm_message='$id' and akmid_password ='$password' and userStatus='unblock'";
            $accountcheck_res1 = mysqli_query($server_connection, $accountcheck_stm1);
            $accountcheck_count1 = mysqli_num_rows($accountcheck_res1);
            if($accountcheck_count==1 && $accountcheck_count1 ==1){
                return $accountcheck_row["username"];
            }elseif(($accountcheck_count ==1 && $accountcheck_count1 ==0) || ($accountcheck_count ==0 && $accountcheck_count1 ==1)){
                return 2;
            }else{
                $accountcheck_stm1 = "SELECT * FROM akmaccounts WHERE akmid='$id' and password = '$password' and userStatus='block'";
                $accountcheck_res1 = mysqli_query($server_connection, $accountcheck_stm1);
                $accountcheck_count1 = mysqli_num_rows($accountcheck_res1);
                if($accountcheck_count1 == 1){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
        function login_to_mainPage(){
            global $server_connection;
            $accountCheck = $this->akm_account_check();
            $akmid = $this->akmid;
            $akmpassword =$this->password;
            if($accountCheck == 0){
                ?>
                    <script>
                        y.style ="color:crimson";
                        y.innerHTML = "Incurrect id and Password";
                    </script>
                <?php
            }elseif($accountCheck == 1) {
                ?>
                    <script>
                        y.style ="color:crimson";
                        y.innerHTML = "Your Akm Account is blocked";
                    </script>
                <?php
            }elseif($accountCheck ==2){
                ?>
                    <script>
                        y.style ="color:crimson";
                        y.innerHTML = "Can not process you account get <a href='#'>Help</a>";
                    </script>
                <?php
            }else{
                $_SESSION["akmid"] = $akmid;
                $_SESSION["username"] = $accountCheck;
               ?>
                    <script>
                        window.location="akm_mainPage.php";
                    </script>
               <?php
            }
        }
    } 
    final class akmMainAccount{
        private $name;
        private $akmid;
        private $akmTablename;
        private $akm_report;
        public function setakm_report($value){
            $this->akm_report = $value;
        }
        public function gerakm_report(){
            return $this->akm_report;
        }
        public function __construct($name, $akmid){
            $this->name = $name;
            $this->akmid =$akmid;
            $this->akmTablename =$name."akmaccount";
        }
        protected function akm_account_checking(){
            global $akm_account_db_connection;
            global $server_connection;
            $name = $this->name;
            $akmid = $this->akmid;
            $tablename = $this->akmTablename;
 
            $stm1 ="SELECT * FROM akmaccounts WHERE username='$name' and akmid ='$akmid' and userStatus='unblock'";
            $res1 =mysqli_query($server_connection, $stm1);
            $count1 = mysqli_num_rows($res1);

            $stm2 = "SHOW TABLES LIKE '$tablename'";
            $res2 =mysqli_query($akm_account_db_connection, $stm2);
            $count2 =mysqli_num_rows($res2);
            if($count1 ==1 && $count2 ==1){
                return $name;
            }else{
                if($count1 ==0){
                    return "akm account block";
                }elseif($count2 ==0){
                    return "No akm account";
                }elseif($count1 ==0 && $count1 ==0){
                    return "No akm Account";
                }
            }
        }
        public function display_ak_messages($name){
            global $akm_account_db_connection;
            global $server_connection;
            $account_check_res = $this->akm_account_checking();
            $akmid = $this->akmid;
            if($account_check_res == $name){
                $tablename = $this->akmTablename;
                $stm1 ="SELECT*FROM $tablename  order by id desc";
                $res1 =mysqli_query($akm_account_db_connection, $stm1);
                $count1 = mysqli_num_rows($res1);
                ?>
                    <table id="akm_table">
                <?php
                    $unseen_messages = (array)null;
                    $unseen_messages_count = 0;
                    while($row1 = mysqli_fetch_assoc($res1)){
                        $image ="";
                        $subject =$row1["subject_akm"];
                        $shortsub =substr($subject,16,-4);

                        //code for getting akmid and wether the message send or reseved
                        $akmidMP='';

                        $Mmsstatus ="";
                        if($row1['message_status']=='Send'){

                            $akmidMP=$row1["toakmid"];

                            $Mmsstatus ="Sent To:-";

                        }elseif($row1['message_status']=='Reseved'){

                            $akmidMP =$row1["fromakmid"];

                            $Mmsstatus ="Reseved From:-";

                        }elseif($row1['message_status']=='failed'){

                            $Mmsstatus="Failed To Sent:-";

                            $akmidMP=$row1["toakmid"];
                        }
                        //codding for selecting for image when the in normal or spam
                        if($row1["akm_report"] == "Sgakm"){

                            $image="websitthings\sit logo.png";

                        }elseif($row1["akm_report"] == "Spam"){

                            $image ="websitthings/th.jpg";

                        }else{
                            $mainaccountimage = new accountchecking($row1["username"], $akmidMP);
                            $mainaccountimage->checkingaccountprocess();
                            $imagename = $mainaccountimage->getdp();
                            $image = "userimages/$imagename";
                        }
                        if($row1["seen"] ==0){
                            $unseen_messages[$unseen_messages_count] = $row1["id"];
                            $unseen_messages_count++;
                        }
                        ?>
                            <tr class="akm_table_tr" id="<?php echo $row1["id"];?>" onclick="akmnav(<?php echo $row1['id']; ?>)">
                                <td id="akm_table_image"><img src="<?php echo $image;?>" width="20px" height="20px" id="akm_message_image"></td>
                                <td id="akm_table_username"><a id="message_body"><?php echo $Mmsstatus; ?></a></td>
                                <td id="akm_table_username"><a id="message_body"><?php echo $akmidMP;?></a></td>
                                <td id="akm_table_message"><a id="message_body">Sub:- <?php echo  $shortsub; ?></a></td>
                                <td id="akm_table_time"><a id="message_body">Time:-<?php echo $row1["time1"]; ?></a></td>
                                <td id="akm_table_report"><a id="message_body">M Status:- <?php echo $row1["message_status"];?></a></td>
                                <td id="report_symble"><a class="report_spam">&#9885;<p class="report_message_cont">spam</p></a></td>
                            </tr>
                        <?php
                    }
                    ?>
                        </table>
                        <script>
                            function akmnav(value){
                                var value=value;
                                var idnumber = value.toString();
                                window.location="akm_message_see.php?id="+idnumber;
                            }
                        </script>
                    <?php
            }else{
                ?>
                    <script>
                        const akm_apan = document.querySelector("#akm_span");
                        akm_apan.innerHTML ="You have a problem with your account akm account....";
                        akm_apan.style="color:crimson";
                    </script>
                <?php
            }
            for($j=0; $j<=count($unseen_messages)-1; $j++){
                $unseen_id = $unseen_messages[$j];
                ?>
                    <script>
                        var unseenid = document.getElementById(<?php echo $unseen_id;?>);
                        unseenid.style="font-weight: bolder; background: linear-gradient(89deg,rgb(96, 158, 147), rgb(46, 83, 104), white)";
                    </script>
                <?php
            }
        }
        public function see_ak_message($ak_message_id, $name){
            global $akm_account_db_connection;
            global $server_connection;
            $username = $this->akm_account_checking();
            $akmid =$this->akmid;
            $tablename ="";
            if($username != $name){
                ?>
                    <script>
                        const span_message = document.querySelector("#akm_span");
                        span_message.innerHTML = "<?php echo $username;?>";
                        span_message.style='color:crimson';
                    </script>
                <?php
            }else{
                $tablename1 = $this->akmTablename;
                $stm1 ="SHOW TABLES LIKE '$tablename1'";
                $res1 =mysqli_query($akm_account_db_connection, $stm1);
                $count1 =mysqli_num_rows($res1);
                if($count1 ==1){
                    $tablename =$tablename1;
                }else{
                    ?>
                        <script>
                            const span_message = document.querySelector("#akm_span");
                            span_message.innerHTML = "You have a problam with your account.....";
                            span_message.style='color:crimson';
                        </script>
                    <?php
                }
            }
            $stm2 ="SELECT * FROM $tablename  WHERE id =$ak_message_id";
            $res2 =mysqli_query($akm_account_db_connection, $stm2);
            $row2 =mysqli_fetch_assoc($res2);
            //select to and from statement
            $statement = "";
            $akmidTD = "";
            if($row2["message_status"]=="Send"){
                $statement = 'To';
                $akmidTD=$row2["toakmid"];
            }elseif($row2["message_status"]=="Reseved"){
                $statement="From";
                $akmidTD=$row2["fromakmid"];
            }else{
                $statement="Failed";
                $akmidTD="Failed To Sent";
            }
            ?>
                <div id='see_message_body'>
                    <div id='ak_message_from'>
                        <p id="from_paragra"><b id="akm_spam_header"><?php echo $statement; ?>:- </b> <?php echo @$akmidTD;?><span id='down_arrow'  onclick="Showditails()">&#8595;</span></p>
                        <a><p id="spam_report">
                           <form id='form-report' method='post'><button name='spam' id='spambutton'><a id='symbol1'><input type='radio' id='spam' name="Report"></a>Spam</button>
                            <button name='normal' id='normalbutton'><a id='symbol2'><input type='radio' id='normal' name="Report"></a>Normal</button></form>
                            <a id='symbol3'><input type='radio' id='sgakm' name='Sgakm'><spam class='cont'><b>Sgakm</b></spam></a>
                            <?php
                                if(isset($_POST["spam"])){
                                    $spamreportStm ="UPDATE $tablename SET akm_report='Spam' WHERE id =$ak_message_id";
                                    $spamreportRes =mysqli_query($akm_account_db_connection, $spamreportStm);
                                    ?>
                                        <script>
                                            window.location="akm_message_see.php?id=<?php echo $ak_message_id?>"
                                            window.reload();
                                        </script>
                                    <?php
                                }
                                if(isset($_POST["normal"])){
                                    $spamreportStm1 ="UPDATE $tablename SET akm_report='Normal' WHERE id =$ak_message_id";
                                    $spamreportRes1 =mysqli_query($akm_account_db_connection, $spamreportStm1);
                                    ?>
                                        <script>
                                            window.location="akm_message_see.php?id=<?php echo $ak_message_id?>"
                                            window.reload();
                                        </script>
                                    <?php
                                }
                            ?>
                        </p></a>
                        <?php
                        @$this->setakm_report($row2["akm_report"]);
                         if(@$row2["akm_report"] =="Sgakm"){
                            ?>
                                <script>
                                    const option1 = document.querySelector("#spambutton");
                                    const option2 = document.querySelector("#normalbutton");
                                    const option31 = document.querySelector("#sgakm");
                                    option1.style='display:none';
                                    option2.style='display:none';
                                    option31.checked=true;
                                </script>
                            <?php
                        }elseif(@$row2["akm_report"]=="Spam"){
                            ?>
                                <script>
                                    const spam = document.querySelector("#spam");
                                    const normal = document.querySelector("#normal");
                                    const option3 = document.querySelector("#symbol3");
                                    option3.style='display:none';
                                    spam.checked=true;
                                </script>
                            <?php
                        }elseif(@$row2["akm_report"]=="Normal"){
                            ?>
                                <script>
                                     const option3 = document.querySelector("#symbol3");
                                    option3.style='display:none';
                                    normal.checked=true;
                                </script>
                            <?php
                        }
                        ?>
                    </div>
                    <div id="ak_message_from_content">
                        <p  id="ak_message_subject_text"><b id="akm_spam_header">From:- </b> <?php echo @$row2["fromakmid"];?><span id='down_arrow' onclick="hideditails()">&#8593;</span> </p>
                        <p  id="ak_message_subject_text"><b id="akm_spam_header">To:- </b> <?php echo @$row2['toakmid']; ?></p>
                        <p  id="ak_message_subject_text"><b id="akm_spam_header">Name:- </b> <?php echo @$row2["username"];?></p> 
                        <p  id="ak_message_subject_text"><b id="akm_spam_header">Time:- </b> <?php echo @$row2["time1"];?></p>
                    </div>
                    <div id="ak_message_subject">
                        <span id="akm_spam_header"><b>Subject:-</b></span>
                            <p id="ak_message_subject_text" class='akm_subject'><?php 
                                echo @$row2["subject_akm"];
                            ?></p>
                    </div>
                    <div  id="ak_message_MMS_text">
                        <span id="akm_spam_header">Message:-</span>
                        <p  id="ak_message_subject_text">
                            <?php 
                                echo @$row2["ak_message"];
                                if(@$row2["ak_message"]==""){
                                    ?>
                                        <script>
                                            const monkey = document.querySelector("#marquee");
                                            monkey.innerHTML="No AKM";
                                            monkey.style='color:gray';
                                        </script>
                                    <?php
                                }
                            ?>
                        </p>
                    </div>
                    <div id="ak_message_footer">
                        <span id="akm_spam_header">Footer:-</span>
                        <p id="ak_message_subject_text" class='akm_footer'><?php 
                            echo @$row2["footer"];
                        ?>
                    </div>
                    <footer><div id="footer_first_div">
                        <a id="forword_ak_message" onclick="forword()">Forword</a>
                        <a id='replay_ak_message' onclick="replayakm()">Replay</a>
                    </div></footer>
                </div>
                <script>
                    function Showditails(){
                        $("#ak_message_from").slideUp();
                        $("#ak_message_from_content").slideDown(1000);
                    }
                    function hideditails(){
                        $("#ak_message_from").slideDown(1000);
                        $("#ak_message_from_content").slideUp(1000);
                    }
                </script>
            <?php
            $stm3 = "UPDATE $tablename SET seen=1 WHERE id=$ak_message_id";
            $res3 = mysqli_query($akm_account_db_connection, $stm3);
        }
        function ak_message_send($message, $subject, $footer, $Toakmid,$clintname){
            global $akm_account_db_connection;
            global $server_connection;
            $username = $this->akm_account_checking();
            $akmid = $this->akmid;
            $time=date("Y/M/d h:i");
            $tablename = $this->akmTablename;
            $accountDetails = "SELECT max(id) FROM $tablename";
            $accountDetailsRes = mysqli_query($akm_account_db_connection, $accountDetails);
            $accountDetailsRow =mysqli_fetch_assoc($accountDetailsRes);
            $messageId = $accountDetailsRow["max(id)"]+1;
            $userid=0;
            if($clintname === $username){    
                $akmaccount_stm = "SELECT * FROM akmaccounts where username='$clintname' and userStatus='unblock'";
                $akmaccount_res = mysqli_query($server_connection, $akmaccount_stm);
                $akmaccount_row = mysqli_fetch_assoc($akmaccount_res);
                $mainaccount = new accountchecking($username, $akmid);
                $mainaccount->checkingaccountprocess();
                $userid = $mainaccount->getid();
                if(empty($akmaccount_row["accountname"])){
                    //url notes
                    $notes = "Not Finding the account name";
                    $NotesName =$username;
                    $Notesakmid=$akmid;
                    //ak message
                    $AdminUserName ='Onlinehelpexam';
                    $AdminUserId =$userid;
                    $time =date("Y/M/d,H:i");
                    $subject_akm ="<p id='akm_sbj'>You have a problam in akm account..</p>";                                                                         //add url notes
                    $ak_message ="<p id='akm_sms'>You have a problam with your Username<br>We are working on the problam We will get back to you in 1 week..<br><a href='#'>Click</a> to rase The complent..</p>";
                    $akm_report ='Sgakm';
                    $AdminAkmId='onlinehelpexam@akm.online_message';
                    $seen=0;
                    $akm_footer ="<p id='akm_foot'>This is System generated ak message bo not replay or forword to this message.</p>";
                    $message_status ="Recived";
                    $ak_message_id =00;
                    $subject_akm =mysqli_real_escape_string($server_connection ,$subject_akm);
                    $ak_message =mysqli_real_escape_string($server_connection ,$ak_message);
                    $akm_footer =mysqli_real_escape_string($server_connection ,$akm_footer);
                    $ErrorStm ="INSERT INTO $tablename(username, userid, time1, subject_akm, ak_message, akm_report, akmid, seen, footer, message_status, ak_message_id)
                        value('$AdminUserName', $AdminUserId, '$time', '$subject_akm',sysdate() '$ak_message', '$akm_report', '$AdminAkmId', $seen, '$akm_footer', '$message_status', $ak_message_id)"
                    ;
                    $ErrorRes =mysqli_query($akm_account_db_connection, $ErrorStm);
                    ?>
                        <script>
                                var count =0;
                                var time = setInterval(()=>{
                                    count++;
                                    if(count==3){
                                        window.location='akm_message.php';
                                        clearInterval(time);
                                    }                                           
                                },1000);
                        </script>
                    <?php
                }else{
                   //buserAccount details..
                    $BAccountDetailsStm = "SELECT * FROM akmaccounts WHERE akmid ='$Toakmid'";
                    $BAccountDetailsRes =mysqli_query($server_connection, $BAccountDetailsStm);
                    $BAccountDetailsRow =mysqli_fetch_assoc($BAccountDetailsRes);
                    $BAccountDetailsCount =mysqli_num_rows($BAccountDetailsRes);
                    if($BAccountDetailsCount ==1){
                        //chacking Bloack account
                        $accountBlockStm = "SELECT * FROM akmaccounts WHERE akmid ='$Toakmid' AND userStatus='unblock'";
                        $accountBlockRes =mysqli_query($server_connection,$accountBlockStm);
                        $accountRow =mysqli_fetch_assoc($accountBlockRes);
                        $accountCount =mysqli_num_rows($accountBlockRes);
                        if($accountCount ==1){
                            if(empty($BAccountDetailsRow["accountname"])){
                                ?>
                                    <script>
                                        var alert1 = document.querySelector("#mani_spam");
                                        alert1.style='color: white;background-color:crimson;border-radius:10px;padding:5px';                                                           //add url
                                        alert1.innerHTML="No login's from <?php  echo $Toakmid?> account.";
                                    </script>
                                <?php
                                //gatting buser details for failed message
                                $buseraccountFailStm = new accountchecking($accountRow["username"], $Toakmid);
                                $buseraccountFailStm->checkingaccountprocess();
                                $Bfuserid=$buseraccountFailStm->getid();
                                $ASendingakmstmFailed = "INSERT INTO  $tablename(username, userid, time1, subject_akm, ak_message, akm_report, toakmid, seen, footer, message_status, ak_message_id, fromakmid)
                                    value('$accountRow[username]', $Bfuserid, sysdate(), '$subject', '$message', 'Normal', '$Toakmid', 0, '$footer', 'failed', 00, '$akmid');
                                ";
                                $ASendingakmResFailed =mysqli_query($akm_account_db_connection, $ASendingakmstmFailed);
                                ?>
                                    <script>
                                            var count =0;
                                             var time = setInterval(()=>{
                                                count++;
                                                if(count==3){
                                                    clearInterval(time);  window.location='akm_message.php';
                                                  
                                                }                                           
                                            },1000);
                                    </script>
                                <?php
                            }else{
                                //buserAccount name
                                $BuserAccountName =$BAccountDetailsRow["accountname"];
                                //Sending ak message Details for buser
                                $AUserName =$username;
                                $AUserId =$userid;
                                $time = $time;
                                $BUserId=0;
                                $sentSubject ="<p id='akm_sub'>$subject</p>";
                                $sendMessage ="<p id='akm_sms'>$message</p>";
                                $SendReport ="Normal";
                                $AuserAkmId ="$akmid";
                                $seen =0;
                                $SentFooter ="</p id='akm_foot'>$footer</p>";
                                $BSendMessageStatus ="Reseved";
                                $ak_message_id11 =  $messageId;
                                $sentSubject = mysqli_real_escape_string($server_connection,  $sentSubject);
                                $sendMessage = mysqli_real_escape_string($server_connection, $sendMessage);
                                $SentFooter = mysqli_real_escape_string($server_connection,  $SentFooter);
                                $Sendingakmstm = "INSERT INTO $BuserAccountName(username, userid, time1, subject_akm, ak_message, akm_report, fromakmid, seen, footer, message_status, ak_message_id, toakmid)
                                    value('$AUserName', $userid, sysdate(), '$sentSubject', '$sendMessage', '$SendReport', '$AuserAkmId', $seen, '$SentFooter', '$BSendMessageStatus', $ak_message_id11, '$Toakmid')
                                ";
                                $SendingRes =mysqli_query($akm_account_db_connection, $Sendingakmstm);
                                
                                $CheckingMessageStm = "SELECT * FROM $BuserAccountName WHERE username='$username' AND ak_message_id =$ak_message_id11 AND userid =$userid";
                                $CheckingMessageRes =mysqli_query($akm_account_db_connection, $CheckingMessageStm);
                                $CheckingMessageCount =mysqli_num_rows($CheckingMessageRes);
                                //Account name;
                                $AAccountName =$akmaccount_row["accountname"];
                                //account buser account Details
                                $Bmainaccount = new accountchecking($BAccountDetailsRow["username"], $Toakmid);
                                $Bmainaccount->checkingaccountprocess();
                                $BUserName = $BAccountDetailsRow["username"];
                                $BUserId =$Bmainaccount->getid();
                                $Buserid=$BUserId;
                                $time=$time;
                                $sentSubject ="<p id='akm_sub'>$subject</p>";
                                $sendMessage ="<p id='akm_sms'>$message</p>";
                                $BSendReport ="Normal";
                                $BUserAkmId =$Toakmid;
                                $seen =0;
                                $SentFooter ="</p id='akm_foot'>$footer</p>";
                                $messagrStatus ="Send";
                                $ak_message_id =00;
                                $sentSubject = mysqli_real_escape_string($server_connection,  $sentSubject);
                                $sendMessage = mysqli_real_escape_string($server_connection, $sendMessage);
                                $SentFooter = mysqli_real_escape_string($server_connection,  $SentFooter);
                                if($CheckingMessageCount == 1){
                                    $ASendingakmstm = "INSERT INTO  $AAccountName(username, userid, time1, subject_akm, ak_message, akm_report, toakmid, seen, footer, message_status, ak_message_id, fromakmid)
                                        value('$BUserName', $BUserId, sysdate(), '$sentSubject', '$sendMessage', '$BSendReport', '$BUserAkmId', $seen, '$SentFooter', '$messagrStatus', 00 ,'$akmid');
                                    ";
                                    $ASendingakmRes =mysqli_query($akm_account_db_connection, $ASendingakmstm);
                                        ?>
                                            <script>
                                                var alert1 = document.querySelector("#mani_spam");
                                                alert1.style='color: white;background-color:green;border-radius:10px;padding:5px';                                                           //add url
                                                alert1.innerHTML="Sent To <?php  echo $Toakmid?>";
                                                $("#mani_spam").show(900);
                                                $("#mani_spam").hide(2000);
                                                var count =0;
                                                var time = setInterval(()=>{
                                                    count++;
                                                    if(count==3){
                                                        clearInterval(time);   window.location='akm_message.php';
                                                    
                                                    }                                           
                                                },1000);
                                            </script>
                                        <?php
                                }else{
                                    $ASendingakmstm = "INSERT INTO  $AAccountName(username, userid, time1, subject_akm, ak_message, akm_report, toakmid, seen, footer, message_status, ak_message_id, fromakmid)
                                        value('$BUserName', $BUserId, sysdate(), '$sentSubject', '$sendMessage', '$BSendReport', '$BUserAkmId', $seen, '$SentFooter', 'Pending', 00, '$akmid');
                                    ";
                                    $ASendingakmRes =mysqli_query($akm_account_db_connection, $ASendingakmstm);
                                    ?> 
                                        <script>
                                           var count =0;
                                           var time = setInterval(()=>{
                                                count++;
                                                if(count==3){
                                                    clearInterval(time);
                                                    window.location='akm_message.php';
                                                    
                                                }                                           
                                           },1000);
                                        </script>
                                    <?php
                                }
                            }
                        }else{
                            ?>
                                <script>
                                    var alert1 = document.querySelector("#mani_spam");
                                    alert1.style='color: white;background-color:crimson;border-radius:10px;padding:5px';                                                           //add url
                                    alert1.innerHTML="<?php  echo $Toakmid?> Block";
                                </script>
                            <?php
                        }
                    }else{
                        ?>
                            <script>
                                var alert1 = document.querySelector("#mani_spam");
                                alert1.style='color: white;background-color:crimson;border-radius:10px;padding:5px';                                                           //add url
                                alert1.innerHTML="Unknow akmid <?php  echo $Toakmid?>";
                            </script>
                        <?php
                    }
                }
            }else{
                ?>
                    <script>
                        var alert1 = document.querySelector("#mani_spam");
                        alert1.style='color:crimson';                                                           //add url
                        alert1.innerHTML="We can't process your account..<br>failed to sent ak message...<a href='ErrorFolder/Errorakm.php'>Get Help</a>";
                    </script>
                <?php
            }
        }
    }
?>