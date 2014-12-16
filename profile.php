<?php$pn = 6;$page_restrictions = "loggedin"; // (notloggedin || loggedin || any)// Start session and include files, if not already doneif(session_id() == '') { session_start(); }include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Classinclude_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class$ua = new UserActions();$loggedIn = $ua->isLoggedIn();$userObj = new User();$isMe = true;if(isset($_GET['userId'])){    $getUserId = $_GET['userId'];    if($ua->userIdValid($getUserId)){        if($userObj->getUserId()!=$getUserId){            $userObj = $ua->makeUserObjectFromId($getUserId);            $isMe = false;        }    }}//Create user objectif($isMe){    $userObj = $ua->makeUserObject();}else{    $userObj = $ua->makeUserObjectFromId($getUserId);}// This page should only be viewed by users who are registered and logged inif(!$loggedIn){ header('Location: login.php'); }// Check account has been verifiedif($isMe){    $activationObj = new VerifyAccount($userObj->getUserId());    if(!$activationObj->isAccountVerified()){        $verifiedUser = false;        header('Location: /html/verify-account.php');    }}$socialmedia = $userObj->getSocialMedia();$details = $userObj->getAdditionalInformation();//Skills$skillsObj = new Skills();$usersSkills = $skillsObj->getUsersSkills($userObj->getUserId());$userHasSkills = false;if(count($usersSkills)>1){	$userHasSkills = true;}else if(count($usersSkills>0)){	if(count($usersSkills)==1){		if($usersSkills[0]!="" && $usersSkills[0]!=null  && $usersSkills[0]!=" "){			$userHasSkills = true;		}		else{			$userHasSkills = true;		}	}}// Magnets$magnetObj = new Magnet();$magnetRequestSent = $magnetObj->checkIfConnected($_SESSION['user_id'], $userObj->getUserId());$magnetRequestReceived = $magnetObj->checkIfConnected($userObj->getUserId(),$_SESSION['user_id']);if($magnetRequestReceived && $magnetRequestSent){ $alreadyConnected = true; }else{ $alreadyConnected = false; }?><!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">    <meta charset="utf-8">    <meta http-equiv="X-UA-Compatible" content="IE=edge">    <meta name="viewport" content="width=device-width, initial-scale=1">    <meta name="description" content="">    <meta name="author" content="Alicia Sykes">    <link rel="shortcut icon" href="img/favicon.ico">    <title><?php echo $userObj->getFullName(); ?> | Profile</title>    <!-- Bootstrap -->    <link href="css/bootstrap.min.css" rel="stylesheet">    <!-- Fonts -->    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>    <!-- Custom styles for template -->    <link href="css/login-styles.css" rel="stylesheet">    <link href="css/styles.css" rel="stylesheet">    <link rel="stylesheet" href="/lib/fancybox/jquery.fancybox.css" type="text/css" media="screen" />    <!-- Fancybox helpers -->    <link rel="stylesheet" href="/lib/fancybox/helpers/jquery.fancybox-buttons.css" type="text/css" media="screen" />    <link rel="stylesheet" href="/lib/fancybox/helpers/jquery.fancybox-thumbs.css" type="text/css" media="screen" />    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->    <!--[if lt IE 9]>    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>    <![endif]--></head><body><?php require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php"); ?><div class="main container">    <div class="inner-main">       <br />       <div class="row">           <?php           if($isMe){               $profileCompleteness =  $userObj->calculateProfileCompleteness();               $tips = $userObj->getProfileImprovementTips();               if($profileCompleteness<=20){ $c = "danger";        $m = "Urgent Attention Needed"; }               else if($profileCompleteness<=40){ $c = "warning";  $m = "Attention Needed"; }                else if($profileCompleteness<=70){ $c = "info";     $m = "Getting there";}                else if($profileCompleteness>70) { $c = "success";  $m = "Your profiles looking great!";}                ?>                    <div class="alert alert-<?php echo $c; ?>" role="alert" onclick="location.href='/settings.php#awsome-profile'" id="profile-completeness-notice">                        <?php echo $m." - <b>".$profileCompleteness; ?>% complete</b>. <?php if(count($tips)>0){ ?>Click <a href="/settings.php#awsome-profile">here</a> to see <b>(<?php echo count($tips);?>)</b> tips for improvement<?php } ?>                    </div>            <?php } ?>            <h1 style="display: inline;"><?php echo $userObj->getFullName(); ?>  </h1>            <h2 style="display: inline;">  <?php echo "  ".($userObj->getUserLine()); ?>   </h2>            <a href="#similarUsers">  Show Similar Users</a>            <br />  <br />        </div>        <div class="row">            <div class="col-sm-3 part">                <img src="<?php echo $userObj->getGravatar(); ?>" class="profile-picture"/>                <?php if($isMe){ ?>                    <a class="iframlink" href="/html/update-picture.php?iframe" style="margin-left:30px;">Change profile picture</a>                <?php } ?>                <br />                <br />                <div class="panel panel-default">                   <div class="panel-heading">Magnetise</div>                   <div class="panel-body">                       <?php if($alreadyConnected) { ?><b style="color: green" onClick="window.location.href = '/magnet-board.php'">You are already connected!</b><br /><br />                       <?php } else if($magnetRequestSent){ ?>                            <b  onClick="window.location.href = '/magnet-board.php'" style="color: grey;">You have already sent a magnetise request</b><br /><br />                       <?php } else { ?>                            <?php if ($userObj->getVisibility()=="visible"){?>                                <?php if($magnetRequestReceived) { ?><b style="color: green"><?php echo $userObj->getFirstName(); ?> has magnetised with you, <br />magnetsise with them to be put in touch!</b><?php } ?>                                <p>Interested in connecting with <?php echo $userObj->getFirstName(); ?>?</p>                                    <a title="Connect with <?php echo $userObj->getFirstName(); ?>" class="iframlink grey-button" href="/html/magnetise.php?profileid=<?php echo $userObj->getUserId(); ?>">Magnetise</a>                            <?php } else { ?>                                  <?php  if($userObj->getUserType()=='employer'){ ?>                                    <p>This company is not currently looking for new interns</p>                                  <?php } else { ?>                            <p><?php echo $userObj->getFirstName(); ?> is not currently looking for an internship</p>                        <?php } } }?>                       <b>To view and edit your magnet requests, go to your <a href="/magnet-board.php">magnet board</a></b>                    </div>                </div>                <ul class="list-group">                    <li class="list-group-item text-muted">Profile</li>                    <li class="list-group-item text-right"><span class="pull-left"><strong>User Type</strong></span><?php echo $userObj->getUserType(); ?></li>                    <?php  if($userObj->getUserType()=='student'){ ?>                        <li class="list-group-item text-right"><span class="pull-left"><strong>Field of Study</strong></span><?php echo $userObj->getUserCategory(); ?></li>                        <li class="list-group-item text-right"><span class="pull-left"><strong>Year of Study</strong></span><?php echo $userObj->getYearOfStudy(); ?></li>                    <?php } ?>                    <li class="list-group-item text-right"><span class="pull-left"><strong>Joined</strong></span><?php echo $userObj->getUserJoinDate(); ?></li>                    <li class="list-group-item text-right"><span class="pull-left"><strong>Last seen</strong></span><?php echo $userObj->getLastSeenDate(); ?></li>                </ul>                <div class="panel panel-default">                    <div class="panel-heading">Social Media                        <?php if($isMe){ ?>                            <a class="iframlink right" href="/html/social-network-add.php?iframe">Add New</a>                        <?php } ?></div>                    <div class="panel-body">                        <?php if ($socialmedia!=null){  for($i = 0; $i< count($socialmedia); $i++){ ?>                            <a href="<?php echo $socialmedia[$i]['url']; ?>">                                <img src="/ico/sm_<?php echo $socialmedia[$i]['service']?>.png"                                     alt="<?php echo $socialmedia[$i]['service']; ?>" class="social_icon"                                     title="<?php echo $userObj->getFirstName()." on ".$socialmedia[$i]['service']; ?>"/>                            </a>                        <?php } } ?>                        <?php if($isMe){ ?>                        <br />                            <p class="small-grey">Edit and remove social media in <a href="/settings.php">settings</a></p>                        <?php } ?>                    </div>                </div>                <div class="panel panel-default">                    <div class="panel-heading">Other details</div>                    <div class="panel-body">                        <?php if(count($details)==0){?>                            <p><?php echo $userObj->getFirstName(); ?> hasn't added any additional details</p>                        <?php } else { ?>                            <?php foreach($details as $d){?>                                <li class="list-group-item">                                    <strong><?php echo $d['type']; ?></strong><br /><?php echo $d['value']; ?> </li>                            <?php } ?>                        <?php } ?>                        <?php if($isMe){?><a class="iframlink"  href="/html/additional-information-add.php?iframe">click here</a> to add more<?php } ?>                    </div>                </div>           </div>            <div class="col-sm-9">                <div class="row">                    <div class="col-sm-6">                        <div class="panel panel-default">                            <div class="panel-heading">About                                <?php if($isMe){ ?> <a class="iframlink right" href="/html/edit-description.php?iframe">Edit</a><?php } ?></div>                            <div class="panel-body">                                <p><?php echo $userObj->getUserDescription(); ?></p>                                <?php if($userObj->getUserDescription()==""){ echo $userObj->getFirstName()." has not completed this section yet"; } ?>                            </div>                        </div>                    </div>                    <div class="col-sm-6">                        <?php  if($userObj->getUserType()=='employer'){ ?>                            <div class="panel panel-default">                                <div class="panel-heading">Industry Categories                                    <?php if($isMe){?><a  href="/html/add-skills.php?iframe" class="right iframlink">Edit</a> <?php } ?></div>                                <div class="panel-body">                                    <?php                                    $db = new Db();                                    $ecid = $userObj->getEmployersCategories();                                    for($k = 0; $k< count($ecid); $k++){                                        $theid = $ecid[$k]['category_id'];                                        $theq = $db->query_get("SELECT name FROM categories WHERE id = '$theid'");                                        $ec[] = $theq[0]['name'];                                    }                                    if (count($ec)!=0){ ?>                                        <?php for($i = 0; $i<count($ec); $i++){?>                                            <a href="/search.php?ec=<?php echo $ec[$i]; ?>" title="Show more employers interested in <?php echo $ec[$i];?> students">                                                <span class="tag"><?php echo $ec[$i]; ?></span>                                            </a>                                        <?php } ?>                                        <br /><br /><br />                                    <?php } else { ?>                                        <li class="list-group-item"><strong>                                                <?php echo $userObj->getFirstName(); ?> hasn't yet added any Skills to their profile yet,                                                <?php if($isMe){?><a class="iframlink"  href="/html/add-skills.php?iframe">click here</a> to upload a CV<?php } ?>                                            </strong> </li>                                    <?php } ?>                                </div>                            </div>                        <?php } else if ($userObj->getUserType()=='student'){ ?>                        <div class="panel panel-default">                            <div class="panel-heading">Skills                                <?php if($isMe){?><a  href="/html/add-skills.php?iframe" class="right iframlink">Edit</a> <?php } ?></div>                            <div class="panel-body">                                            <?php if ($userHasSkills){ ?>                                            <?php for($i = 0; $i<count($usersSkills); $i++){?>                                                <a href="/search.php?s=<?php echo $usersSkills[$i]; ?>" title="Show more users with <?php echo $usersSkills[$i];?> skills">                                                    <span class="tag"><?php echo $usersSkills[$i]; ?></span>                                                </a>                                            <?php } ?>                                            <br /><br /><br />                                <?php } else { ?>                                    <li class="list-group-item"><strong>                                            <?php echo $userObj->getFirstName(); ?> hasn't yet added any Skills to their profile yet,                                            <?php if($isMe){?><a class="iframlink"  href="/html/add-skills.php?iframe">click here</a> to upload a CV<?php } ?>                                        </strong> </li>                                <?php } ?>                            </div>                        </div>                        <?php } ?>                    </div>                </div>                <?php ?>                <div class="panel panel-default">                    <?php if ($userObj->getUserType() == 'student'){?>                    <div class="panel-heading">Curriculum Vitae                        <?php if($isMe){?><a  href="/html/upload-cv.php?iframe" class="right iframlink">Edit</a> <?php } ?>                    </div>                    <div class="panel-body">                        <?php if($userObj->isCvUploaded()){?>                        <object data="/uploads/cv/<?php echo $userObj->getCvPath(); ?>" onerror="this.data='/img/old_logo.png'"                                type="application/pdf" width="100%" height="900">                            <p>It appears you don't have a PDF plugin for this browser.                                No biggie... you can <a href="/uploads/cv/<?php echo $userObj->getCvPath(); ?>">click here to                                    download the PDF file.</a></p>                        </object>                        <?php } else {?>                           <p><?php echo $userObj->getFirstName(); ?> has not uploaded a CV</p>                        <?php } ?>                    </div>                    <?php } else if ($userObj->getUserType() == 'employer'){?>                        <div class="panel-heading">Company Profile                            <?php if($isMe){?><a  href="/html/upload-cv.php?iframe" class="right iframlink">Edit</a> <?php } ?>                        </div>                        <div class="panel-body">                            <?php if($userObj->isCvUploaded()){?>                                <object data="/uploads/cv/<?php echo $userObj->getCvPath(); ?>" type="application/pdf" width="100%" height="900">                                    <p>It appears you don't have a PDF plugin for this browser.                                        No biggie... you can <a href="/uploads/cv/<?php echo $userObj->getCvPath(); ?>">click here to                                            download the PDF file.</a></p>                                </object>                            <?php } else {?>                                <p><?php echo $userObj->getFirstName(); ?> has not uploaded a company profile</p>                            <?php } ?>                        </div>                    <?php } ?>                </div>                <div class="panel panel-default" id="similarUsers">                    <div class="panel-heading">Similar Users</div>                    <div class="panel-body">                       <strong>[COMING SOON!]</strong>                    </div>                </div>            </div>        </div>    </div></div><?php require($_SERVER['DOCUMENT_ROOT'] . "/html/footer.php"); ?><!-- jQuery (necessary for Bootstrap's JavaScript plugins) --><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script><!-- Include all compiled plugins (below), or include individual files as needed --><script src="js/bootstrap.min.js"></script><script src="/js/notification-script.js"></script><script type="text/javascript" src="/lib/fancybox/jquery.fancybox.js"></script><script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-buttons.js"></script><script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-media.js"></script><script type="text/javascript" src="/lib/fancybox/helpers/jquery.fancybox-thumbs.js"></script><script>    $(document).ready(function() {        $("#profile-completeness-notice").delay(6000).slideUp('slow');        $("a.iframlink").fancybox({            'type':'iframe',            'width':500,            'centerOnScroll': true,            afterClose: function () {   parent.location.reload(true);   }        });    });</script></body></html>