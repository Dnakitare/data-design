<?php
namespace Dnakitare\DataDesign;
require_once (dirname("/Classes/autoload.php"));
/**
 * Trait to Validate a mySQL date
 *
 * This trait will inject a private method to validate a my SQL style date. It will convert a string representation to a DateTime object or throw an exception.
 */
trait ValidateDate {
	/**
	 * custom filter for mySQl date
	 *
	 * Converts a string to a DateTime object; this is designed to be used within a mutator method.
	 *
	 * @param \DateTime|string $newDate date to validate
	 * @return \Datetime DateTime object containing the validated date
	 * @throws \InvalidArgumentException if the date is in an invalid format
	 * @throws \TypeError when type hints fail
	 */
	private static function validateDate($newDate) : \DateTime {
		// base case: if the date is a DateTime object, there is no work to be done
		if(is_object($newDate) === true && get_class($newDate) === "DateTime") {
			return ($newDate);
		}
		// treat the date as a mySQL data string: Y-m-d
		$newDate = trim($newDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $newDate, $matches)) !== 1) {
			throw (new \InvalidArgumentException("date is not valid date"));
		}
		// verify the date is really a valid calendar date
		$year = intval($matches[1]);
		$month = intval($matches[2]);
		$day = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw (new \RangeException("date is not a Gregorian date"));
		}
		// if we got here, the dat is clean
		$newDate = \DateTime::createFromFormat("Y-m-d H:i:s", $newDate . " 00:00:00");
		return($newDate);
	}
	/** custom filter for mySQL style dates
	 *
	 *Converts a string to a DateTime object; this is designed to be used within a mutator method.
	 *
	 *@param mixed $newDateTime date to validate
	 *@return \DateTime DateTime object containing the validated date
	 *@throws \InvalidArgumentException if the date is in an invalid format
	 *@throws \RangeException if the date is not a Gregorian date
	 *@throws \TypeError when type hints fail
	 *@throws \Exception id some other error occurs
	 */
	private static function validateDateTime($newDateTime) : \DateTime {
		// base case: if the date is a DateTime object, there is not work to be done
		if(is_object($newDateTime) === true && get_class($newDateTime) === "DateTime") {
			return($newDateTime);
		}
		try {
			list($date, $time) = explode(" ", $newDateTime);
			$date = self::validateDate($date);
			$time = self::validateTime($time);
			list($hour, $minute, $second) = explode(":", $time);
			list($second, $microseconds) = explode(":", $second);
			$date->setTime($hour, $minute, $second, $microseconds);
			return($date);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType ($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * custom filter for mySQL style times
	 *
	 * validates a time string; this is design to be used within a mutator method.
	 *
	 * @param string $newTime time to validate
	 * @return string validated time as a string H:i:s[.u]
	 * @throws \InvalidArgumentException if the date is in an invalid format
	 * @throws \RangeException if the date is not a Gregorian date
	 */
	private static function validateTime(string $newTime) : string {
		// treat the date as a my SQL date string: H:i:s[.u]
		$newTime = trim($newTime);
		if((preg_match("/^(\d{2}):(\d{2}):(\d{2})(?(?=\.)\.(\d{1,6}))$/", $newTime, $matches)) !== 1) {
			throw (new \InvalidArgumentException("time is not a valid time"));
		}
		// verify the date is really a valid calender date
		$hour = intval($matches[1]);
		$minute = intval($matches[2]);
		$second = intval($matches[3]);
		// verify the time is really a valid wall clock time
		if($hour < 0 || $hour >= 24 || $minute < 0 || $minute >= 60 || $second < 0 || $second >= 60) {
			throw (new \RangeException("date is not valid wall clock time"));
		}
		// put a placeholder for microseconds if they do not exist
		$microseconds = $matches[4] ?? "0";
		$newTime = "$hour:$minute:$second.$microseconds";
		// if we got here, the date is clean
		return($newTime);
	}
}
