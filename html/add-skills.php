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

$skillsList = array('January', 'February', 'March');
$jsSkillList = '"'.implode('","',  $skillsList ).'"';

$userId = $userObj->getUserId();

$skillsObj = new Skills();
$currentSkillsList = $skillsObj->getUsersSkills($userId);
if($currentSkillsList!=false && count($currentSkillsList)>0){
    $currentJsSkillList = implode(',',  $currentSkillsList ); }
else{$currentJsSkillList = ""; }
?>



<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Alicia Sykes">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>Upload your CV and add skills</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for template -->
    <link href="/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="/lib/fancybox/jquery.fancybox.css" type="text/css" media="screen" />


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Tags Input -->
    <link rel="stylesheet" type="text/css" href="/css/jquery.tagsinput.css" />
    <link rel="stylesheet" type="text/css" href="http://xoxco.com/x/tagsinput/jquery-autocomplete/jquery.autocomplete.css" />
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css" />


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js'></script>

    <script type="text/javascript" src="/js/jquery.tagsinput.min.js"></script>
    <script type='text/javascript' src='http://xoxco.com/x/tagsinput/jquery-autocomplete/jquery.autocomplete.min.js'></script>

    <script type="text/javascript">


        $(function() {
            $('#tags_3').tagsInput({
                width: 'auto',
                autocomplete_url:'/json/fake_plaintext_endpoint.php',
                onChange: function(elem, elem_tags)
                {
                    var languages = [<?php echo $jsSkillList; ?>];
                    $('.tag', elem_tags).each(function()
                    {
                        if($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0)
                            $(this).css('background-color', 'yellow');
                    });
                }
            });
        });

    </script>
    <?php if(isset($_GET['iframe'])){ ?> <style>body{padding:0; background: #fff}</style> <?php } ?>
</head>

<body>


    <?php if(!isset($_GET['iframe'])){require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php");} ?>


                <div class="panel panel-default">
                    <div class="panel-heading"><h3>Add your Skills</h3><i class="fa fa-link fa-1x"></i></div>

                    <form action="/actions/save-skills.php" method="post">
                        <br /><p><label>Skills:</label>(separate skills with a comma or press enter after each skill)
                            <input id='tags_3' type='text' class='tags' name="txtSkillList" value="<?php echo $currentJsSkillList; ?>" /></p>
                        <button type="submit" class="simple-tb">Update Skills</button>
                    </form>
                </div>
            </div>




<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>

</body></html>