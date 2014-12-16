<?php

include_once $_SERVER['DOCUMENT_ROOT']."/php/Db.class.php";	// Include the database class

class UserActions {

    public $_dbObj;		// The Database object for executing queries and other common db actions
    protected $_url;	// The URL of of the site (used for sending emails)

	/* Constructor to assign global variables*/
    public function __construct(){
        $this->_dbObj = new Db();
        $this->_dbObj->connect();
        $this->_url = "http://demo.internmagnet.go.uk"; // NOTE: this must be modified in order to use it
    }

	/* 	Logs the user out and resets user session variables	*/
    public function logOut(){
        $_SESSION['user_id']='';        unset($_SESSION['user_id']);
        $_SESSION['check_user'] = '';   unset($_SESSION['check_user']);
    }

	/* Return true if a VALID user is currently logged in, otherwise return false */
    public function isLoggedIn(){
        if(isset($_SESSION['user_id']) && isset($_SESSION['check_user'])){
            $userId = $_SESSION['user_id'];
            $checkUser = $_SESSION['check_user'];
            if($this->userIdValid($userId)){
                $newUserCheckStr = $this->makeCheckUserStr($userId);
                if($checkUser == $newUserCheckStr){
                    return true;
                }
            }
        }
        return false;
    }

	/* Creates and returns a user object from currently logged in user */
    public function makeUserObject(){
        if($this->isLoggedIn()){
            $userId = $_SESSION['user_id'];
            return $this->makeUserObjectFromId($userId);
        }
        return false;
    }

	/* Creates and returns a user object for a given user ID */
    public function makeUserObjectFromId($userId){
        if($this->userIdValid($userId)){
            $userQuery = ($this->_dbObj->query_get("SELECT * FROM users WHERE ID = '$userId'"));
            $userObj = new User();
            $userObj->setUserId($userId);
            $userObj->setUsername($userQuery[0]['username']);
            return $userObj;
        }
        return false;
    }

	/* Creates a link to allow user to reset their password when followed */
    public function makePasswordResetUrl($email){
        $email = strtolower($email);
        $q = ($this->_dbObj->query_get("SELECT ID, salt FROM users WHERE email = '$email'"));
        if(count($q)>0){$id = $q[0]['ID']; }
        else{ $id   = ""; }
        $verificationCode = $this->makePasswordResetCode($id);
        $url = $this->_url."/html/reset-password.php?v=$verificationCode&i=$id";
        return $url;
    }

	/* Generates the code to be embeded into the link to allow user to reset their password */
    public function makePasswordResetCode($userId){
        $db = new Db();
        if(count($db->query_get("SELECT ID FROM users WHERE ID = '$userId'"))>0){
            $q = ($this->_dbObj->query_get("SELECT ID, salt FROM users WHERE ID = '$userId'"));
            $salt = $q[0]['salt'];
        } else{$salt = ""; }
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        $verificationCode = hash('sha512', $salt.$user_browser);
        return $verificationCode;
    }

    /* Set the URL class attribute */
    public function setUrl($url){
        $this->_url = $url;
    }

	/* Puts year of study into english e.g. '1' will return '1st year' */
    public function formatYearOfStudy($year){
        if($year==1){return "1st year";}
        else if($year == 2){return "2nd year"; }
        else if($year == 3){return "3rd year"; }
        else if($year == 4){return "4th year"; }
        else{ return $year; }
    }

	/* Generates a random string of letters and numbers to be used as a salt */
    protected function generateSalt($len=32){
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $len; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

	/* Returms true if the user is VALID (exisst in db) otherwise returns false */
    public function userIdValid($userId){
        if(count($this->_dbObj->query_get("SELECT * FROM users WHERE ID = '$userId'"))>0){
            return true;
        }
        return false;
    }

	/* Returns an array of all categories */
    public function getCategories(){
        $dbObj = new Db();
        $categories = $dbObj->query_get("SELECT * FROM categories");
        return $categories;
    }

	/* Hashes and returns password passed in as param */
    protected function hashPassword($pwd, $salt){
        return hash_hmac('sha512', $pwd.$salt, "4z2nebDDIB");
    }

	/* Generates a code commining the users password, salt and browser which can be used to verify them */
    protected function makeCheckUserStr($userId){
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        if($this->userIdValid($userId)){
            $passwordQuery = ($this->_dbObj->query_get("SELECT * FROM users WHERE ID = '$userId'"));
            $password = $passwordQuery[0]['password']; }
        else{
            $password = ""; }

        $checkUserString = hash('sha512', $password . $user_browser);
        return $checkUserString;
    }

	/* Checks that the users password is correct, returns bool */
    protected function checkUsersPassword($userId, $pwd){
        if($this->userIdValid($userId)){
            $saltQuery = ($this->_dbObj->query_get("SELECT * FROM users WHERE ID = '$userId'"));
            $salt = $saltQuery[0]['salt'];
            $pwd = $this->hashPassword($pwd, $salt);
            if(count($this->_dbObj->query_get("SELECT * FROM users WHERE ID = '$userId' AND password = '$pwd'"))>0){
                return true;
            }
            return false;
        }
        else{
            return false;
        }

    }

}


/**
*	A class containing all login related methods
*	The method start($name, $password) will begin the login process
*/

class Login extends UserActions{

