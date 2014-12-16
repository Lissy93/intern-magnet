<?php

/**
 * Script to update user description
 * @author Alicia Sykes <me@aliciasykes.com>
 */

if(session_id() == '') { session_start(); }

// Check we've got what we need and the user didn't just land here by mistake
if(!isset($_GET['fromUser']) || !isset($_GET['toUser'])){
    $message = $_SESSION['information-message']['message'] = "There was an error with the information provided";
    $_SESSION['information-message']['type']    =  "error";
}
else {


// Include necessary files
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php/Db.class.php";         // Database Class
    include_once $_SERVER['DOCUMENT_ROOT'] . "/php/UserActions.class.php";// User Actions Class


// Set variables from POST parameters
    $userId = $_SESSION['user_id'];
    $fromUser = $_GET['fromUser'];
    $toUser = $_GET['toUser'];

    $magnetObj = new Magnet();
    $magnetObj->deleteConnection($fromUser, $toUser);


// Prepare message
    $message = $_SESSION['information-message']['message'] = "Connection / Magnet Request Deleted";
    $_SESSION['information-message']['type'] = "success";

}
?>



<!DOCTYPE html>
<html lang="en"><head>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><h2>Delete Connection</h2><i class="fa fa-link fa-1x"></i></div>
        <div class="panel-body"  style="padding: 15px 20px;">
            <div style="display: block; width: 260px; margin: 0 auto;">
                <p><?php echo $message; ?></p>
            </div>
        </div>
    </div>
</div>

</body></html>