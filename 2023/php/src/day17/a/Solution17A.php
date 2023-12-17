<?php declare(strict_types=1);

namespace day17\a;

use day17\lib\Solver;
use Monolog\Logger;

final class Solution17A
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solver())->getResult($inputFile, 1, 3);
    }

}