    public  function start($inName, $inPassword, $staySignedIn=false){

        /* Determine if username or email */
        if(filter_var($inName, FILTER_VALIDATE_EMAIL)){ $loginWith = "email"; }
        else{ $loginWith = "username"; }

        /* Format username/ email before searching for in db */
        $inName = strtolower($inName);
        if($loginWith=="username"){ $inName = preg_replace('/[^\w]+/', '', $inName);}

        /* Database */
        $dbObj = new Db();
        $dbObj->connect();

        /* Find Salt */
        if(count($dbObj->query_get("SELECT ID FROM users WHERE $loginWith = '$inName' "))>0){
            $saltQuery = ($this->_dbObj->query_get("SELECT ID, salt FROM users WHERE $loginWith = '$inName'"));
            $salt = $saltQuery[0]['salt'];
        }
        else{
            $salt = "";
        }

        /* Hash Password */
        $inPassword = $this->hashPassword($inPassword, $salt);

        /* Determine if user exists */
        $exists = false;
        $q = $dbObj->query_get("SELECT ID FROM users WHERE $loginWith = '$inName' AND password='$inPassword'");
        if(count($q)>0){ $exists = true; }

        /* Set the stay signed in cookie */
        if($exists){
            $this->setSessions($q[0]['ID']);
            $this->recordLoginAttempt(true,$q[0]['ID']);
            return true;
        }

        if(isset($saltQuery)){$userId = $saltQuery[0]['ID']; }else{$userId = 0;}
        $this->recordLoginAttempt(false,$userId);
        return false;

    }

    /* Set the users ID to session */
    protected function setSessions($userId){
        if(session_id() == '') { session_start(); }
        $_SESSION['user_id'] = $userId;
        $_SESSION['check_user'] = $this->makeCheckUserStr($userId);
    }

    protected function recordLoginAttempt($success, $userId){
        /* Get the data */
        $usersIp = $_SERVER['REMOTE_ADDR'];
        $date = new DateTime();
        $timeStamp = $date->getTimestamp();

        /* Database bit */
        $dbObj = new Db();
        $dbObj->connect();

        /* Insert */
        $dbObj->query_insert("INSERT INTO user_logins (user_id, ip, time, success) ".
            "VALUES('$userId','$usersIp','$timeStamp','$success')");
    }


}




class Register extends UserActions{
    private $_message = "";

    private $_username;
    private $_firstname;
    private $_lastname;
    private $_email;
    private $_password;
    private $_salt;
    private $_userType;
    private $_category;
    private $_year;
    private $_date;

    public function registerUser($firstName, $lastName, $email, $password, $userType, $category='none', $year='none'){
        /* Assign parameters to class members */
        $this->_username    =   $this->generateUsername($firstName, $lastName, $userType);
        $this->_firstname   =   $firstName;
        $this->_lastname    =   $lastName;
        $this->_email       =   $email;
        $this->_password    =   $password;
        $this->_userType    =   $userType;
        $this->_category    =   $category;
        $this->_year        =   $year;

        /* Make data safe*/
        $this->makeDataSafe();

        /* Check all the data is valid*/
        if(!$this->checkData()){ return false;}

        /* Password hash */
        $this->encryptPassword();

        /* Get today's date */
        $today = getdate();
        $this->_date = $today['year'].'-'.$today['mon'].'-'.$today['mday'];

        /* Insert into database */
        $this->insertData();

        /* Log user in */
        $this->logUserIn($password);

        /* Send activation email */
        $verifyObj = new VerifyAccount($_SESSION['user_id']);
        $verifyObj->sendActivationEmail();

        /* Everything went smoothly - return true */
        return true;
    }

    /* Generates a unique username from the users full name or company name */
    private function generateUsername($fn, $ln, $userType='student'){
        if($userType=='employer'){
            $un = str_replace(' ', '', strtolower($fn));
        }
        else{
            $un = strtolower(substr($fn,0,1).$ln);
        }
		$numNames = count($this->_dbObj->query_get("SELECT * FROM users WHERE username LIKE '$un%'"))+1;
        if($numNames>1){
            $numNames = str_pad($numNames, 2, "0", STR_PAD_LEFT);
            $un .= $numNames;
        }
        return $un;
    }

    /**
     * Puts all the data into a consistent format
     * Strips illegal special characters
     * Removes trailing space
     */
    private function makeDataSafe(){
        if($this->_userType!='employer'){
        $this->_firstname = preg_replace('/[^\w]+/', '', $this->_firstname);
        $this->_firstname = strtolower($this->_firstname); }
        $this->_lastname = preg_replace('/[^\w]+/', '', $this->_lastname);
        $this->_lastname = strtolower($this->_lastname);
        $this->_email = strtolower($this->_email);
    }

    /**
     * Checks all user registration data is in a valid format
     * If not, sets the class member _message to the error
     * @return bool true if valid, false if invalid
     */
    private function checkData(){
        /* Check the username - length, and taken*/
    ///// CURRENTLY NOT NEEDED AS USERNAME IS AUTO GENERATED! //////////
    //        $un = $this->_username;
    //        if(strlen($un)>50){
    //            $this->_message = "Username is too long - must be under 50 characters";
    //            return false;
    //        }
    //        if(strlen($un)<3){
    //            $this->_message = "Username is too short - must be 3 or more characters";
    //            return false;
    //        }
    //        if(count($this->_dbObj->query("SELECT * FROM users WHERE username = '$un'"))>0){
    //            $this->_message = "Username is already taken";
    //            return false;
    //        }

        /* Check email address */
        if(!filter_var($this->_email, FILTER_VALIDATE_EMAIL)){
            $this->_message = "Email address does not appear to be valid";
            return false;
        }

        /* Check email is not taken */
        $em = $this->_email;
        if(count($this->_dbObj->query_get("SELECT * FROM users WHERE email = '$em'"))>0){
            $this->_message = "Email is already registered";
            return false;
        }

        /* Check password */
        if(strlen($this->_password)>65){
            $this->_message = "Password is too long - must be under 250 characters";
            return false;
        }
        if(strlen($this->_password)<6){
            $this->_message = "Password is too short - must be 6 or more characters";
            return false;
        }

        /* Check user type */
        if($this->_userType!="intern"&&($this->_userType!="employer"&&$this->_userType!="student")){
            $this->_message = "User type is invalid";
            return false;
        }

        return true;
    }

