<?php
namespace Dnakitare\DataDesign;

require_once "autoload.php";
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

class Article implements \JsonSerializable {
	/**
	 * bring in traits: ValidateDate, ValidateUuid
	 */
	use ValidateDate;
	use ValidateUuid;
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
	 * @param string|Uuid $newArticleId id of this article or null if new article
	 * @param string|Uuid $newArticleUserId id of the user that created this article
	 * @param \DateTime|string|null $newArticleAge date and time article was created or null if set to current date and time
	 * @param string $newArticleContent the content of the article
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @thorws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 */

	public function __construct($newArticleId, $newArticleUserId, $newArticleAge, string $newArticleContent) {
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
	 * @param \DateTime|string|null $newArticleAge article date as a Datetime object or a string (use
	 * current time if null)
	 * @throws \InvalidArgumentException if $newArticleAge is not a valid object or string
	 * @throws \RangeException if $newArticleAge is a date that does not exits
	 * @throws \Exception if some other exception occurs
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
			throw (new \RangeException("article content is too large"));
		}
		// store the article content
		$this->articleContent = $newArticleContent;
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 */
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);

		$fields["articleId"] = $this->articleId->toString();
		$fields["articleUserId"] = $this->articleUserId->toString();

		// formats the date so that the front end can consume it
		$fields["articleAge"] = round(floatval($this->articleAge->format("U.u")) * 1000);
		return($fields);
	}

	// PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO PDO
	/**
	 * inserts this Article into mySQl
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError is $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO article(articleId, articleUserId, articleAge, articleContent) VALUES (:articleId, :articleUserId, :articleAge, :articleContent)";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$formattedDate = $this->articleAge->format("Y-m-d H:i:s.u");
		$parameters = ["articleId" => $this->articleId->getBytes(), "articleUserId" => $this->articleUserId->getBytes(), "articleAge" => $formattedDate, "articleContent" => $this->articleContent];
		$statement->execute($parameters);
	}

	/**
	 * delete this Article from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError is $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo) : void {
		// create query template
		$query = "DELETE FROM article WHERE articleId = :articleId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["articleId" => $this->articleId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * update this Article in mySQl
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQl related erros occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function update(\PDO $pdo) : void {
		//create query template
		$query = "UPDATE article SET articleUserId = :articleUserId, articleAge = :articleAge, articleContent = :articleContent WHERE articleId = :articleId";
		$statement = $pdo->prepare($query);

		$formattedDate = $this->articleAge->format("Y-m-d H:i:s.u");
		$parameters = ["articleId" => $this->articleId->getBytes(), "articleUserId" => $this->articleUserId->getBytes(), "articleAge" => $formattedDate, "articleContent" => $this->articleContent];
		$statement->execute($parameters);
	}

	/**
	 * gets the Article by articleId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $articleId article id to search for
	 * @return Article|null Article found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 */
	public static function getArticlebyArticleId(\PDO $pdo, $articleId) : ?Article {
		//sanitize the articleId before searching
		try {
			$tweetId = self::validateUuid($articleId);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT articleId, articleUserId, articleAge, articleContent FROM article WHEN articleId = :articleId";
		$statement = $pdo->prepare($query);

		// bind the article id to the place holder in the template
		$parameters = ["articleId" => $articleId->getBytes()];
		$statement->execute($parameters);

		// grab the article from mySQL
		try {
			$article = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$article = new Article($row["articleID"], $row["articleUserId"], $row["articleAge"], $row["articleContent"]);
			}
		}
		catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($article);
	}

	/**
	 * gets the Article by user id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $articleUserId profile id to search by
	 * @return \SplFixedArray SplFixedArray of Articles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */

	public static function getArticleByUserId(\PDO $pdo, $articleUserId) : \SplFixedArray {
		try{
			$articleUserId = self::validateUuid($articleUserId);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT articleId, articleUserId, articleAge, articleContent FROM article WHERE articleUserId = :articleUserId";
		$statement = $pdo->prepare($query);
		// bind the article user id to the place holder in the template
		$parameters = ["articleUserId" => $articleUserId->getBytes()];
		$statement->execute($parameters);
		// build an array of articles
		$articles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$article = new Article($row["articleId"], $row["articleUserID"], $row["articleAge"], $row["articleContent"]);
				$articles[$articles->key()] = $article;
				$articles->next();
			}
			catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($articles);
	}

	/**
	 * get the Article by content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $articleContent article content to earch for
	 * @return \SplFixedArray SplFixedArray of Articles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getArticleByArticleContent(\PDO $pdo, string $articleContent) : \SplFixedArray {
		// sanitize the description before searching
		$articleContent = trim($articleContent);
		$articleContent = filter_var($articleContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($articleContent) === true) {
			throw (new \PDOException("article content is invalid"));
		}

		// escape any mySQL wild cards
		$articleContent = str_replace("_", "\\_", str_replace("%", "\\%", $articleContent));

		// create query template
		$query = "SELECT articleId, articleUserId, articleAge, articleContent FROM article WHERE articleContent LIKE :articleContent";
		$statement = $pdo->prepare($query);

		// bind the article content to the place holder in the template
		$articleContent ="%$articleContent%";
		$parameters = ["articleContent" => $articleContent];
		$statement->execute($parameters);

		// build an array of articles
		$articles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$article = new Article($row["articleId"], $row["articleUserId"], $row["articleAge"], $row["articleContent"]);
				$articles[$articles->key()] = $article;
				$articles->next();
			}
			catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($articles);
	}

	/**
	 * get all Articles
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Tweets found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getAllArticles(\PDO $pdo) : \SplFixedArray {
		// create query template
		$query = "SELECT articleId, articleUserId, articleAge, articleContent FROM article";
		$statement = $pdo->prepare($query);
		$statement->execute();

		//build an array of tweets
		$articles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$article = new Article($row["articleId"], $row["articleUserId"], $row["articleAge"], $row["articleContent"]);
				$articles[$articles->key()] = $article;
				$articles->next();
			}
			catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($articles);
	}

}

