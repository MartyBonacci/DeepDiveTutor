<?php

/**
 * Class Skill
 * calling skill table, and defining restrictions on type to pushed through and validated if not validated will throw errors
 */
class Skill implements  \JsonSerializable{
	/**
	 * @var skillId is an int its the id of the skill ; a primary key
	 */
	private $skillId;
	/**
	 * @var skillName is a string it will actually house the names of the skills specified by admin;
	 */
	private $skillName;

	/**
	 * Skill constructor.
	 * @param int|null $newSkillId test if this skill id is null or an int
	 * @param string $newSkillName test if this skill id is a string, if it holds under the 32 character limit, and if its an empty value
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data value is out of bounds
	 * @throws \Exception if some other exception occurs
	 * @throw \TypeError if data types violate type hints
	 */
	public function __construct(?int $newSkillId, string $newSkillName){
		try {
			$this->setSkillId($newSkillId);
			$this->setSkillName($newSkillName);
		}
		catch (\InvalidArgumentException| \RangeException |\Exception | \TypeError $exception){
			$exceptionType = get_class($exception);
			throw(new exceptionType($exception->getMessage(),0,$exception));
					}
	}

	/**
	 * accessor method for skillId
	 * @return int|null value of skillId
	 */
	public function getSkillId(): int {
		return $this->skillId;
	}

	/**
	 * @param mixed int
	 */
	public function setSkillId(int $newSkillId): void {
		if ($newSkillId<= 0){
			throw(new \RangeException("This Skill Id is not positive"));
		}
		$this->skillId = $newSkillId;
	}
	/**
	 * @return mixed
	 */
	public function getSkillName(): string {
		return $this->skillName ;
	}

	public function setSkillName(string $newSkillName): void{
	$newSkillName = trim($newSkillName);
	$newSkillName = filter_var($newSkillName,FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($newSkillName)=== true){
		throw(new \InvalidArgumentException("The Skill Name is Empty!"));
	}
	if(strlen($newSkillName)>32){
		throw(new \RangeException("Skill Name is Too Looooong!"));
	}
	$this->skillName = $newSkillName;
}