    /**
     * Calls the hashPassword method in parent class
     */
    private function encryptPassword(){
        $this->_salt = $this->generateSalt();
        $this->_password = $this->hashPassword($this->_password, $this->_salt);
    }

    /**
     * Inserts the user data into the database
     */
    private function insertData(){
        $un = $this->_username; $fn = $this->_firstname; $ln = $this->_lastname; $pw = $this->_password;
        $em = $this->_email; $ct = $this->_category;$yr = $this->_year; $ut = $this->_userType;
        $st = $this->_salt; $dt = $this->_date;
        $this->_dbObj->query_insert("INSERT INTO users (username, firstName, lastName, password, salt, email, category, visibility, bio, cv,  year, userType, dateCreated, verified) ".
            "VALUES('$un','$fn','$ln','$pw','$st','$em','$ct', 'visible', '', '', '$yr', '$ut', '$dt', '0')");
    }

    /* Inserts the employers categgories into table */
    public function insertEmployerCategories($employerCategories){
        $userid = $_SESSION['user_id'];
        foreach($employerCategories as $ic) {
            if($ic!=0) {
                $this->_dbObj->query_insert("INSERT INTO employer_categories (user_id, category_id) VALUES($userid,'$ic')");
            }
        }
    }

    /**
     * Calls log in class
     * @param $password original
     * @return bool true if logged in else false
     */
    private function logUserIn($password){
        $logInObj = new Login();
        if($logInObj->start($this->_username, $password)){
            return true;
        }
        return false;
    }

    /**
     * @return string the _message error message
     */
    public function getMessage(){
        return $this->_message;
    }
}


class UserSettings extends  UserActions{

    private $_message;

    public function getMessage(){
        return $this->_message;
    }

    /* Method to change the users password also checks they are valid for security  */
    public function changePassword($userId, $oldPassword, $newPassword){
        if($this->checkUsersPassword($userId, $oldPassword)){
            if(strlen($newPassword)<6){
                $this->_message = "Password is too short - must be 6 or more characters";
                return false;
            }
            $newSalt = $this->generateSalt();
            $newPassword = $this->hashPassword($newPassword, $newSalt);
            $this->_dbObj->query_insert("UPDATE users SET password='$newPassword', salt='$newSalt' WHERE ID='$userId'");
            $this->logOut();
            return true;
        }
        $this->_message = "Incorrect old password entered";
        return false;
    }

    /* Method to reset the users password, if they have forgotten it, uses validation code */
    public function resetPassword($userId, $newPassword){
        if(strlen($newPassword)<6){
            $this->_message = "Password is too short - must be 6 or more characters";
            return false;
        }
        $newSalt = $this->generateSalt();
        $newPassword = $this->hashPassword($newPassword, $newSalt);
        $this->_dbObj->query_insert("UPDATE users SET password='$newPassword' AND salt='$newSalt' WHERE ID='$userId'");
        return true;

    }

    /* Method to update the users username, requiers valid password */
    public function changeUsername($userId,$pwd, $newUsername){
        if($this->checkUsersPassword($userId, $pwd)){
            if(strlen($newUsername)>50){
                $this->_message = "Username is too long - must be under 50 characters";
                return false;
            }
            if(strlen($newUsername)<3){
                $this->_message = "Username is too short - must be 3 or more characters";
                return false;
            }
            if(count($this->_dbObj->query_get("SELECT * FROM users WHERE username = '$newUsername'"))>0){
                $this->_message = "Username is already taken";
                return false;
            }
            $this->_dbObj->query_insert("UPDATE users SET username='$newUsername' WHERE ID='$userId'");
            return true;
        }
    }

    /* Updates users first name accordingly */
    public function updateFirstName($userId,$pwd, $newFirstName){
        if($this->checkUsersPassword($userId, $pwd)){
            if(!$this->checkNewName($newFirstName)){ return false; }
            $this->_dbObj->query_insert("UPDATE users SET firstName='$newFirstName' WHERE ID='$userId'");
            return true;
        }
    }

    /* Updates users last name accordingly */
    public function updateLastName($userId,$pwd, $newLastName){
        if($this->checkUsersPassword($userId, $pwd)){
//            if(!$this->checkNewName($newLastName)){ return false; }
            $this->_dbObj->query_insert("UPDATE users SET lastName='$newLastName' WHERE ID='$userId'");
            return true;
        }
    }

