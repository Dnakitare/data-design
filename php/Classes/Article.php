<?php
namespace Dnakitare\DataDesign;

require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;

class article {
	/**
	 * bring in traits: ValidateDate, ValidateUuid
	 */
	use ValidateDate;
	use VaildateUuid;
	/**
	 * id for this article; this is a primary key
	 * @var Uuid $articleId
	 */
	private $articleId;
	/**
	 * id of the profile that created this article; this is a foreign key
	 * @var Uuid $articleUserId
	 */
	private $articleUserId;
	/**
	 * date and time this article was created; in a PHP Datetime object
	 * @var \DateTime $articleAge
	 */
	private $articleAge;
	/**
	 * the actual textual content of the article
	 * @var string $articleContent
	 */
	private $articleContent;

	/**
	 * constructor for this Article
	 *
	 * @param string|Uuid $newArticleId id of this rticle or null if new article
	 * @param string|Uuid $newArticleUserId id of the user that created this article
	 * @param \DateTime|string|null $newArticleAge date and time article was created or null if set to current date and time
	 * @param string $newArticleContent the content of the article
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @thorws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 */

	public function __construct($newArticleId, $newArticleUserId, $newArticleAge, $newArticleContent) {
		try {
			$this->setArticleId($newArticleId);
			$this->setArticleUserId($newArticleUserId);
			$this->setArticleAge($newArticleAge);
			$this->setArticleContent($newArticleContent);
		}

		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0 , $exception));
		}
	}

	/**
	 * accessor method for article id
	 *
	 * @return Uuid value of the article id
	 */
	public function getArticleId() : Uuid {
		return $this->articleId;
	}
	/**
	 * mutator method for article id
	 *
	 * @param Uuid | string $newArticleId new value of the article id
	 * @throws \RangeException if $newArticleId is not postive
	 * @throws \TypeError
	 */
	public function setArticleId($newArticleId) : void {
		try {
			$uuid = self::validateUuid($newArticleId);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {

		}
		$this->articleId = $uuid;
	}

	/**
	 * accessor method for article user id
	 *
	 * @return Uuid value of the article user id
	 */
	public function getArticleUserId() : Uuid {
		return $this->articleUserId;
	}
	/**
	 * mutator method for article user id
	 *
	 * @param string | Uuid $newArticleUserId
	 * @throws \RangeException if $newArticleUserId is not positive
	 * @throws \TypeError if $newArticleUserId is not an string
	 */
	public function setArticleUserId($newArticleUserId) : void {
		try {
			$uuid = self::validateUuid($newArticleUserId);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the article user id
		$this->articleUserId = $uuid;
	}

	/**
	 * accessor method for article date
	 *
	 * @return \DateTime value of article date
	 */
	public function getArticleAge() : \DateTime {
		return $this->articleAge;
	}
	/**
	 * mutator method for article date
	 *
	 * @param \DateTime|string|null $newArticleAge article date as a Datetime object or a string (use current time if null)
	 * @throws \InvalidArgumentException if $newArticleAge is not a valid object or string
	 * @throws \RangeException if $newArticleAge is a date that does not exits
	 */
	public function setArticleAge($newArticleAge = null) : void {
		// base case: if the date is null, use the current dat and time
		if($newArticleAge === null) {
			$this->articleAge = new \DateTime();
			return;
		}
		// store the article date using the ValidateDate trait
		try {
			$newArticleAge = self::validateDateTime($newArticleAge);
		}
		catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->articleAge = $newArticleAge;
	}

	/**
	 * accessor method for article contents
	 *
	 * @return string contents of article
	 */
	public function getArticleContent() {
		return $this->articleContent;
	}

	/**
	 * mutator method for article contents
	 *
	 * @param string $newArticleContent new value of article content
	 * @throws \InvalidArgumentException if $newArticleContent is empty
	 * @throws \RangeException if $newArticleContent is longer than 4000 characters
	 */
	public function setArticleContent(string $newArticleContent): void {
		// verify the article content is secure
		$newArticleContent = trim($newArticleContent);
		$newArticleContent = filter_var($newArticleContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArticleContent) === true) {
			throw (new \InvalidArgumentException("article content is empty"));
		}
		// verify the article content will fit in the database
		if(strlen($newArticleContent) > 4000) {
			throw (new \RangeException("article content is to large"));
		}
		// store the article content
		$this->articleContent = $newArticleContent;
	}


}
?>
