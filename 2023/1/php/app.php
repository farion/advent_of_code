<?php declare(strict_types=1);

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('a\\', __DIR__ . "/a");
$loader->addPsr4('b\\', __DIR__ . "/b");

use a\SolutionA;
use b\SolutionB;

$task = $argv[1];
$inputFile = $argv[2];

if($task === "b"){
    $result = SolutionA::getResult($inputFile);
} else {
    $result = SolutionB::getResult($inputFile);
}

echo "Result: " . $result . "\n";