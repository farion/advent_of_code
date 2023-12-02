<?php declare(strict_types=1);

namespace a;

final class SolutionA
{
    public static function getResult(string $inputFile): int
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