    /* Updates the users email address */
    public function changeEmail($userId, $pwd, $newEmail){
        if($this->checkUsersPassword($userId, $pwd)){
            if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)){
                $this->_message = "Email address does not appear to be valid";
                return false;
            }
            $this->_dbObj->query_insert("UPDATE users SET email='$newEmail' WHERE ID='$userId'");
            return true;
        }
    }

    /* Permently deletes the users account from the database */
    public function deleteAccount($userId, $pwd){
        if($this->checkUsersPassword($userId, $pwd)){
            $this->_dbObj->query_insert("DELETE FROM users WHERE ID='$userId'");
            $this->logOut();
            return true;
        }
        $this->_message = "Incorrect password entered";
        return false;
    }

    /* Associates social networks to the users account */
    public function addSocialNetwork($service, $url){
        if($this->isLoggedIn()){
            $userId = $_SESSION['user_id'];
            $db = new Db();
            $db->query_insert("INSERT INTO user_socialmedia (user_id, service, url) VALUES ('$userId','$service','$url')");
            return true;
        }
        return false;
    }

    public function getSocialContentFronId($id){
        $db = new Db();
        $q = $db->query_get("SELECT id, service, url FROM user_socialmedia WHERE id = '$id'");
        if(count($q)>0){ return $q; }
        else{ return null;}
    }


    public function removeSocialMedia($id){
        $this->_dbObj->query_insert("DELETE FROM user_socialmedia WHERE id='$id'");
    }

    public function addAdditionalDetails($type, $value, $description){
        if($this->isLoggedIn()){
            $userId = $_SESSION['user_id'];
            $db = new Db();
            $db->query_insert("DELETE FROM user_information WHERE user_id = '$userId' AND type = '$type'");
            $db->query_insert("INSERT INTO user_information (user_id, type, value, description) VALUES ('$userId', '$type', '$value','$description')");
            return true;
        }
        return false;
    }

    public function removeInfo($infoId){
        if($this->isLoggedIn()){
            $userId = $_SESSION['user_id'];
            $this->_dbObj->query_insert("DELETE FROM user_information WHERE id='$infoId' AND user_id='$userId'");
            return true;
        }
        return false;
    }

    public function changeCategory($newCatId){
        $userId = $_SESSION['user_id'];
        if(is_numeric($newCatId)||$newCatId=='none'){
            $this->_dbObj->query_insert("UPDATE users SET category='$newCatId' WHERE ID='$userId'");
            return true;
        }
        $this->_message = "Invalid Category Selected.";
        return fasle;
    }

    public function changeYear($year){
        $userId = $_SESSION['user_id'];
        if($year == "0" ||$year == "1" ||$year == "2" ||$year == "3" ||$year == "4" ||$year == "graduated")
            $this->_dbObj->query_insert("UPDATE users SET year='$year' WHERE ID='$userId'");
            return true;
    }

    public function changeVisibility($vis){
        $userId = $_SESSION['user_id'];
        if($vis == "visible"){ $this->_dbObj->query_insert("UPDATE users SET visibility='visible' WHERE ID='$userId'"); return true; }
        else if($vis == "invisible"){ $this->_dbObj->query_insert("UPDATE users SET visibility='invisible' WHERE ID='$userId'");  return true; }
        $this->_message = "Unknown Visiblity Settings";
        return fasle;
    }

    public function changeDescription($description){
        $userId = $_SESSION['user_id'];
        $this->_dbObj->query_insert("UPDATE users SET bio='$description' WHERE ID='$userId'");
        return true;
    }


    public function updateEmployerCategories($newCategoryList){
        $userId = $_SESSION['user_id'];
        $ua = new UserActions();
        $ua->_dbObj->query_insert("DELETE FROM employer_categories WHERE employer_id = '$userId'");
        foreach ($newCategoryList as $cat) {
            $ua->_dbObj->query_insert("INSERT INTO employer_categories (employer_id, category_id) VALUES ('$userId', '$cat');");
        }
    }


    private function checkNewName($newName){
        if(strlen($newName)>50){
            $this->_message = "Username is too long - must be under 50 characters";
            return false;
        }
        if(strlen($newName)<3){
            $this->_message = "Username is too short - must be 3 or more characters";
            return false;
        }
        return true;
    }
}


class VerifyAccount extends UserActions {

    private $_userId;
    private $_db;
    private $_code;
    private $_userEmail;

    public function __construct($userId){
        $this->_userId = $userId;
        $this->db = new Db();
        $this->code = $this->getCode();
        $this->_userEmail = $this->getUsersEmailAddress();
    }

    public function isAccountVerified(){
        $uid = $this->_userId;
        $q = $this->db->query_get("SELECT verified FROM users WHERE ID = '$uid'");
        if(count($q)>0){ if($q[0]['verified']==1){ return true;} }
        return false;
    }

    public function attemptActivate($usersCode){
        if($usersCode==$this->code){
            $this->markAsActivated();
            return true;  }
        else{ return false; }
    }

    public function sendActivationEmail(){
       
		$this->_code = $this->getCode();
		
        $to = $this->_userEmail;
		
        $subject = "Activate your Account";

        $header  = "Reply-To: InternMagnet <noreply@internmagnet.co.uk>\r\n";
        $header .= "Return-Path: Admin <admin@internmagnet.co.uk>\r\n";
        $header .= "From: InternMagnet <noreply@internmagnet.co.uk>\r\n";
        $header .= "Organization: InternMagnet\r\n";
        $header .= "Content-type: text/html; charset=iso-8859-1";

        $message  =  "<html><body><h1>Welcome. Just one last step to your account being ready to go</h1>"; 
        $message .= '<p>Please <a href="http://demo.internmagnet.co.uk/actions/activate-account.php?code='.$this->_code.'">click here</a> to verify your account</p>';
		$message .= "<p>Your activation code is <b>".$this->_code."</b>.</body></html>";
        $message = wordwrap($message, 70);
        
		mail($to, $subject, $message, $header);

    }

    private function markAsActivated(){
        $uid = $this->_userId;
        $this->db->query_insert("UPDATE users SET verified=1 WHERE ID = '$uid';");
    }

    public  function getCode(){
        $uid = $this->_userId;
        $q = $this->db->query_get("SELECT salt FROM users WHERE ID = '$uid'");
        if(count($q)>0){ return $q[0]['salt']; }
        else{ return ""; }
    }

    private function getUsersEmailAddress(){
        $uid = $this->_userId;
        $q = $this->db->query_get("SELECT email FROM users WHERE ID = '$uid'");
        if(count($q)>0){ return $q[0]['email']; }
        else{ return ""; }
    }

}



class Skills extends UserActions{

    private $_db;

    public function __construct(){
        $this->_db = new Db();
        $this->_db->connect();
    }

    public function getAllSkills(){
        $q = $this->_db->query_get("SELECT * FROM skills");
        if(count($q)<1){return false; }
        else{
            $results = array();
            for($i=0; $i<count($q); $i++){
                $results[]=$q[$i]['name'];
            }
            return $results;
        }
    }

