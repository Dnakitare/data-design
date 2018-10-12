ALTER DATABASE  dnakitare CHARACTER SET 	utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS star;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS article;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
	userId BINARY (16) NOT NULL,
	userActivationToken CHAR (32),
	userEmail VARCHAR (128) NOT NULL,
	userHandle VARCHAR (32) NOT NULL,
	userType VARCHAR(16) NOT NULL,
	userHash VARCHAR(97) NOT NULL,
	UNIQUE (userEmail),
	UNIQUE (userHandle),
	PRIMARY KEY (userId)
);

CREATE TABLE article (
	articleId      BINARY(16) NOT NULL,
	articleUserId  BINARY(16) NOT NULL,
	articleAge     DATETIME   NOT NULL,
	articleContent TEXT       NOT NULL,
	INDEX (articleUserId),
	FOREIGN KEY (articleUserId) REFERENCES user(userId),
	PRIMARY KEY (articleId)
);

CREATE TABLE comment (
	commentId BINARY (16) NOT NULL,
	commentUserId BINARY (16) NOT NULL,
	commentAge DATETIME NOT NULL,
	commentContent VARCHAR(1024) NOT NULL,
	INDEX (commentUserId),
	FOREIGN KEY (commentUserId) REFERENCES user(userId),
	PRIMARY KEY (commentId)
);

CREATE TABLE star (
	starUserId BINARY (16) NOT NULL,
	starCommentId BINARY (16) NOT NULL,
	starAge DATETIME NOT NULL,
	INDEX (starUserId),
	INDEX (starCommentId),
	FOREIGN KEY (starUserId) REFERENCES user(userId),
	FOREIGN KEY (starCommentId) REFERENCES comment(commentId),
	PRIMARY KEY (starUserId, starCommentId)
);
