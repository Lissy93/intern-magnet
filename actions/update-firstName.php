<?php if(session_id() == '') { session_start(); }
/**
 * Script to update first name
 * @author Alicia Sykes <me@aliciasykes.com>
 */



// Check we've got what we need and the user didn't just land here by mistake
if(!isset($_POST['password'])||
    !isset($_POST['firstName'])||
    !isset($_SESSION['user_id'])){

    $message = $_SESSION['information-message']['message'] = "There was an error with the information you provided";
    $_SESSION['information-message']['type']    =  "error";
}
else{


// Include necessary files
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
    include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class


// Set variables from POST parameters
    $userId = $_SESSION['user_id'];
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];

    $settings = new UserSettings();
    if($settings->updateFirstName($userId, $password,  $firstName)){$success = true; } else{ $success = false; }


// Prepare message
    if($success){
        $message = $_SESSION['information-message']['message'] =  "Your name  has been updated successfully";
        $_SESSION['information-message']['type']    =  "success";
    }
    else{
        $message = $_SESSION['information-message']['message'] = "Unable to update name: ".$settings->getMessage();
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
        <div class="panel-heading"><h2>Update First Name</h2><i class="fa fa-link fa-1x"></i></div>
        <div class="panel-body"  style="padding: 15px 20px;">
            <div style="display: block; width: 260px; margin: 0 auto;">
                <p><?php echo $message; ?></p>
            </div>
        </div>
    </div>
</div>

</body></html>