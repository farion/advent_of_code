<?php declare(strict_types=1);

namespace day11\b;

use day11\lib\Solver;
use Monolog\Logger;

final class Solution11B
{
    public static function getResult(string $inputFile, Logger $logger, $factor = 1000000): int
    {
        return (new Solver())->getResult($inputFile, $logger, $factor);
    }
}