<?php
$pn = 3;

$page_restrictions = "loggedin"; // (notloggedin || loggedin || any)

// Start session and include files, if not already done
if(session_id() == '') { session_start(); }
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class

$ua = new UserActions();
$loggedIn = $ua->isLoggedIn();
$userObj = $ua->makeUserObject();

// This page should only be viewed by users who are registered and logged in
if(!$loggedIn){ header('Location: login.php'); }

// Check account has been verified
$activationObj = new VerifyAccount($userObj->getUserId());
if(!$activationObj->isAccountVerified()){
    header('Location: /html/verify-account.php');
}

// Get user type
$userType = $userObj->getUserType();

?>







<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Alicia Sykes">
    <link rel="shortcut icon" href="img/favicon.ico">
    <title>Getting Started | <?php echo $userObj->getFullName(); ?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Fancybox helpers -->
    <link rel="stylesheet" href="/lib/fancybox/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/lib/fancybox/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

    <!-- Custom styles for template -->
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="/lib/fancybox/jquery.fancybox.css" type="text/css" media="screen" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>



<body>

    <?php require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php"); ?>
    <div class="main container">
        <div class="inner-main inner-main-page">

            <div class="row">
                <h1>Welcome to Intern Magnet</h1>
            </div>

            <?php if($userType=='student'){ ?>
                <div class="row">
                    <h2>How it works</h2>
                    <div class="col-md-4 text-center">
                        <img src="/ico/gs_1.png" alt="Complete your profile">
                        <div class="caption">
                            <h3>1. Complete your profile</h3>
                            <p>Fill in your basic information on your profile so that potential employers can find you</p>
                            <a href="/settings.php" class="btn link-button" role="button">Settings</a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/ico/gs_2.png" alt="Complete your profile">
                        <div class="caption">
                            <h3>2. Search for Internships</h3>
                            <p>Search and find for employers and see the internships they offer</p>
                            <a href="/search.php" class="btn link-button" role="button">Search</a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/ico/gs_3.png" alt="Complete your profile">
                        <div class="caption">
                            <h3>3. Magnetise</h3>
                            <p>Magnetise (or connect) with employers. If they also magnetise with you we'll put you in touch</p>
                            <a href="/magnet.php" class="btn link-button" role="button">Magnetise</a>
                        </div>
                    </div>
                </div>
                <?php if($userObj->calculateProfileCompleteness()<79){ ?>
                <div class="row">
                    <h2>Getting Started</h2>
                    <p>For more accurate matching you should fill in the following information.</p>
                    <p>Click a button to set the answer. You can edit your information at any time in the <a href="/settings.php">settings</a> page</p>
                    <?php if($userObj->getUserCategory() == "unspecified"){  ?>
                    <div class="gs-part">
                        <a href="/html/change-category.php?iframe" class="btn link-button btn-longer iframlink" role="button">What is your field of study?</a>
                    </div>
                    <?php } ?>
                    <?php if($userObj->getYearOfStudy() == "unspecified"){ ?>
                    <div class="gs-part">
                        <a href="/html/change-year.php?iframe" class="btn link-button btn-longer iframlink" role="button">What year of study are you in?</a>
                    </div>
                    <?php } ?>
                    <?php if($userObj->getSpecificInfo('university')==null){ ?>
                    <div class="gs-part">
                        <a href="/html/change-university.php?iframe" class="btn link-button btn-longer iframlink" role="button">Which university are you studying at?</a>
                    </div>
                    <?php } ?>
                    <?php if($userObj->getSpecificInfo('location')==null){ ?>
                    <div class="gs-part">
                        <a href="/html/change-city.php?iframe" class="btn link-button btn-longer iframlink" role="button">Which city are you based in?</a>
                    </div>
                    <?php } ?>
                    <?php
                        $skillsObj = new Skills();
                        $userSkills = $skillsObj->getUsersSkills($userObj->getUserId());
                        $numSkills = count($userSkills);
                        if($numSkills<2){
                        ?>
                    <div class="gs-part">
                        <a href="/html/add-skills.php?iframe" class="btn link-button btn-longer iframlink" role="button">Add you skills</a>
                    </div>
                    <?php } ?>
                    <?php if($userObj->getUserDescription() == ""){ ?>
                    <div class="gs-part">
                        <a href="/html/edit-description.php?iframe" class="btn link-button btn-longer iframlink" role="button">Say a bit about yourself</a>
                    </div>
                    <?php } ?>
                    <?php if($userObj->getCvPath() == ""){?>
                    <div class="gs-part">
                        <a href="/html/upload-cv.php?iframe" class="btn link-button btn-longer iframlink" role="button">Upload your CV</a>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <div class="top-buffer row"></div>
                <div class="row">
                    <h2>Useful Information</h2>
                    <ul>
                        <li>You can modify any of your account settings at any time from the <a href="/settings.php">settings page</a></li>
                        <li>You can view your profile, and make changes to how it looks from the <a href="/profile.php">profile page</a></li>
                        <li>You can search for other students or employers to magnetise with on the <a href="/search.php">search page</a></li>
                        <li>You can view the progress of all your recent magnetics activit on the <a href="/magnetise.php">magnet page</a></li>
                        <li>You can read more about Intern Magnet on the <a href="/about.php">about us page</a></li>
                        <li>You can view all legal information including <a href="legal.php#privacy">privacy policy</a>, <a href="/legal.php#t-and-c">T&C</a>, <a href="/legal.php#cookies">cookies policy</a>, <a href="/legal.php#accessibility">accessibility</a> and <a href="legal.php#disclaimer">disclaimer</a> on the <a href="/legal.php">legal page</a></li>
                    </ul>

                </div>
            <?php }
            else if ($userType=='employer'){?>
                <div class="row">
                    <h2>How it works</h2>
                    <div class="col-md-4 text-center">
                        <img src="/ico/gs_1.png" alt="Complete your profile">
                        <div class="caption">
                            <h3>1. Complete your profile</h3>
                            <p>Fill in basic information about your company so interns can find you</p>
                            <a href="/settings.php" class="btn link-button" role="button">Settings</a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/ico/gs_2.png" alt="Complete your profile">
                        <div class="caption">
                            <h3>2. Search for Interns</h3>
                            <p>Search and find for interns and see their skills and expereince</p>
                            <a href="/search.php" class="btn link-button" role="button">Search</a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/ico/gs_3.png" alt="Complete your profile">
                        <div class="caption">
                            <h3>3. Magnetise</h3>
                            <p>Magnetise (or connect) with interns. If they also magnetise with you we'll put you in touch</p>
                            <a href="/magnet.php" class="btn link-button" role="button">Magnetise</a>
                        </div>
                    </div>
                </div>
                <div class="row top-buffer"></div>
                <div class="row">
                    <h2>More information about your company on Intern Magnet</h2>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                        voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                    <p>
                        Proin venenatis sapien vel eros facilisis, vitae pretium dui eleifend. Sed in augue non odio
                        volutpat congue eu vel libero. Sed elit eros, bibendum vel odio nec, convallis accumsan ex.
                        Curabitur efficitur tellus at dictum suscipit. Morbi laoreet quis mi et aliquet. Praesent non
                        eros non dui interdum tempus sed quis eros. Nullam euismod lectus sapien, sit amet vulputate
                        dolor semper porta. Pellentesque eget tristique magna. Interdum et malesuada fames ac ante
                        ipsum primis in faucibus. Morbi scelerisque nunc eget lectus rhoncus, nec ullamcorper odio placerat
                    </p>
                </div>
                <div class="row top-buffer"></div>
                <div class="row">
                    <h2>Useful Information</h2>
                    <ul>
                        <li>You can modify any of your account settings at any time from the <a href="/settings.php">settings page</a></li>
                        <li>You can view your profile, and make changes to how it looks from the <a href="/profile.php">profile page</a></li>
                        <li>You can search for other students or employers to magnetise with on the <a href="/search.php">search page</a></li>
                        <li>You can view the progress of all your recent magnetics activit on the <a href="/magnetise.php">magnet page</a></li>
                        <li>You can read more about Intern Magnet on the <a href="/about.php">about us page</a></li>
                        <li>You can view all legal information including <a href="legal.php#privacy">privacy policy</a>, <a href="/legal.php#t-and-c">T&C</a>, <a href="/legal.php#cookies">cookies policy</a>, <a href="/legal.php#accessibility">accessibility</a> and <a href="legal.php#disclaimer">disclaimer</a> on the <a href="/legal.php">legal page</a></li>
                    </ul>

                </div>


            <?php } ?>
        </div>
    </div>
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/html/footer.php"); ?>




    <!-- jQuery and Bootstrap-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="/js/notification-script.js"></script>

    <!-- Fancybox -->
    <script type="text/javascript" src="/lib/fancybox/jquery.fancybox.js"></script>
    <script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-buttons.js"></script>
    <script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-media.js"></script>
    <script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-thumbs.js"></script>

    <script>
        $(document).ready(function() {
            $("a.iframlink").fancybox({
                'type':'iframe', 'width':500, 'centerOnScroll': true,
                afterClose: function () {   parent.location.reload(true);   }
            });
        });
    </script>

</body>

</html>