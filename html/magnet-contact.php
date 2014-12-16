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

$social = $userObj->getSocialMedia();
$information = $userObj->getAdditionalInformation();

// Get magnet information
$magnetObj = new Magnet();
$matchedConections = $magnetObj->getTwoWayConnections($userObj->getUserId());
$inwardConections  = $magnetObj->removeAlreadyConnectedUsers($magnetObj->getInwardConnections($userObj->getUserId()), $matchedConections);
$outwardConections = $magnetObj->removeAlreadyConnectedUsers($magnetObj->getOutwardConnections($userObj->getUserId()), $matchedConections);

// Check that this is a valid connection, and not some idiot messing around with the URL params...
$valid = false;
if(isset($_GET['f']) && isset($_SESSION['user_id'])){
    if($magnetObj->checkIfConnected($_GET['f'], $_SESSION['user_id']) && $magnetObj->checkIfConnected($_SESSION['user_id'], $_GET['f'])){
        $valid = true;
    }
}

if(!$valid){
    $_SESSION['information-message']['type'] = "error";
    $_SESSION['information-message']['message'] = "Invalid Credentials";
    header('Location: /magnet-board.php');

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

    <title>Intern Magnet Connection</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for template -->
    <link href="/css/login-styles.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="/lib/fancybox/jquery.fancybox.css" type="text/css" media="screen" />

    <!-- Fancybox helpers -->
    <link rel="stylesheet" href="/lib/fancybox/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/lib/fancybox/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class=" main container">
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php"); ?>
    <div class="inner-main">
        <div class="row">
            <h1>Your Magnet Board</h1>
        </div>
        <br />
        <div class="row">

        </div>

    </div>

</div>


<?php require($_SERVER['DOCUMENT_ROOT'] . "/html/footer.php"); ?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>
<script type="text/javascript" src="/lib/fancybox/jquery.fancybox.js"></script>

<script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-buttons.js"></script>
<script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-media.js"></script>
<script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-thumbs.js"></script>

<script>
    $(document).ready(function() {

        $("a.iframlink").fancybox({
            'type':'iframe',
            'width':500,
            'centerOnScroll': true,
            afterClose: function () {   parent.location.reload(true);   }
        });

    });
</script>



</body></html>