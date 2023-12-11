<?php declare(strict_types=1);

namespace day1\a;
use Monolog\Logger;

final class Solution1A
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;
        foreach ($lines as $line) {
            if (strlen($line) == 0) continue;
            preg_match_all("/[0-9]/", $line, $matches);
            $size = sizeof($matches[0]);
            $result += intval($matches[0][0] . $matches[0][$size - 1]);
        }
        return $result;
    }
}