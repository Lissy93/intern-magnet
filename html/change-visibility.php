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

    <title>Change Visibility</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for template -->
    <link href="/css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php if(isset($_GET['iframe'])){ ?> <style>body{padding-top:0;}</style> <?php } ?>
</head>

<body>

<?php
if(!isset($_GET['iframe'])){require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php");} ?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Change Search Visibility</h2><i class="fa fa-link fa-1x"></i></div>
                <div class="panel-body"  style="padding: 15px 20px;">
                    <div style="display: block; width: 350px; margin: 0 auto;">
                        <p>If you are not currently looking for an internship or placement, then you can become unsearchable,
                            and you will not show up in employers search results and they will not be able to magnetise with you.</p>
                        <p>(Your profile will still be saved on InternMagnet)</p>
                        <form action="/actions/change-visibility.php" method="post">
                            <label>Searchable</label>
                            <select required="required" name="slctSearchable" class="dropdown">
                                <option value="visible">Visible</option>
                                <option value="invisible">Invisible</option>
                            </select><br /><br />
                            <button type="submit" class=" simple-tb">Update</button>
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