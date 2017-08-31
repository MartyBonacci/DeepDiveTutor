<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DeepDiveTutor\{
	Skill, ProfileSkill
};

/**
 *
 * api for the skill class
 * @author Gdavis@cnm.edu
 *
 */
//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/deepdivetutor.ini");
	// mock a logged in user by mocking the session and assigning a specific user to it.
	//this is only for testing purposes and dhould not be in the live code.
	//$_SESSION["profile"] =$Profile::getProfileByProfileId($pdo, 732);

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	//$skillId = filter_input(INPUT_GET, "skillId", FILTER_VALIDATE_INT);
	//$profileId = filter_input(INPUT_GET,"profileId", FILTER_VALIDATE_INT);

	if($method === "GET") {
//set xsrf cookie
		setXsrfCookie();

		//get a specific skillname based on SkillID and update reply
		if(empty ($id) === false) {
			$skill = Skill::getSkillNameBySkillId($pdo, $id);
			if($skill !== null) {
				$reply->data = $skill;
			}
		} //Get All SkillNames and update reply
		else {
			$skills = Skill::getAllSkillNames($pdo)->toArray();
			if($skills !== null) {
				$reply->data = $skills;
			}
		}
	}

	// POST new profileSkill
	if($method === "POST") {


		//skill id is a required field
		if(empty($id) === true) {
			throw(new \InvalidArgumentException ("No skill id present", 405));
		}

		// enforce the user is signed in and only trying to edit their own profile
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you are not allowed to access this profile", 403));
		}

		$profileSkill = new ProfileSkill($_SESSION["profile"]->getProfileId(), $id);
		$profileSkill->insert($pdo);
		$reply->message = "Profile Skill added ok";
	}


	if($method === "DELETE") {

		// enforce the user is signed in and only trying to edit their own profile
		if(empty($_SESSION["profile"]) === true ) {
			throw(new \InvalidArgumentException("you are not allowed to access this profile", 403));
		}

		$profileSkill = ProfileSkill::getProfileSkillProfileIdAndProfileSkillSkillId($pdo,$_SESSION["profile"]->getProfileId(), $id);
		if ($profileSkill === null){
			throw(new \InvalidArgumentException("can not delete non-existent profile skill", 418));
		}
		$profileSkill->delete($pdo);
		$reply->message = "Profile Skill deleted ok";

	}

} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type:application/json");
if($reply->data === null) {
	unset($reply->data);
}
//encodes json
echo json_encode($reply);