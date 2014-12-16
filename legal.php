<?php

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

    <title>Legal | Intern Magnet</title>

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
<?php require($_SERVER['DOCUMENT_ROOT'] . "/html/navbar.php"); ?>
<div class="legal-container">
    <h1>Legal Information</h1>
    <br />
    <div class="row">
        <div class="col-md-4"><br />
            <ol class="legal-list">
                <li><a href="#t-and-c">T&C</a></li>
                <li><a href="#disclaimer">Disclaimer</a></li>
                <li><a href="#cookies">Cookies</a></li>
                <li><a href="#accessibility">Accessibility</a></li>
                <li><a href="#privacy">Privacy Policy</a></li>
            </ol>
        </div>
        <div class="col-md-8">
        <p>
            <b>Introduction </b> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rutrum sapien massa,
            et accumsan nulla placerat non. Cras sit amet augue eget magna ultricies vestibulum quis
            vitae nibh. Morbi vitae augue et libero dictum sagittis.
            <br /><br />
            Curabitur condimentum nibh feugiat lorem euismod consequat. Vestibulum tempus nunc at varius viverra. In egestas sit amet eros nec bibendum. Aenean fermentum non arcu a porttitor. Curabitur bibendum dui in mauris dapibus, at fringilla ex mollis. Quisque et velit orci. Etiam sit amet odio ut velit iaculis congue. Proin viverra finibus dui ac semper.

            Vivamus et ipsum at neque ullamcorper rhoncus sit amet vel neque. Etiam eu dolor eget diam hendrerit maximus ut a dui. Interdum et malesuada fames ac ante ipsum primis in faucibus. Cras lacus massa, commodo vitae fringilla consequat, sagittis et nisi. Suspendisse non mollis nulla. Quisque vitae luctus justo. In pulvinar mi enim, a semper mauris bibendum sed. Phasellus id enim eget nunc euismod congue. In hac habitasse platea dictumst. Donec eget tincidunt nunc. Sed orci odio, suscipit vel magna euismod, accumsan sagittis neque. Morbi euismod ex consequat ex efficitur, sed pellentesque ligula pharetra. Donec suscipit enim accumsan dapibus vulputate. Vestibulum sodales est vitae neque interdum, sit amet tincidunt elit efficitur. Donec sed velit leo.
        </p>
        </div>
    </div>
</div>

<div class="legal-container" id="t-and-c">
    <h2>Terms and Conditions</h2>
    <br />
    <p>
        <b>Introduction </b> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rutrum sapien massa,
        et accumsan nulla placerat non. Cras sit amet augue eget magna ultricies vestibulum quis
        vitae nibh. Morbi vitae augue et libero dictum sagittis.
    </p>
</div>

<div class="legal-container" id="disclaimer">
    <h2>Disclaimer</h2>
    <br />
    <p>
        nibh feugiat lorem euismod consequat. Vestibulum tempus nunc at varius viverra. In egestas sit amet eros nec bibendum. Aenean fermentum non arcu a porttitor. Curabitur bibendum dui in mauris dapibus, at fringilla ex mollis. Quisque et velit orci. Etiam sit amet odio ut velit iaculis congue. Proin viverra finibus dui ac semper.nibh feugiat lorem euismod consequat. Vestibulum tempus nunc at varius viverra. In egestas sit amet eros nec bibendum. Aenean fermentum non arcu a porttitor. Curabitur bibendum dui in mauris dapibus, at fringilla ex mollis. Quisque et velit orci. Etiam sit amet odio ut velit iaculis congue. Proin viverra finibus dui ac semper.nibh feugiat lorem euismod consequat. Vestibulum tempus nunc at varius viverra. In egestas sit amet eros nec bibendum. Aenean fermentum non arcu a porttitor. Curabitur bibendum dui in mauris dapibus, at fringilla ex mollis. Quisque et velit orci. Etiam sit amet odio ut velit iaculis congue. Proin viverra finibus dui ac semper.nibh feugiat lorem euismod consequat. Vestibulum tempus nunc at varius viverra. In egestas sit amet eros nec bibendum. Aenean fermentum non arcu a porttitor. Curabitur bibendum dui in mauris dapibus, at fringilla ex mollis. Quisque et velit orci. Etiam sit amet odio ut velit iaculis congue. Proin viverra finibus dui ac semper.

    </p>
</div>

