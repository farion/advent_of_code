<?php declare(strict_types=1);

namespace day12\b;

use day11\lib\Solver;
use day12\a\Solution12A;
use Monolog\Logger;

final class Solution12B
{

    private array $solutions = array();

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution12B())->getNonStaticResult($inputFile, $logger);
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

            $unfoldedLine = substr(str_repeat($row[0][0] . "?", 5), 0, -1);
            $unfoldedCounts = array_merge(...array_fill(0, 5, $counts[0]));

            $result += $this->tryToPlaceNextGroup($unfoldedLine, $unfoldedCounts);
        }
        return $result;
    }

    public function tryToPlaceNextGroup($line, $counts): int
    {
        $id = $line."-".join("-",$counts);

        if (isset($this->solutions[$id])) {
            return $this->solutions[$id];
        }

        // solution found
        if (sizeof($counts) == 0 && preg_match("/^[.?]*$/", $line))
            $result = 1;

        // Dead end -> no solution
        // No counts, but still hashes
        // Counts, but line is empty
        elseif (sizeof($counts) == 0 && preg_match("/^.*#.*$/", $line) ||
            sizeof($counts) > 1 && $line == "")
            $result = 0;

        // fork ways
        else {
            $result = 0;

            // skip possible dots
            $result += preg_match("/^[.?].*/", $line) ? $this->tryToPlaceNextGroup(substr($line, 1), $counts) : 0;

            // place group and try with next subset.
            $result += preg_match("/^[?#].*/", $line) ? $this->trySolutionWithHash($line, $counts) : 0;
        }

        $this->solutions[$id] = $result;

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