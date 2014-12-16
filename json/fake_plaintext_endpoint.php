<?php
include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/php/UserActions.class.php";

$skillsObj = new Skills();

$skillsList = $skillsObj->getAllSkills();

echo implode("\n",  $skillsList);

?>