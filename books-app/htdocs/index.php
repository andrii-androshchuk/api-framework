<?php

error_reporting(0);

define("APPLICATION_NAME", "Books", true);

define("API_FRAMEWORK_PATH", "S:\\libraries\\api-framework\\framework/", true);
define("APPLICATION_PATH", "S:/libraries/api-framework/books-app/", true);

require_once API_FRAMEWORK_PATH . "framework.php";
require_once APPLICATION_PATH . "app/" . APPLICATION_NAME . ".API.php";

$application_name = strval(APPLICATION_NAME) . "API";

$application = new $application_name();