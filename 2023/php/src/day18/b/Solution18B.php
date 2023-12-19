<?php declare(strict_types=1);

namespace day18\b;

use day18\lib\Solver;
use Monolog\Logger;

final class Solution18B
{
    private $dMapping = [
        0 => "R",
        1 => "D",
        2 => "L",
        3 => "U"
    ];

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution18B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $pipe = [];
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        foreach ($lines as $y => $line) {
            if (!trim($line))
                continue;
            preg_match("/^([A-Z]) ([0-9]+) \(#([0-9a-z]{5})([0-9a-z])\)$/", $line, $matches);
            $pipe[] = [$this->dMapping[$matches[4]], hexdec($matches[3])];
        }
        return (new Solver())->getResult($pipe);
    }
}