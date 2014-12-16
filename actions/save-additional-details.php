<?php


if(session_id() == '') { session_start(); }

include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class

$ua = new UserActions();

$settings = new UserSettings();

$message = "";

if($ua->isLoggedIn()){

    if(isset($_POST['form-type'])){
        $ft = $_POST['form-type'];
        if(isset($_POST['slctType'])&&isset($_POST['txtValue'])&&isset($_POST['txtDescription'])){
            $settings->addAdditionalDetails($_POST['slctType'], $_POST['txtValue'], $_POST['txtDescription']);
            $message = 'Your <b>'.$_POST['slctType'].'</b> information has been saved';
        }
    }

}
?>








<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Add Additional Information</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">
    <style>body{padding-top:0;}</style>
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><h2>Done!</h2><i class="fa fa-link fa-1x"></i></div>
        <div class="panel-body"  style="padding: 15px 20px;">
            <p><?php echo $message; ?></p>

            <a href="/html/additional-information-add.php?iframe">
                <div class="p-button">
                    Add More Information
                </div>
            </a>
            <a href="/settings.php" target="_parent">
                <div class="p-button">
                    Back to Settings
                </div>
            </a>
            <a href="/index.php" target="_parent">
                <div class="p-button">
                    Return to Homepage
                </div>
            </a>
        </div>
    </div>

</div>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>


</body></html>






