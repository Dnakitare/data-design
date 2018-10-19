<?php
namespace Dnakitare\DataDesign;
require_once (dirname(__DIR__, 1) . "/vendor/autoloader.php");
use Ramsey\Uuid\Uuid;
/**
 * Trait to validate a uuid
 *
 * This trait will validate a uuid in any of the following three formats:
 *
 * - Human readable string (36 bytes)
 * - Binary string (16 bytes)
 * - Ramsey\Uuid\Uuid object
 *
 * @author Dylan McDonald
 */
trait VaildateUuid {
	/**
	 * validate a uuid irrespective of format
	 *
	 * @param string|Uuid $newUuid uuid to validate
	 * @return Uuid object with validated uuid
	 * @throws \InvalidArgumentException if $newUuid is not a valid uuid
	 * @throws \RangeException if $newUuid is not a valid uuid v4
	 */
	private static function validateUuid($newUuid) : Uuid {
		// verify a string uuid
		if(gettype($newUuid) === "string") {
			// 16 character is binary data from mySQL - convert to string and fall to next if block
			if(strlen($newUuid) === 16) {
				$newUuid = bin2hex($newUuid);
				$newUuid = substr($newUuid, 0, 8) . "-" . substr( $newUuid, 8, 4) . "-" . substr($newUuid, 12, 4) . "-" . substr($newUuid, 16, 4) . "-" . substr($newUuid, 20, 12);
			}
			// 36 characters is a human readable uuid
			if(strlen($newUuid) === 36) {
				if(Uuid::isValid($newUuid) === false) {
					throw(new \InvalidArgumentException("invalid uuid"));
				}
				$uuid = Uuid::fromString($newUuid);
			}
			else {
				throw (new \InvalidArgumentException("invalid uuid"));
			}
		}
		elseif(gettype($newUuid) === "object" && get_class($newUuid) === "Ramsey\\Uuid\Uuid") {
			// if the misquote id is already a valid UUId, move on
			$uuid = $newUuid;
		}
		else {
			// throw out any other trash
			throw (new \InvalidArgumentException("invalid uuid"));
		}
		// verify the uuid is uuid v4
		if($uuid->getVersion() !== 4) {
			throw (new \RangeException("uuid is incorrect version"));
		}
		return ($uuid);
	}
}
/**
 * Created by PhpStorm.
 * User: overlord
 * Date: 10/17/18
 * Time: 2:00 PM
 */