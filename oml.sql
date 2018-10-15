/*adding some rows of information */

INSERT INTO user(userId, userHandle, userEmail, userType, userHash) VALUES (unhex("09b76ac0d0b311e8a8d5f2801f1b9fd1"), "Duffman", "duffman@ooooya.com", "Reader", "211372d1f43044229b61bebbafd82f32");

INSERT INTO user(userId, userHandle, userEmail, userType, userHash) VALUES (unhex("c01dcc72d0b411e8a8d5f2801f1b9fd1"), "Dr. Hibbert", "hibbertMD@normalpractice.com", "Author", "cd86eee4d05840d6aab6db7e64f1f953");

INSERT INTO user(userId, userHandle, userEmail, userType, userHash) VALUES (unhex("4ca78066d0b511e89e50f2801f1b9fd1"), "Mr. Burns", "1@1.com", "Reader", "433f5ba9bf624ca38aa2251b4c48945d");

/* updating a row */

UPDATE user SET userHandle = "W. Smithers" WHERE userId = unhex("4ca78066d0b511e89e50f2801f1b9fd1");

/* deleting a row */

DELETE FROM user WHERE userId = unhex("09b76ac0d0b311e8a8d5f2801f1b9fd1");

/* inserting into another table with a foreign key */

INSERT INTO article(articleId, articleUserId, articleAge, articleContent) VALUES (unhex("f5f60394d0b611e8a8d5f2801f1b9fd1"), unhex("c01dcc72d0b411e8a8d5f2801f1b9fd1"), NOW(), "I am happy you are not suing me for the sandwich I accidentally left inside during your last appendectomy");

/* selecting a row using a primary key */

SELECT userId, userHandle, userEmail, userType FROM user WHERE userId = unhex("09b76ac0d0b311e8a8d5f2801f1b9fd1");

/* select statement with a inner join with a where statement */

SELECT article.articleId, article.articleUserId, article.articleAge, articleContent FROM article INNER JOIN user ON article.articleUserId = user.userId WHERE articleId = "f5f60394d0b611e8a8d5f2801f1b9fd1";

/* Select statement based off of DDC-Twitter that counts number of likes for a specific tweet */

SELECT `like`.likeProfileId, `like`.likeDate FROM `like` INNER JOIN tweet ON `like`.likeTweetId = tweet.tweetId WHERE tweetId = "foo111bar222baz333qux444quux555c";

SELECT COUNT(likeProfileId) FROM `like` WHERE likeTweetId = "foo111bar222baz333qux444quux555c";
