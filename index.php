<!DOCTYPE html>
	<head>
		<meta charset="UTF-8">
		<title>Data Design Project</title>
	</head>
	<body>
		<h1>Data Design Project</h1>
		<h2>User Persona:</h2>
		<img src="Sven.jpg" alt="Sven Thorsfriend" height="500">
		<ul>
			<li><strong>Name:</strong> Sven Thorsfriend</li>
			<li><strong>Age:</strong> 35</li>
			<li><strong>Gender:</strong> Male</li>
			<li><strong>Location:</strong> Savannah, Georgia</li>
			<li><strong>Profession:</strong> Corporate Lawyer</li>
			<li><strong>Relationship Status:</strong> Married, No Children</li>
			<li><strong>Hobbies:</strong> Restoring Classic German Sports Cars, Autocross, Photography</li>
			<li><strong>Likes: </strong> Clean design and easily accessible and sharable information.</li>
			<li><strong>Owned Technology:</strong></li>
			<ul>
				<li><strong>Phone:</strong> One+ 6 running Oreo based OxygenOS</li>
				<li><strong>Laptop:</strong> 15" MacbookPro early 2017 OS X 10.14</li>
				<li><strong>Smart Watch:</strong> Apple Watch 4</li>
				<li><strong>Camera:</strong> Nikon D850 DLSR, Light L16</li>
			</ul>
			<li><strong>Technology Comfort Level:</strong></li>
			<ul>
				<li>Very comfortable with technology and active daily on social
					media platforms (facebook, instagram, pintrest)</li>
			</ul>
			<li><strong>Technological Needs:</strong></li>
			<ul>
				<li>A robust comment system</li>
				<li>Share linking to social media</li>
				<li>Easy access to the work of favorite writers</li>
			</ul>
			<li><strong>Frustrations:</strong></li>
			<ul>
				<li>Sifting through multiple pages to find previously posted articles</li>
				<li>Required waiting periods before comments become public</li>
				<li>Bad article suggestions on differing interest categories</li>
			</ul>
		</ul>
		<h2>User Case</h2>
		<p>Sven wants to be able to visit a website between client meetings that will provide great information on the
			state of the automotive industry<br> while also providing extremely entertaining articles on all things cars.
			Because he not only tracks his cars on the weekends but also rebuilds<br> old cars in his garage, he would like
			to be able to interact with the authors and other readers of his favorite articles. He would also like to<br>
			share articles he finds interesting or amusing with his social network easily.</p>
		<h2>User Story:</h2>
		<p>Sven wants to be informed and entertained with all things automobile related in his free time with the option to
			interact with fellow readers.</p>
		<h3>Interaction Flow</h3>
		<ol>
			<li>Sven visits Jalopnik.com on his laptops web browser.</li>
			<li>The server recognizes his activation token and returns Jalopnik's homepage.</li>
			<li>He clicks on search icon at the top left of the page and it prompted to input a keyword.</li>
			<li>He types in "BMW" and hits enter.</li>
			<li>The server returns a Jalopnik search results page with results containing the key word organized by the most published first.</li>
			<li>Sven clicks on the most recent article (server returns article page) and starts reading.</li>
			<li>After reading the article, Sven scrolls down and makes a comment in the Discussion section asking the article's author a question.</li>
		</ol>
		<h3>Conceptual Design</h3>
		<h4>User</h4>
		<ul>
			<li>userId (primary key)</li>
			<li>userActivationToken</li>
			<li>userHandle</li>
			<li>userEmail</li>
			<li>userType</li>
			<li>userHash</li>
		</ul>
		<h4>Article</h4>
		<ul>
			<li>articleId (primary key)</li>
			<li>articleUserId (foreign key)</li>
			<li>articleAge</li>
			<li>articleContent</li>
		</ul>
		<h4>Comment</h4>
		<ul>
			<li>commentId (primary key)</li>
			<li>commentUserId (foreign key)</li>
			<li>commentAge</li>
			<li>commentContent</li>
		</ul>
		<h4>Star</h4>
		<ul>
			<li>starUserId (foreign key)</li>
			<li>starCommentId (foreign key)</li>
			<li>starAge</li>
		</ul>
		<h4>Relationships</h4>
		<ul>
			<li>one user (type author) can write many articles (1 to n)</li>
			<li>many users can star many comments (n to m)</li>
		</ul>
		<img src="erd-data-design.jpg" alt="ERD Data-design">
	</body>