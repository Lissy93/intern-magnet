<?php
$pn = 4;

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


/* Profile Completness */
$profileCompleteness =  $userObj->calculateProfileCompleteness();
$tips = $userObj->getProfileImprovementTips();
if($profileCompleteness<=20){ $c = "danger";        $m = "Urgent Attention Needed"; }
else if($profileCompleteness<=40){ $c = "warning";  $m = "Attention Needed"; }
else if($profileCompleteness<=70){ $c = "info";     $m = "Getting there";}
else if($profileCompleteness>70) { $c = "success";  $m = "Your profiles looking great!";}

?>







<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Alicia Sykes">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>Magnet Board</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

    <!-- Custom styles for template -->
    <link href="css/login-styles.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
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
            <?php if($profileCompleteness<81){?>
                <div class="alert alert-<?php echo $c; ?>" role="alert" onclick="location.href='/settings.php#awsome-profile'" id="profile-completeness-notice">
                    <?php echo $m." - <b>".$profileCompleteness; ?>% complete</b>. <?php if(count($tips)>0){ ?>Click <a href="#awsome-profile">here</a> to see <b>(<?php echo count($tips);?>)</b> tips for improvement<?php } ?>
                </div>
            <?php } ?>

            <h1>Your Magnet Board</h1></div>
        <br />
        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading"  style="background: #a7ede0;"><h3>Inward  Magnetions</h3><i class="fa fa-link fa-1x">People who have magnetised with you</i></div>

                    <?php for($i = 0; $i<count($inwardConections); $i++){ ?>
                        <div class="list-group-item text-right mb-height">
                            <a href="/profile.php?userId=<?php echo $inwardConections[$i]->getUserId();?>">
                                <span class="pull-left"><img class="block-pic2" src="<?php echo $inwardConections[$i]->getGravatar(50); ?>" /></span>
                                <span class="pull-left"><strong><?php echo $inwardConections[$i]->getFullName(); ?> </strong></span>
                            </a>
                            <a href="/actions/delete-magnetise.php?fromUser=<?php echo $inwardConections[$i]->getUserId()."&toUser=".$_SESSION['user_id'];?>"
                               class="iframlink"  onclick="return confirm('Are you sure you want to delete this invitation to connect?')" >
                                Dismiss
                            </a> |
                            <a title="Connect with <?php echo $inwardConections[$i]->getFirstName(); ?>" class="iframlink" href="/html/magnetise.php?profileid=<?php echo $inwardConections[$i]->getUserId(); ?>">Connect</a>

                        </div>
                    <?php } ?>
                    <?php if(count($inwardConections)<1){?>
                        <div class="list-group-item text-right">
                            <span class="pull-left"><strong>No one yet has magnetised with you</strong></span>
                            <br />
                            <i>Try improving your profile</i>
                        </div>
                    <?php } ?>

                </div>
            </div>

            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading"  style="background: #eae2ab;"><h3>Outward Magnetions</h3><i class="fa fa-link fa-1x">Users you magnetised, who haven't yet accepted</i></div>

                    <?php for($i = 0; $i<count($outwardConections); $i++){ ?>
                        <div class="list-group-item text-right mb-height">
                            <a href="/profile.php?userId=<?php echo $outwardConections[$i]->getUserId();?>">
                                <span class="pull-left"><img class="block-pic2" src="<?php echo $outwardConections[$i]->getGravatar(50); ?>" /></span>
                                <span class="pull-left"><strong><?php echo $outwardConections[$i]->getFullName(); ?> </strong></span>
                            </a>
                            <a href="/profile.php?userId=<?php echo $outwardConections[$i]->getUserId();?>">View</a> |
                            <a href="/actions/delete-magnetise.php?fromUser=<?php echo $_SESSION['user_id']."&toUser=".$outwardConections[$i]->getUserId();?>"
                               class="iframlink"  onclick="return confirm('Are you sure you want to delete this invitation to connect?')" >
                                Delete
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(count($outwardConections)<1){?>
                        <div class="list-group-item text-right">
                            <span class="pull-left"><strong>You haven't yet magnetised with anyone</strong></span>
                            <br />
                            <i>Try searching for other users to connect with</i>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading" style="background: #a8e49d;"><h3>Matches</h3>People who accepted your magnet request</i></div>

                    <?php for($i = 0; $i<count($matchedConections); $i++){ ?>
                        <div class="list-group-item text-right mb-height">
                            <a href="/profile.php?userId=<?php echo $matchedConections[$i]->getUserId();?>">
                                <span class="pull-left"><img class="block-pic2" src="<?php echo $matchedConections[$i]->getGravatar(50); ?>" /></span>
                                <span class="pull-left"><strong><?php echo $matchedConections[$i]->getFullName(); ?> </strong></span>
                            </a>
                            <a href="/html/magnet-contact.php<?php echo "?f=".$matchedConections[$i]->getUserId();?>">Get in Touch</a>
                        </div>
                    <?php } ?>
                    <?php if(count($matchedConections)<1){?>
                        <div class="list-group-item text-right">
                            <span class="pull-left"><strong>You haven't yet got any matches</strong></span>
                            <br />
                            <i>Matches will appear when someone you magnetise with, magnetises with you, or visa-versa</i>
                        </div>
                    <?php } ?>

                </div>
            </div>
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