<div class="legal-container" id="cookies">
    <h2>Cookies</h2>
    <br />
    <p>
        <b>Local Cookies </b> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rutrum sapien massa,
        et accumsan nulla placerat non. Cras sit amet augue eget magna ultricies vestibulum quis
        vitae nibh. Morbi vitae augue et libero dictum sagittis.
        <br /><br />
        Curabitur condimentum nibh feugiat lorem euismod consequat. Vestibulum tempus nunc at varius viverra. In egestas sit amet eros nec bibendum. Aenean fermentum non arcu a porttitor. Curabitur bibendum dui in mauris dapibus, at fringilla ex mollis. Quisque et velit orci. Etiam sit amet odio ut velit iaculis congue. Proin viverra finibus dui ac semper.
        Vivamus et ipsum at neque ullamcorper rhoncus sit amet vel neque. Etiam eu dolor eget diam hendrerit maximus ut a dui. Interdum et malesuada fames ac ante ipsum primis in faucibus. Cras lacus massa, commodo vitae fringilla consequat, sagittis et nisi. Suspendisse non mollis nulla. Quisque vitae luctus justo. In pulvinar mi enim, a semper mauris bibendum sed. Phasellus id enim eget nunc euismod congue. In hac habitasse platea dictumst. Donec eget tincidunt nunc. Sed orci odio, suscipit vel magna euismod, accumsan sagittis neque. Morbi euismod ex consequat ex efficitur, sed pellentesque ligula pharetra. Donec suscipit enim accumsan dapibus vulputate. Vestibulum sodales est vitae neque interdum, sit amet tincidunt elit efficitur. Donec sed velit leo.
        <br /><br />
        <b>Server Cookies </b>Etiam semper ligula eget viverra ultricies. Donec interdum augue dolor, vitae cursus velit aliquet vel. Sed nec auctor sem. Sed interdum consequat justo, vitae pharetra turpis venenatis at. Nullam eget tellus sit amet orci congue scelerisque id nec quam. Vestibulum eu justo turpis. Suspendisse suscipit commodo faucibus. Fusce condimentum, leo sit amet sagittis placerat, ex mauris euismod metus, ac ullamcorper sem ante ut erat. Proin suscipit mauris in malesuada dignissim.
        Aenean fringilla augue nec mollis malesuada. Curabitur fringilla elit sed venenatis lacinia. Nunc rhoncus nibh vel turpis dignissim malesuada. Etiam interdum eros ligula, in aliquet ipsum placerat a. Nulla auctor pellentesque justo, id consequat purus gravida in. Pellentesque sed fringilla erat. Nunc vitae porta ante, eget cursus turpis. Suspendisse lacinia mauris non suscipit interdum.
    </p>
</div>

<div class="legal-container" id="accessibility">
    <h2>Accessibility</h2>
    <br />
    <p>
        <b>Introduction </b> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rutrum sapien massa,
        et accumsan nulla placerat non. Cras sit amet augue eget magna ultricies vestibulum quis
        vitae nibh. Morbi vitae augue et libero dictum sagittis.
    </p>
</div>

<div class="legal-container" id="privacy">
    <h2>Privacy Policy</h2>
    <br />
    <p>
        <b>Introduction </b> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rutrum sapien massa,
        et accumsan nulla placerat non. Cras sit amet augue eget magna ultricies vestibulum quis
        vitae nibh. Morbi vitae augue et libero dictum sagittis.
        <br /><br />
        Etiam semper ligula eget viverra ultricies. Donec interdum augue dolor, vitae cursus velit aliquet vel. Sed nec auctor sem. Sed interdum consequat justo, vitae pharetra turpis venenatis at. Nullam eget tellus sit amet orci congue scelerisque id nec quam. Vestibulum eu justo turpis. Suspendisse suscipit commodo faucibus. Fusce condimentum, leo sit amet sagittis placerat, ex mauris euismod metus, ac ullamcorper sem ante ut erat. Proin suscipit mauris in malesuada dignissim.
        Aenean fringilla augue nec mollis malesuada. Curabitur fringilla elit sed venenatis lacinia. Nunc rhoncus nibh vel turpis dignissim malesuada. Etiam interdum eros ligula, in aliquet ipsum placerat a. Nulla auctor pellentesque justo, id consequat purus gravida in. Pellentesque sed fringilla erat. Nunc vitae porta ante, eget cursus turpis. Suspendisse lacinia mauris non suscipit interdum.
        Etiam semper ligula eget viverra ultricies. Donec interdum augue dolor, vitae cursus velit aliquet vel. Sed nec auctor sem. Sed interdum consequat justo, vitae pharetra turpis venenatis at. Nullam eget tellus sit amet orci congue scelerisque id nec quam. Vestibulum eu justo turpis. Suspendisse suscipit commodo faucibus. Fusce condimentum, leo sit amet sagittis placerat, ex mauris euismod metus, ac ullamcorper sem ante ut erat. Proin suscipit mauris in malesuada dignissim.
        Aenean fringilla augue nec mollis malesuada. Curabitur fringilla elit sed venenatis lacinia. Nunc rhoncus nibh vel turpis dignissim malesuada. Etiam interdum eros ligula, in aliquet ipsum placerat a. Nulla auctor pellentesque justo, id consequat purus gravida in. Pellentesque sed fringilla erat. Nunc vitae porta ante, eget cursus turpis. Suspendisse lacinia mauris non suscipit interdum.

    </p>
</div>

<br />

<?php require($_SERVER['DOCUMENT_ROOT'] . "/html/footer.php"); ?>





<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="/js/notification-script.js"></script>
</body></html>