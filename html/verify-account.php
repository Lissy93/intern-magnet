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

$activationObj = new VerifyAccount($userObj->getUserId());
?>



<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Alicia Sykes">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title><?php echo $userObj->getUsername(); ?> | Verify your account</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for template -->
    <link href="/css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="main container">
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php"); ?>
    <br />
    <div class="row">
        <div class="col-sm-12">


            <?php if(!$activationObj->isAccountVerified()){?>
            <div class="panel panel-default extra-margin-sides">
                <div class="panel-heading"><h2>Verify your Account</h2><i class="fa fa-link fa-1x"></i></div>
                    <div class="panel-body">
                        <p><b>Your account has not yet been verified, please follow the steps
                            below to confirm your email address and verify account.</b></p>
                        <br />
                        <p>You should have recieved an email from us after signing up, please either
                            click the link in the email to activate your account, of copy and paste
                            the activation code into the input box below</p>
                        <br />
                        <form action="/actions/activate-account.php" method="get">
                            <input type="text" name="code" placeholder="Paste your activation code here"
                                   class="textbox" style="height: 35px; width: 350px;"/>
                            <button style="height: 35px; border-radius: 4px; border: 1px solid grey;"
                                    type="submit">Activate Account</button>
                        </form>
                        <br />
                        <p><b>Didn't recieve the email?</b></p>
                        <p>Click <a href="/actions/send-activation-email.php">here</a> to resend it to the email address you signed up with.</p>
                        <p>If you would like to change the email address you created this account
                            with, click <a href="/settings.php">here</a></p>
                        <br />
                        <p><b>Having problems receiving the email?</b></p>
                        <p>Enter your mobile number and we can send your activation code to your phone</p>
                        <form action="/actions/send-sms-verification.php" method="get">
                            <input type="text" name="mob-number" placeholder="Enter your mobile number"
                                   class="textbox" style="height: 35px; width: 350px;"/>
                            <button style="height: 35px; border-radius: 4px; border: 1px solid grey;"
                                    type="submit">Send SMS</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } else {?>
            <div class="panel panel-default extra-margin-sides">
                <div class="panel-heading"><h2>Your has been verified</h2><i class="fa fa-link fa-1x"></i></div>
                    <div class="panel-body">
                        <p><b>Your account has now been activated, and you have full use of all the sites features</b></p>
                        <br />
                        <p>If you are not automatically redirected, please click <a href="/getting-started.php">here</a></p>
                        <br />
                        <p>If you would like to modify your account settings, click <a href="/settings.php">here</a></p>
                    </div>
                </div>
            </div>
    <?php } ?>

        </div>
    </div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>

</body></html>