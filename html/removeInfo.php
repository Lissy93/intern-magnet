<?php

$page_restrictions = "loggedin"; // (notloggedin || loggedin || any)

// Start session and include files, if not already done
if(session_id() == '') { session_start(); }
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class

$ua = new UserActions();
$settingsObj = new UserSettings();
$loggedIn = $ua->isLoggedIn();
if($loggedIn){
    $userObj = $ua->makeUserObject();
    if(!$userObj){$loggedIn = false;}
}

// This page should only be viewed by users who are registered and logged in
if(!$loggedIn){ header('Location: login.php'); }

if(isset($_GET['id'])){
    $infoId = $_GET['id'];
    $success = $settingsObj->removeInfo($infoId);
}

?>



<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Alicia Sykes">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>Removed Info</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for template -->
    <link href="/css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>body{padding-top:0;}</style>
</head>

<body>

<div class="container">
    <div class="row text-center">
        <?php if($success){ ?>
            <h2>Entry Removed</h2>
        <?php } else { ?>
            <h2>Error :(</h2>
            <p>Sorry about that, please try again later or contact support if you require any assistance</p>
        <?php } ?>

    </div>
</div>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>


</body></html>