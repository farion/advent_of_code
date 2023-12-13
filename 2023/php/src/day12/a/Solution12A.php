<?php declare(strict_types=1);

namespace day12\a;

use Monolog\Logger;
use RuntimeException;

final class Solution12A
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution12A())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $result = 0;
        foreach ($lines as $y => $line) {
            if (!trim($line)) continue;
            preg_match_all("/([?.#]+)/", $line, $row);
            preg_match_all("/([0-9]+)/", $line, $counts);

            $result += $this->tryToPlaceNextGroup($row[0][0], $counts[0]);
        }
        return $result;
    }

    public function tryToPlaceNextGroup($line, $counts): int
    {
        // solution found
        if (sizeof($counts) == 0 && preg_match("/^[.?]*$/", $line))
            return 1;

        // Dead end -> no solution
        // No counts, but still hashes
        // Counts, but line is empty
        if (sizeof($counts) == 0 && preg_match("/^.*#.*$/", $line) ||
            sizeof($counts) > 1 && $line == "")
            return 0;


        // fork ways
        $result = 0;

        // skip possible dots
        $result += preg_match("/^[.?].*/", $line) ? $this->tryToPlaceNextGroup(substr($line, 1), $counts) : 0;

        // place group and try with next subset.
        $result += preg_match("/^[?#].*/", $line) ? $this->trySolutionWithHash($line, $counts) : 0;

        return $result;
    }

    private function trySolutionWithHash($line, $counts)
    {
        $currentLength = strlen($line);
        $nextCount = intval($counts[0]);

        // next group does not fit -> no solution
        if ($currentLength < $nextCount) return 0;

        // next group can not be placed -> no solution
        if (!preg_match("/^[#?]+$/", substr($line, 0, $nextCount))) return 0;

        // next group starts directly -> no solution
        if (isset($line[$nextCount]) && $line[$nextCount] == '#') return 0;

        return $this->tryToPlaceNextGroup(
            substr($line, $nextCount + 1),
            array_slice($counts, 1)
        );
    }

    // debug
    private function isValidRow($row, $counts)
    {
        preg_match_all("/([#]+)/", $row, $springs);

        foreach ($counts as $k => $c) {
            if (!isset($springs[0][$k]) || strlen($springs[0][$k]) != $c) {
                return false;
            }
        }
        return true;
    }

}