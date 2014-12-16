<?php

/**
 * Script to update user description
 * @author Alicia Sykes <me@aliciasykes.com>
 */

if(session_id() == '') { session_start(); }

// Check we've got what we need and the user didn't just land here by mistake
if(!isset($_GET['profileid'])){
    $message = $_SESSION['information-message']['message'] = "There was an error with the information provided";
    $_SESSION['information-message']['type']    =  "error";
}
else{


// Include necessary files
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
    include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class


// Set variables from POST parameters
    $userId = $_SESSION['user_id'];
    $profielId = $_GET['profileid'];

    $magnetObj = new Magnet();
    if($magnetObj->addConnection($userId, $profielId)){$success = true; } else{ $success = false; }


// Prepare message
    if($success){
        $message = $_SESSION['information-message']['message'] =  "Magnetise request sent to user";
        $_SESSION['information-message']['type']    =  "success";
    }
    else{
        $message = $_SESSION['information-message']['message'] = "Unable to sent magnetise request: ".$magnetObj->getMessage();
        $_SESSION['information-message']['type']    =  "error"; }

}
?>



<!DOCTYPE html>
<html lang="en"><head>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><h2>Magnetise</h2><i class="fa fa-link fa-1x"></i></div>
        <div class="panel-body"  style="padding: 15px 20px;">
            <div style="display: block; width: 260px; margin: 0 auto;">
                <p><?php echo $message; ?></p>
            </div>
        </div>
    </div>
</div>

</body></html>