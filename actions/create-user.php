<?php
if(session_id() == '') { session_start(); }

/**
 * Script to create a new user
 * @author Alicia Sykes <me@aliciasykes.com>
 */



// Check we've got what we need and the user didn't just land here by mistake
if(!isset($_POST['firstname'])||
    !isset($_POST['lastname'])||
    !isset($_POST['company-name'])||
    !isset($_POST['interestedCategories'])||
   !isset($_POST['password'])||
   !isset($_POST['confPassword'])||
   !isset($_POST['email'])||
   !isset($_POST['slctUserType'])||
   !isset($_POST['slctCategory'])||
   !isset($_POST['slctYear'])){
    header("Location: "."/");
    die();
}
else{

// Include necessary files
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class


// Set variables from POST parameters
$firstName = $_POST['firstname'];
$lastName = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confPassword = $_POST['confPassword'];
$rawUserType = $_POST['slctUserType'];
$rawCategory = $_POST['slctCategory'];
$rawYear = $_POST['slctYear'];
$companyName = $_POST['company-name'];
$employerCategories = $_POST['interestedCategories'];


//Get user type
$userType = 'none';
if($rawUserType=='student'){
    $userType = 'student'; }
else if($rawUserType=='employer'){
    $userType='employer'; }

//Get category
$category = 0;
if($userType=='student'){
    if($rawCategory=='none'){$category = 0;}
    if(is_numeric($rawCategory)){$category = $rawCategory;}
    else{ $category = 0; }
}

//Get year of study
$yearOfStudy = "none";
if($userType=='student'){
    if($rawYear=="0"||$rawYear=="1"||$rawYear=="2"||$rawYear=="3"||$rawYear=="4"||$rawYear=="graduated"){
        $yearOfStudy = $rawYear;
    }
}




// Register the new user
$register = new Register();
if($userType=="employer"){
    if($register->registerUser($companyName,"",$email,$password,$userType, 0, "none")){
        $success = true;
        $register->insertEmployerCategories($employerCategories);
    }
    else{ $success = false; }
}
else{
    if($register->registerUser($firstName, $lastName,$email,$password,$userType, $category, $yearOfStudy)){
        $success = true;  }
    else{ $success = false; }
}



// Prepare message
if($success){
    $_SESSION['information-message']['message'] =  "Welcome $firstName, your account was created successfully";
    $_SESSION['information-message']['type']    =  "success";
}
else{
    $_SESSION['information-message']['message'] = "Unfortunately there was an error: ".$register->getMessage();
    $_SESSION['information-message']['type']    =  "error"; }


// Get the URL the user came from
$redirectTo = "/profile.php";


// Redirect the user back to their last page, or give them a link to click
header('Location: '.$redirectTo);
echo 'If you are not automatically redirected, please click <a href="'.$redirectTo.'">here</a>';


}

