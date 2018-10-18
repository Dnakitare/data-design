<?php


class article {
	private $articleId;
	private $articleUserId;
	private $articleAge;
	private $articleContent;

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

	public function getArticleId() : Uuid {
		return $this->articleId;
	}

	public function setArticleId($articleId) : void {
		try {
			$uuid = self::validateUuid($newArticleId);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {

		}
		$this->articleId = $uuid;
	}

	public function getArticleUserId() : Uuid {
		return $this->articleUserId;
	}

	public function setArticleUserId($articleUserId) : void {
		try {
			$uuid = self::validateUuid($newArticleUserId);
		}
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->articleUserId = $uuid;
	}

	public function getArticleAge() : \DateTime {
		return $this->articleAge;
	}

	public function setArticleAge($articleAge = null) : void {
		if($newArticleAge === null) {
			$this->articleAge = new \DateTime();
			return;
		}
		try {
			$newArticleAge = self::validateDatetime($newArticleAge);
		}
		catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw (new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->articleAge = $articleAge;
	}


	public function getArticleContent() {
		return $this->articleContent;
	}


	public function setArticleContent(string $articleContent): void {
		$newArticleContent = trim($newArticleContent);
		$newArticleContent = filter_var($newArticleContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArticleContent) === true) {
			throw (new \InvalidArgumentException("article content is empty"));
		}
		if(strlen($newArticleContent) > 4000) {
			throw (new \RangeException("article content is to large"));
		}
		$this->articleContent = $articleContent;
	}


}
?>

/**
 * Created by PhpStorm.
 * User: overlord
 * Date: 10/17/18
 * Time: 1:59 PM
 */