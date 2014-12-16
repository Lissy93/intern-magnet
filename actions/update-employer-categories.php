<?php

/**
 * Script to update user skills
 * @author Alicia Sykes <me@aliciasykes.com>
 */


if(session_id() == '') { session_start(); }

// Check we've got what we need and the user didn't just land here by mistake
if(!isset($_POST['interestedCategories'])){
    $message = $_SESSION['information-message']['message'] = "There was an error with the information provided";
    $_SESSION['information-message']['type']    =  "error";
}

else{

// Include necessary files
    include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";         // Database Class
    include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";// User Actions Class


// Set variables from POST parameters
    $userId = $_SESSION['user_id'];
    $catlList = $_POST['interestedCategories'];

    $us = new UserSettings();
    $us->updateEmployerCategories($catlList);

    $message = $_SESSION['information-message']['message'] = "Categories updated successfully!";
    $_SESSION['information-message']['type']    =  "success";
    header('Location: /settings.php');
}

?>
