<?php declare(strict_types=1);

namespace day12\b;

use day11\lib\Solver;
use Monolog\Logger;

final class Solution12B
{
    public static function getResult(string $inputFile, Logger $logger, $factor = 2): int
    {
        return (new Solver())->getResult($inputFile, $logger, $factor);
    }
}