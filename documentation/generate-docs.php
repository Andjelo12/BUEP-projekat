<?php

require("../vendor/autoload.php");
//Generate docs
$openapi = \OpenApi\Generator::scan(['/path/to/project']);
//Write new docs to .json file
$jsonDoc = fopen("swagger-docs.json", "w");
fwrite($jsonDoc, $openapi->toJson());
fclose($jsonDoc);
echo 'Done, check root folder of this script for .json docs';