    public function getUsersSkills($userId){
        $q = $this->_db->query_get("SELECT * FROM user_skills WHERE user_id = '$userId'");
        if(count($q)<1){return false; }
        else{
            $skillIds = array();
            for($i=0; $i<count($q); $i++){
                $skillIds[]=$q[$i]['skill_id'];
            }
            $results = $this->getSkillsFromSkillIdArr($skillIds);
            return $results;
        }
    }

    public function addSkillListToUser($skillList, $userId){
        $this->deleteUsersSkills($userId);
        $skillArray = explode(',',  $skillList.',' );
        foreach ($skillArray as $skill) {
            if($skill!=""){
                $searchForSkillQ = $this->_db->query_get("SELECT * FROM skills WHERE name = '$skill';");
                $skillExists = (count($searchForSkillQ)>0? true: false);
                if($skillExists){
                    $skillId = $searchForSkillQ[0]['id']; }
                else{
                    $this->_db->query_insert("INSERT INTO skills (name) VALUES ('$skill');");
                    $newSkillQ = $this->_db->query_get("SELECT id FROM skills WHERE name = '$skill';");
                    $skillId = $newSkillQ[0]['id'];
                }
                $this->_db->query_insert("INSERT INTO user_skills (user_id, skill_id) VALUES ('$userId','$skillId')");
            }

        }
    }


    public function addSkill($skillName){

    }

    private function convertListToArray($skillList){

    }

    private function convertArrayToList($skillArray){

    }

    private function getSkillsFromSkillIdArr($skillIdArr){
        $results  = array();
        for($i=0; $i<count($skillIdArr); $i++){
            $skill = $this->getSkillFromId($skillIdArr[$i]);
            if($skill!=false){ $results[]=$skill; }
        }
        return $results;
    }

    private function getSkillFromId($skillId){
        $q = $this->_db->query_get("SELECT * FROM skills WHERE id = '$skillId'");
        if(count($q)>0){  return $q[0]['name']; }
        else{ return false; }
    }

    private function deleteUsersSkills($userId){
        $this->_db->query_insert("DELETE FROM user_skills WHERE user_id = '$userId'");
    }
}


class Search extends UserActions{
    private $_db;

    public function __construct(){
        $this->_db = new Db();
        $this->_db->connect();
    }

    public function getResltsFromTerm($searchTerm){
        $searchWords = explode(" ", $searchTerm);
        $idResults = array();
        for($i=0; $i<count($searchWords); $i++){
            $currentWord = $searchWords[$i];

            /* Search Skills */
            $s = $this->_db->query_get("SELECT id FROM skills WHERE name LIKE '%$currentWord%' ");
            for($e = 0; $e<count($s); $e++){
               $idResults = array_merge($idResults, $this->getUsersWithSkills($s[$e]['id']));
            }

            /* Search name */
            $q = $this->_db->query_get("SELECT ID FROM users WHERE username LIKE '%$currentWord%'");
            for($e = 0; $e<count($q); $e++){
                $idResults[]= $q[$e]['ID'];
            }

            /* Search location */
            $q = $this->_db->query_get("SELECT user_id FROM user_information WHERE value LIKE '%$currentWord%'");
            for($e = 0; $e<count($q); $e++){
                $idResults[]= $q[$e]['user_id'];
            }


        }
 		$idResults = $this->removeDeadUsers($idResults);
        $idResults = $this->removeInvisibleUsers($idResults);
        $idResults = array_unique($idResults);
        $results = $this->makeIdIntoUserobjArray($idResults);
        return $results;

    }

    public function advancedSearch($searchTerm, $fieldOfStudy, $city, $year, $skills, $userType){

        $idResults = array();

        /* Search Skills */
        $skills = explode(", ", $skills);
        $idResults = array();
        for($i=0; $i<count($skills); $i++){
            $currentSkill = $skills[$i];
            $s = $this->_db->query_get("SELECT id FROM skills WHERE name = '$currentSkill' ");
            for($e = 0; $e<count($s); $e++){
                $idResults = array_merge($idResults, $this->getUsersWithSkills($s[$e]['id']));
            }
        }

        /* Search year*/
        $q = $this->_db->query_get("SELECT ID FROM users WHERE year = '$year'");
        for($e = 0; $e<count($q); $e++){
            $idResults[]= $q[$e]['ID'];
        }

        /* Search field of study*/
        $q = $this->_db->query_get("SELECT id FROM categories WHERE name LIKE '$fieldOfStudy'");
        for($e = 0; $e<count($q); $e++){
            $catId = $q[$e]['id'];
            $q = $this->_db->query_get("SELECT ID FROM users WHERE category = '$catId'");
            for($e = 0; $e<count($q); $e++){
                $idResults[]= $q[$e]['ID'];
            }
        }

        /* Search city */
        $q = $this->_db->query_get("SELECT user_id FROM user_information WHERE value LIKE '%$city%'");
        for($e = 0; $e<count($q); $e++){
            $idResults[]= $q[$e]['user_id'];
        }
        if($userType=="student"||$userType=="employer"){
            $idResults = $this->filterUserType($idResults, $userType);}
        $idResults = $this->removeInvisibleUsers($idResults);
        $idResults = $this->removeLowScoringUsers($idResults);
        $idResults = array_unique($idResults);
        $results = $this->makeIdIntoUserobjArray($idResults);
        return $results;
    }

    private function removeLowScoringUsers($idResults){
        $results = array();
        for($i=0; $i<count($idResults); $i++){
            if(isset($results[$idResults[$i]])){
                $results[$idResults[$i]]++;
            }
            else{
                $results[$idResults[$i]]=1;
            }
        }
        arsort($results);

        $firstRes =  $results[key($results)];

        $finalResults = array();
        while ($fruit_name = current($results)) {
            if($firstRes>2){
                if ($fruit_name > 1) {
                    $finalResults[]= key($results);
                }
            }
            else{
                $finalResults[]= key($results);
            }
            next($results);
        }

        return $finalResults;

    }

