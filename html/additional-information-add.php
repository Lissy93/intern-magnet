<?php

$page_restrictions = "loggedin"; // (notloggedin || loggedin || any)

// Start session and include files, if not already done
if(session_id() == '') { session_start(); }
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class

$ua = new UserActions();
$loggedIn = $ua->isLoggedIn();
if($loggedIn){
    $userObj = $ua->makeUserObject();
    if(!$userObj){$loggedIn = false;}
}

// This page should only be viewed by users who are registered and logged in
if(!$loggedIn){ header('Location: login.php'); }


?>



<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Alicia Sykes">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>Add Additional Information</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for template -->
    <link href="/css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php if(isset($_GET['iframe'])){ ?> <style>body{padding:0; background: #fff}</style> <?php } ?>
</head>

<body>

<?php
if(!isset($_GET['iframe'])){require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php");} ?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Add Additional Information</h2><i class="fa fa-link fa-1x"></i></div>
                <div class="panel-body"  style="padding: 15px 20px;">
                    <div style="display: block; width: 260px; margin: 0 auto;">
                        <form action="/actions/save-additional-details.php" method="post">
                            <input type="hidden" name="form-type" value="add" />
                            <select class="simple-tb" name="slctType">
                                <option value="unknown">--Select--</option>
                                <option value="location">Location / City</option>
                                <option value="previous-employer">Previous Employer</option>
                                <option value="current-employer">Current Employer</option>
                                <option value="school">School</option>
                                <option value="university">University</option>
                                <option value="award">Award</option>
                                <option value="qualification">Qualification</option>
                            </select>
                            <br /><br />
                            <input type="text" name="txtValue" placeholder="Short Heading e.g. Oxford Brookes"
                                   id="txtValue" class="simple-tb" required="true"/><br /><br />
                            <textarea name="txtDescription" class="simple-tb" style="height: 100px;"
                                      placeholder="Description, including dates, achievements, details and anything else relevant"></textarea>
                            <br /> <br />
                            <button type="submit" class=" simple-tb">Save</button>
                            <p id="#fieldDescription"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>


</body></html>