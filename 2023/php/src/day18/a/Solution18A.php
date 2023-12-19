<?php declare(strict_types=1);

namespace day18\a;

use day18\lib\Solver;
use Monolog\Logger;

final class Solution18A
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution18A())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $pipe = [];
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        foreach ($lines as $y => $line) {
            if (!trim($line))
                continue;
            preg_match("/^([A-Z]) ([0-9]+) \((#[0-9a-z]{6})\)$/", $line, $matches);
            $pipe[] = [$matches[1], $matches[2]];
        }

        return (new Solver())->getResult($pipe);
    }
}