    private function filterUserType($allIds, $userType){
        $ua = new UserActions();
        $results = array();
        for($i = 0; $i<count($allIds); $i++){
            $currentUser = $ua->makeUserObject($allIds[$i]);
            if($currentUser->getUserType()==$userType){
                $results[] = $allIds[$i];
            }
        }
        return $results;
    }

	private function removeDeadUsers($allIds){
		$ua = new UserActions();
		$results = array();
		for($i = 0; $i<count($allIds); $i++){
			if($ua->userIdValid($allIds[$i])){
				$results[] = $allIds[$i];
			}
		}
		return $results;
	}

    public function removeInvisibleUsers($allIds){
        $ua = new UserActions();
        $results = array();
        for($i = 0; $i<count($allIds); $i++){
            $c = $ua->makeUserObjectFromId($allIds[$i]);
            if($c!=false){
                if($c->getVisibility()=='visible'){
                    $results[] = $allIds[$i];
                }
            }
        }
        return $results;
    }
	

    public function formatSearchTerm($searchTerm){
        return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($searchTerm))))));
    }

    public function getAllUserIdArr(){
        $q = $this->_db->query_get("SELECT ID FROM users");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[] = $q[$i]['ID'];
        }
        return $results;
    }

    public function makeIdIntoUserobjArray($userIdArr){
        $results = array();
        foreach ($userIdArr as $userId) {
            $newUObj = new User();
            $newUObj->setUserId($userId);
            $results[] = $newUObj;
        }
        return $results;

    }

    public function convertToJson($phpArr){

    }

    public function getAllCities(){
        $q = $this->_db->query_get("SELECT DISTINCT value  FROM user_information WHERE type = 'location'");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[] = $q[$i]['value'];
        }
        return $results;
    }

    public function getAllCategories(){
        $q = $this->_db->query_get("SELECT DISTINCT name  FROM categories");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[] = $q[$i]['name'];
        }
        return $results;
    }


    public function getAllSchools(){
        $q = $this->_db->query_get("SELECT DISTINCT value  FROM user_information WHERE type= 'school' or type='university'");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[] = $q[$i]['value'];
        }
        return $results;
    }

    public function getAllSkills(){
        $q = $this->_db->query_get("SELECT name FROM skills");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[] = $q[$i]['name'];
        }
        return $results;
    }

    public function getUsersWithSkills($skillId){
        $q = $this->_db->query_get("SELECT user_id FROM user_skills WHERE skill_id = '$skillId'");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[] = $q[$i]['user_id'];
        }
        return $results;
    }

    public function getNewUsers(){
        $q = $this->_db->query_get("SELECT * FROM users ORDER BY ID ASC");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $uo = new User();
            $uo->setUserId($q[$i]['ID']);
            $results[] = $uo;
        }
        return $results;
    }

}


class Magnet extends UserActions{

    private $message = "";

    public function getMessage(){
        return $this->message;
    }

    public function addConnection($fromUser, $toUser){
        $dbObj = new Db();
       // Check both users exist
        if(count($dbObj->query_get("SELECT * FROM users WHERE ID = '$fromUser'"))<1){
           $this->message = "Your user ID is invalid";
            return false;
        }
        if(count($dbObj->query_get("SELECT * FROM users WHERE ID = '$toUser'"))<1){
            $this->message = "The person you are trying to connect with does not have a valid ID";
            return false;
        }

        $fromUserRow = $dbObj->query_get("SELECT * FROM users WHERE ID = '$fromUser'");
        $toUserRow = $dbObj->query_get("SELECT * FROM users WHERE ID = '$toUser'");


       // Check both users are currently visible
        if($fromUserRow[0]['visibility']!='visible'){
            $this->message = "You must first change your visiblity to searchable in settings";
            return false;
        }
        if($toUserRow[0]['visibility']!='visible'){
            $this->message = "The user you are trying to conect with is not currently looking to connect with anyone";
            return false;
        }

        // Check the user is not trying to connect with themself (users are pretty dumb, we just have to check!)
        if($fromUser == $toUser){
            $this->message="You can't connect to your self!";
            return false;
        }

       // Check one user is employer and the other is a student
        if($fromUserRow[0]['userType']=='employer' && $toUserRow[0]['userType']=='employer'){
            $this->message = "You con not connect to another employer";
            return false;
        }

        if($fromUserRow[0]['userType']=='student' && $toUserRow[0]['userType']=='student'){
            $this->message = "You con not connect to another student";
            return false;
        }

       // Check there isn't already a connection between them
        if(count($dbObj->query_get("SELECT * FROM magnetise WHERE from_user_id = '$fromUser' AND to_user_id='$toUser'"))>0){
            $this->message = "You are already connected...";
            return false;
        }
        //Add connection!
        $timeStamp = time();
        $dbObj->query_insert("INSERT INTO magnetise (`id`, `from_user_id`, `to_user_id`, `timestamp`) VALUES (NULL, '$fromUser', '$toUser', '$timeStamp');");
        return true; // Looks like everything went as it should :)
    }

    public function removeConnection($connectionId){

    }

