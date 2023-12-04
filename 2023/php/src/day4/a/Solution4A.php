<?php declare(strict_types=1);

namespace day4\a;

final class Solution4A
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;

        foreach ($lines as $i => $line) {
            $splittedLine = preg_split('/[|:]/', $line);
            preg_match_all("([0-9]+)", $splittedLine[1], $winning);
            preg_match_all("([0-9]+)", $splittedLine[2], $having);
            $winning = $winning[0];
            $having = $having[0];
            $lineMatchCount = 0;
            foreach ($winning as $winNumber) {
                $lineMatchCount += in_array($winNumber, $having) ? 1 : 0;
            }
            $result += floor(pow(2, $lineMatchCount-1));
        }

        return intval($result);
    }
}