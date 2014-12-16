<?php

if(session_id() == '') { session_start(); }

include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class

$ua = new UserActions();

$loggedIn = $ua->isLoggedIn();

if($loggedIn){
    $userObjNav = $ua->makeUserObject();
    if(!$userObjNav){$loggedIn = false;}
}

if (isset($_SESSION['information-message'])){$showMessage = true; } else{$showMessage = false; }
if(isset($page_restrictions)){
    if(($page_restrictions=="notloggedin" && $loggedIn)||($page_restrictions=="loggedin"&&!$loggedIn)){
        $showMessage = false;
    }
}
if(isset($verifiedUser)){ if(!$verifiedUser){ $showMessage = false; } }

if($showMessage){
    $message = $_SESSION['information-message']['message'];
    $type    = $_SESSION['information-message']['type'];
    unset($_SESSION['information-message']); }

if(!isset($pn)){ $pn =0; }
$at = 'class="active-tab"';
?>

<!-- Google Analytics -->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-54769842-1', 'auto');
    ga('send', 'pageview');

</script>


<!-- Fixed navbar -->
<div class="navbar navbar-default" role="navigation" >
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                Intern Magnet
                <img style="display:none;" src="/img/banner_small.png" class="nav-img"  alt="Intern Magnet"/>
            </a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php if($pn ==1){ echo $at; }?>> <a href="/index.php" style="color: white; ">Home</a></li>
                <li  <?php if($pn ==2){ echo $at; }?>><a href="/search.php" class="nb-label">Search</a></li>
                <?php if($loggedIn && $userObjNav->calculateProfileCompleteness()<75 && $userObjNav->getNumberLogins()<3){?>
                    <li  <?php if($pn ==3){ echo $at; }?>><a href="/getting-started.php" class="nb-label">Getting Started</a></li>
                <?php } else if($loggedIn) { ?>
                    <li  <?php if($pn ==4){ echo $at; }?>><a href="/magnet-board.php" class="nb-label">Magnet Board</a></li>
                <?php } else { ?>
                <li  <?php if($pn ==5){ echo $at; }?>><a href="/about.php" class="nb-label">About</a></li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if($loggedIn){ ?>
                    <li><p class="navbar-label" class="nb-label" style="margin: 0">Logged in as <?php echo $userObjNav->getUsername(); ?></p></li>
                    <li  <?php if($pn ==6){ echo $at; }?>><a href="/profile.php" class="nb-label">Profile</a></li>
                    <li  <?php if($pn ==7){ echo $at; }?>><a href="/settings.php" class="nb-label">Settings</a></li>
                    <li><a href="/actions/log-out.php" class="nb-label">Log Out</a></li>
                <?php } else { ?>
                    <li  <?php if($pn ==8){ echo $at; }?>><a href="/login.php" class="nb-label">Log In</a></li>
                    <li  <?php if($pn ==8){ echo $at; }?>><a href="/login.php" class="nb-label">Sign Up</a></li>
                <?php } ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<?php if($showMessage){ ?>
    <div class="information-message" id="notification-wrapper"><div class="inner <?php echo $type; ?>">
        <p><?php echo $message; ?></p>
    </div></div>
<?php } ?>