    public function getInwardConnections($toUserId){
        $dbObj = new Db();
        $q = $dbObj->query_get("SELECT * FROM magnetise WHERE to_user_id='$toUserId'");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[$i] = new User();
            $results[$i]->setUserId($q[$i]['from_user_id']);
        }
        return $results;
    }

    public function getOutwardConnections($fromUserId){
        $dbObj = new Db();
        $q = $dbObj->query_get("SELECT * FROM magnetise WHERE from_user_id = '$fromUserId'");
        $results = array();
        for($i=0; $i<count($q); $i++){
            $results[$i] = new User();
            $results[$i]->setUserId($q[$i]['to_user_id']);
        }
        return $results;
    }

    public function getTwoWayConnections($userId){
        $outwardConnections = $this->getOutwardConnections($userId);
        $inwardConnections  = $this->getInwardConnections($userId);

        $results = array();
        for($i=0; $i<count($outwardConnections); $i++){
            for($k = 0; $k < count($inwardConnections); $k++){
                if($outwardConnections[$i]->getUserId()==$inwardConnections[$k]->getUserId()){
                    $r = new User();
                    $r->setUserId($outwardConnections[$i]->getUserId());
                    $results[] = $r;
                }
            }
        }
        return $results;
    }

    public function removeAlreadyConnectedUsers($firstList, $connectedList){
        $results = array();
        for($i = 0; $i < count($firstList); $i++){
            $found = false;
            for($k = 0; $k < count($connectedList); $k++ ){
                if($firstList[$i]->getUserId() == $connectedList[$k]->getUserId()){
                    $found = true;
                }
            }
            if(!$found){
                $results[]=$firstList[$i];
            }
        }
        return $results;
    }


    public function deleteConnection($fromUser, $toUser){
        $dbObj = new Db();
        $dbObj->query_insert("DELETE FROM magnetise WHERE from_user_id='$fromUser' AND to_user_id = '$toUser'");
    }


    public function checkIfConnected($fromUser, $toUser){
        $dbObj = new Db();
        if(count($dbObj->query_get("SELECT * FROM magnetise WHERE from_user_id = '$fromUser' AND to_user_id='$toUser'"))>0){
            return true;
        }
        return false;
    }


    public function getTopStudents(){
        return $this->getTopPersons('student');
    }

    public function getTopEmployers(){
        return $this->getTopPersons('employer');
    }

    private function getTopPersons($userType){
        $dbObj = new Db();
        $q = $dbObj->query_get("
            SELECT to_user_id AS user_id
            FROM magnetise
            INNER JOIN users
            ON users.ID=magnetise.to_user_id
            WHERE users.userType = '$userType'
            ORDER BY(to_user_id)");

        $userIds = array();
        for($i = 0; $i< count($q); $i++){
            $userIds[] = $q[$i]['user_id'];
        }
        $uniqueIds = array_values(array_unique($userIds));
        $results = array();

        for($i = 0; $i< count($uniqueIds); $i++){
            $u = $uniqueIds[$i];
            $r = $dbObj->query_get("SELECT count(*) AS count FROM magnetise WHERE to_user_id = '$u'");
            $results[$u] = $r[0]['count'];
        }
        asort($results);

        $return = array();

        foreach ($results as $userIdWin => $countNum) {
            $uo = new User();
            $uo->setUserId($userIdWin);
            array_unshift($return, $uo);
        }

        return $return;
    }





}



class User {

    private $_username;
    private $_userId;

    private $advice;


    public function setUserId($userId){
        $this->_userId = $userId;
    }


    public function getUserId(){
        return $this->_userId;
    }


    public function setUsername($username){
        $this->_username = $username;
    }


