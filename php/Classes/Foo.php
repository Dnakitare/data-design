<?php

namespace Foo;

class user {
	private $userId;
	private $userActivationToken;
	private $userEmail;
	private $userHandle;
	private $userType;
	private $userHash;


	public function __construct($newUserId, $newUserActivationToken, $newUserEmail, $newUserHandle, $newUserType, $newUserHash) {
		try {
			$this->setUserId($newUserId);
			$this->setActivationToken($newUserActivationToken);
			$this->setUserEmail($newUserEmail);
			$this->setUserHandle($newUserHandle);
			$this->setUserType($newUserType);
			$this->setUserHash($newUserHash);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
		}
	}
?>

/**
 * Created by PhpStorm.
 * User: overlord
 * Date: 10/17/18
 * Time: 1:59 PM
 */