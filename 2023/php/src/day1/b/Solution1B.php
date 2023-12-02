<?php declare(strict_types=1);

namespace day1\b;

final class Solution1B
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;
        $num_words = array("one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        foreach ($lines as $line) {
            if (strlen($line) == 0) continue;
            $findings = array();
            for ($i = 0; $i < sizeof($num_words); $i++) {
                $offset = 0;
                while (($pos = strpos($line, $num_words[$i], $offset)) !== false) {
                    $offset = $pos + 1;
                    array_push($findings, array($pos, ($i < 9) ? $i + 1 : intval($num_words[$i])));
                }
            }
            usort($findings, function ($a, $b) {
                return $a[0] > $b[0];
            });
            $result += intval($findings[0][1] . $findings[sizeof($findings) - 1][1]);
        }
        return $result;
    }
}