    public function getUsername(){

        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT username FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            return $q[0]['username'];
        }
        return "unknown";
    }

    public function getFullName(){
        return $this->getFirstName()." ".$this->getLastName();
    }

    public function getFirstName(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT firstName FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            return ucfirst($q[0]['firstName']);
        }
        return "unknown";
    }

    public function getLastName(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT lastName FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            return ucfirst($q[0]['lastName']);
        }
        return "unknown";
    }


    public function getCompanyName(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT firstName FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            return ucfirst($q[0]['firstName']);
        }
        return "unknown";
    }
    public function getUserJoinDate(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT dateCreated FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            if($q[0]['dateCreated']!='0000-00-00'){
                $mysqldate = $q[0]['dateCreated'];
                $date = $this->make_date(strtotime($mysqldate));
            }
            else{ $date = "unknown"; }
        } else{ $date = "unknown"; }
        return $date;
    }

    public function getUserType(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT userType FROM users WHERE ID = '$uid'");
        if(count($q)>0){
           return $q[0]['userType'];
        }
        return "unknown";
    }

    public function getUserCategory(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT category FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            $c = $q[0]['category'];
        } else{ $c=0; }

        $q2 = $db->query_get("SELECT name FROM categories WHERE ID = '$c'");
        if(count($q2)>0){
            return $q2[0]['name'];
        }
        return "none";
    }

    public function getVisibility(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT visibility FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            if($q[0]['visibility']=="invisible"){ return "invisible"; }
        }
        return "visible";
    }

    public function getYearOfStudy(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT year FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            if($q[0]['year']!=""){
                if ($q[0]['year']=="none"){
                    return "unspecified";
                }
                return $q[0]['year'];}
        }
        return "unspecified";
    }

    public function getFormatedYearOfStudy(){
        $uid = $this->_userId;
        $db = new Db();
        $ua = new UserActions();
        $q = $db->query_get("SELECT year FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            if($q[0]['year']!=""){
                return $ua->formatYearOfStudy($q[0]['year']);}
        }
        return "unspecified";
    }


    public function getUserDescription(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT bio FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            return $q[0]['bio'];
        }
        return "none";
    }

    public function isCvUploaded(){
        if($this->getCvPath()!=""){return true; }
        else{ return false; }
    }

    public function getCvPath(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT cv FROM users WHERE ID = '$uid'");
        if(count($q)>0){
            return $q[0]['cv'];
        }
        return "";
    }

    public function getLastSeenDate(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT time FROM user_logins WHERE user_id = '$uid' AND success = 1 ORDER BY time DESC");
        if(count($q)>0){
            $timestamp = $q[0]['time'];
            $date = $this->make_date($timestamp);
        }else{ $date = "Unknown"; }
        return $date;
    }


    public function getSocialMedia(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT id, service, url FROM user_socialmedia WHERE user_id = '$uid'");
        if(count($q)>0){
            return $q; }
        else{ return null; }
    }


    public function getAdditionalInformation(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT id, type, value, description FROM user_information WHERE user_id = '$uid'");
        if(count($q)>0){
            return $q; }
        else{ return null; }
    }

    public function getSpecificInfo($type){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT id, type, value, description FROM user_information WHERE user_id = '$uid' AND type = '$type'");
        if(count($q)>0){
            return $q; }
        else{ return null; }
    }

    public function getEmailAddress(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT email FROM users WHERE ID = '$uid'");
        return $q[0]['email'];
    }

    public function getGravatar($s = 200, $d = 'mm', $r = 'g'){
        $email = $this->getEmailAddress();
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        return $url;
    }

    public function getUserCity(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT value FROM user_information WHERE user_id = '$uid' AND type='location'");
        if(count($q)>0){
            return $q[0]['value']; }
        else{ return null; }
    }

    public function getUserLine(){
        return ($this->getYearOfStudy()=="unspecified"?" ":($this->getYearOfStudy()).$this->ordinal_suffix($this->getYearOfStudy())." year")." ".
        ($this->getUserCategory()=="none"?" ":$this->getUserCategory())." ".
        ($this->getUserType()=="unknown"?" ":$this->getUserType())."".
        ($this->getUserCity()==null?" ":" from ".$this->getUserCity());
    }


    public function getEmployersCategories(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT id, employer_id, category_id FROM employer_categories WHERE employer_id = '$uid'");
        if(count($q)>0){
            return $q; }
        else{ return null; }
    }

    public function getNumberLogins(){
        $uid = $this->_userId;
        $db = new Db();
        $q = $db->query_get("SELECT * FROM user_logins WHERE user_id = '$uid'");
        return count($q);
    }

    public function calculateProfileCompleteness(){
        $completeness = 0; // number 1 - 10
        $advice = array();
        $userId = $this->getUserId();
        $userObj = new User();
        $userObj->setUserId($userId);
        if($userObj->getUserType()=='employer'){
            return $this->calculateProfileCompleteness_employer();
        }
        //count skills
        $skillsObj = new Skills();
        $userSkills = $skillsObj->getUsersSkills($userId);
        $numSkills = count($userSkills);
        if($numSkills>1){ $completeness++; }
        else{ $advice[] = "Add some skills to your profile";}
        if($numSkills>7){$completeness++;}
        else if($numSkills>2){ $advice[] = "Add some more skills to your profile (aim for at least 7)"; }

        // university
        if($this->getSpecificInfo('university')!=null){ $completeness++; }
        else{ $advice[] = "Add your university to your profile"; }

        // current city
        if($this->getSpecificInfo('location')!=null){ $completeness++; }
        else{ $advice[] = "Add your current location/ city to your profile"; }

        // year of study
        if($this->getYearOfStudy() != "unspecified"){ $completeness++; }
        else{ $advice[] = "Add your current year of study to your profile"; }

        // category
        if($this->getUserCategory() != "unspecified"){ $completeness++; }
        else{ $advice[] = "Add your field of study to your profile"; }

        // about you
        if($this->getUserDescription() != ""){ $completeness++; }
        else{ $advice[] = "Add a short description about yourself"; }

        // cv
        if($this->getCvPath() != ""){ $completeness++; }
        else{ $advice[] = "Upload your CV"; }

        // searchable
        if($this->getVisibility() != "invisible"){ $completeness++; }
        else{ $advice[] = "Set your visibility to searchable so employers can find you"; }

        // social media
        $sm = $this->getSocialMedia();
        if(count($sm)>1){ $completeness++; }
        else{ $advice[] = "Link some of your social media profiles with Intern Magnet"; }

        $this->advice = $advice;
        return $completeness*10;
    }

    public function calculateProfileCompleteness_employer(){
        $completeness = 0; // number 1 - 10
        $advice = array();
        $employer = new User();
        $employer->setUserId($this->getUserId());

        //count categories
        if(count($employer->getEmployersCategories())>1){ $completeness++; }
        else{ $advice[] = "Select some categories you are interest in";}

        // current city
        if($this->getSpecificInfo('location')!=null){ $completeness++; }
        else{ $advice[] = "Add your current location/ city to your profile"; }

        // about you
        if($this->getUserDescription() != ""){ $completeness++; }
        else{ $advice[] = "Add a short description about your company"; }

        // cv
        if($this->getCvPath() != ""){ $completeness++; }
        else{ $advice[] = "Upload your company profile"; }

        // searchable
        if($this->getVisibility() != "invisible"){ $completeness++; }
        else{ $advice[] = "Set your visibility to searchable so employers can find you"; }

        // social media
        $sm = $this->getSocialMedia();
        if(count($sm)>1){ $completeness++; }
        else{ $advice[] = "Link some of your social media profiles with Intern Magnet"; }

        $this->advice = $advice;
        return round($completeness/6*100);
    }

    public function getProfileImprovementTips(){
        $this->calculateProfileCompleteness();
        return $this->advice;
}

    private function make_date($ts){
        $suffix = $this->ordinal_suffix(date('j',$ts));
        $date1 = date('D j',$ts);
        $date2 = date(' F  Y',$ts);
        $date = $date1.$suffix.$date2;
        return $date;
    }

    private function ordinal_suffix($num){
        if($num < 11 || $num > 13){
            switch($num % 10){
                case 1: return 'st';
                case 2: return 'nd';
                case 3: return 'rd';
            }
        }
        return 'th';
    }

}

/*---------------------------*\
---- @autohor Alicia Sykes ----
---- (C) Alicia Sykes 2015 ----
---- http://aliciasykes.com ---
\*---------------------------*/


