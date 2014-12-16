<?php

/**
 * Script to upload CV
 * @author Alicia Sykes <me@aliciasykes.com>
 */

if(session_id() == '') { session_start(); }

// Check we've got what we need and the user didn't just land here by mistake

if(!isset($_FILES['file-cv'])){
    $message = $_SESSION['information-message']['message'] = "There was an error with the information you provided";
    $_SESSION['information-message']['type']    =  "error";
}
else{


// Include necessary files
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
    include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class


// Set variables from POST parameters
    $userId = $_SESSION['user_id'];
    $cv = $_FILES['file-cv'];

    $settings = new UserSettings();


    $userId = $_SESSION['user_id'];
    $newFileName = $userId."_".time().".pdf";
    $target_path = $_SERVER['DOCUMENT_ROOT']."/uploads/cv/".$newFileName;

    if(move_uploaded_file($_FILES['file-cv']['tmp_name'], $target_path)) {
        $message =  "The file ".  basename( $_FILES['file-cv']['name'])." has been uploaded";

        $db = new Db();
        $db->connect();
        $db->query_insert("UPDATE users SET cv='$newFileName' WHERE ID = '$userId';");

        $success = true;
    } else{
        echo "There was an error uploading the file, please try again!";
    }


// Prepare message
    if($success){
        $_SESSION['information-message']['message'] =  "Your CV has been uploaded successfully";
        $_SESSION['information-message']['type']    =  "success";
    }
    else{
        $_SESSION['information-message']['message'] = "Unable to upload CV: ".$settings->getMessage();
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
        <div class="panel-heading"><h2>Upload CV</h2><i class="fa fa-link fa-1x"></i></div>
        <div class="panel-body"  style="padding: 15px 20px;">
            <div style="display: block; width: 260px; margin: 0 auto;">
                <p><?php echo $message; ?></p>
            </div>
        </div>
    </div>
</div>

</body></html>