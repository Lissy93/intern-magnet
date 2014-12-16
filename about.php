<?php
$pn = 5;

if(session_id() == '') { session_start(); }

include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class

$ua = new UserActions();

$loggedIn = $ua->isLoggedIn();

if($loggedIn){
    $userObj = $ua->makeUserObject();
    if(!$userObj){$loggedIn = false;}
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

    <title>About | Intern Magnet</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

	<!-- Custom styles for template -->
    <link href="css/styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container main">
<?php require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php"); ?>
    <div style="padding:0 60px;">
		<h1>
			About Intern Magnet
		</h1>
		<br />
     	<p style="font-size: 20px; text-align: left;">
			<b>Lorem ipsum</b> dolor sit amet, consectetur adipiscing elit. Proin rutrum sapien massa, 
			et accumsan nulla placerat non. Cras sit amet augue eget magna ultricies vestibulum quis 
			vitae nibh. Morbi vitae augue et libero dictum sagittis.
			<br /><br />
			<img src="http://placehold.it/280x120" style="float: right; margin: 8px;">
			Donec et laoreet lorem. Nulla vestibulum maximus iaculis. Praesent sed pharetra justo. 
			Etiam a justo eros. Duis mattis nisi sapien, id egestas magna tempor ut. Suspendisse eu enim 
			augue. Ut suscipit est et justo fermentum gravida. Suspendisse in egestas nibh. Nullam finibus 
			dignissim mauris, vulputate eleifend eros mollis in. Mauris ultricies odio arcu, vel venenatis 
			ex vehicula in. Vivamus vestibulum nisi luctus varius eleifend. Nam porta elit at enim tristique, 
			sit amet euismod eros molestie. Aliquam erat volutpat. Aenean dui ex, dictum quis risus vel, 
			fermentum commodo velit. Maecenas sed tortor in ante pharetra porttitor. Aenean orci tellus, 
			viverra eu felis non, facilisis laoreet leo.
			<br /><br />
			<b>Nulla semper</b>, enim at venenatis porttitor, nibh velit pretium massa, ut posuere mauris magna 
			id lacus. Fusce eget dolor sit amet elit tincidunt semper vitae et leo. <i>Sed ut blandit nunc. 
			Phasellus accumsan diam leo, vel tempor quam faucibus ac.</i> Pellentesque quis magna purus. Etiam 
			rhoncus nulla nulla, et vulputate mauris convallis at. Vivamus sit amet ligula tincidunt.
		</p>
    </div>
</div> <!-- /container -->

<?php require($_SERVER['DOCUMENT_ROOT'] . "/html/footer.php"); ?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>
</body></html>