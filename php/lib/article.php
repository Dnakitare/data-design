<?php
namespace Dnakitare\DataDesign;

require_once(dirname(__DIR__, 1) . "/Classes/Article.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");




// instantiate
$cars = new Article("249574f8-8700-48f1-8924-d42667c95045","6cef5099-fa5c-4d21-9f63-5e2a7b88d727", null, "this is a new article");
$cars->setArticleContent("this is a new article");
echo "What article is this? " . $cars->getArticleContent();

