<?php 
    error_reporting(0);

    $server_name = "localhost";

    $server_username = "root";

    $server_password = "K.harsha@2000";

    $server_database = "indiansongs";

    $contact_db = "usercontacts";

    $request_friend_db = "friendrequest";

    $messages_db = "user_messages";

    $blockcontact = "contactblock";

    $akmaccount = "akmaccounts";

    $server_connection = new mysqli($server_name, $server_username, $server_password, $server_database);

    $contact_db_connection = new mysqli($server_name, $server_username, $server_password, $contact_db);

    $request_db_connection = new mysqli($server_name, $server_username, $server_password, $request_friend_db);

    $message_db_connection = new mysqli($server_name, $server_username, $server_password, $messages_db);

    $block_contact_db_connection = new mysqli($server_name, $server_username, $server_password, $blockcontact);

    $akm_account_db_connection = new mysqli($server_name, $server_username, $server_password, $akmaccount);

    if(!$server_connection || !$contact_db_connection || !$request_db_connection || !$message_db_connection || !$akm_account_db_connection ){
        
        die("not connected".$server_conn->connect_error);
    }else{
        // echo "connected";
    }
?>