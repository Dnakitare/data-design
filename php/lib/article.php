<?php
namespace Dnakitare\DataDesign;

require_once ("article.php");
require_once (dirname(__DIR__, 2). "/Classes/Article.php");



// instantiate
$cars = new Article("f581f26fc4eb4c139fb047b980723f74","f581f26fc4eb4c139fb047b980723f74","18-2-2", "this is a new article");
$cars->setArticleContent("this is a new article");
echo "What article is this? " . $cars->getArticleContent();

