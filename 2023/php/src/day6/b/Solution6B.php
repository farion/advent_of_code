<?php declare(strict_types=1);

namespace day6\b;
use Monolog\Logger;

final class Solution6B
{
    public static function getResult(string $inputFile, Logger $logger): float
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        preg_match_all("/[0-9]+/", str_replace(" ", "", $lines[0]), $timeMatches);
        preg_match_all("/[0-9]+/", str_replace(" ", "", $lines[1]), $distanceMatches);

        $a = -1;
        $b = intval($timeMatches[0][0]);
        $c = -intval($distanceMatches[0][0]);

        $x1 = (-$b + sqrt(pow($b,2) - (4 * $a * $c))) / (2 * $a);
        $x2 = (-$b - sqrt(pow($b,2) - (4 * $a * $c))) / (2 * $a);

        return floor($x2) - ceil($x1) + 1;
    }
}