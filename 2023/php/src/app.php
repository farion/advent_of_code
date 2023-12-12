<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Garden\Cli\Cli;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;

$cli = new Cli();

$cli->description('Advent Of Code 2023 PHP Edition')
    ->opt('day:d', 'Set the day', true, 'integer')
    ->opt('task:t', 'Task of day a or b', true)
    ->opt('input:i', 'Specify input file');

$args = $cli->parse($argv, true);

$day = $args->getOpt("day");
$task = $args->getOpt("task");
$input = $args->getOpt("input");

if (!$input) {
    $input = "../resources/" . $day . "/input.txt";
}

$logger = new Logger("AoC2023");
$stream_handler = new StreamHandler("php://stdout", Level::Info);
$logger->pushHandler($stream_handler);

echo ("\day" . $day . "\\" . strtolower($task) . "\\Solution" . $day . strtoupper($task).'::getResult')($input,$logger);
echo "\n";