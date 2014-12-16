<?php



if(session_id() == '') { session_start(); }

include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class
$ua = new UserActions();
$userObj = $ua->makeUserObject();
$firstName = $userObj->getFirstName();
$actObj = new VerifyAccount($userObj->getUserId());
$activationCode = $actObj->getCode();
$success = false;




require $_SERVER['DOCUMENT_ROOT']."/lib/Services/Twilio.php";
if(isset($_GET['mob-number'])){
    $mobNum = $_GET['mob-number'];


$AccountSid = "ACb9ddd21a0383212ec56b2d209d819275";
$AuthToken = "a263dd37812b0ce7259142e589af20a2";

// Step 3: instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);

// Step 4: make an array of people we know, to send them a message.
// Feel free to change/add your own phone number and name here.
$people = array(
    "" => "Curious George",
    "+14158675310" => "Boots",
    "+14158675311" => "Virgil",
);


    $sms = $client->account->messages->sendMessage(
        "YYY-YYY-YYYY",
        $mobNum,
        "Hi $firstName, Welcome to Intern Magnet. Your activation code is: $activationCode"
    );

    // Display a confirmation message on the screen
    $success = true;

}
else{
    $success = false;
}


// Prepare message for the user
if($success){
    $_SESSION['information-message']['type'] = "success";
    $_SESSION['information-message']['message'] = "SMS sent to $mob-num";
}
else {
    $_SESSION['information-message']['type'] = "error";
    $_SESSION['information-message']['message'] = "Error sending SMS";
}


// Redirect the user back to their last page, or give them a link to click
header('Location: /profile.php');
echo 'If you are not automatically redirected, please click <a href="/profile.php">here</a>';

