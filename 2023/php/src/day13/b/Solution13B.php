<?php declare(strict_types=1);

namespace day13\b;

use Monolog\Logger;
use RuntimeException;

final class Solution13B
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution13B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $rowsMap = array();
        $columnsMap = array();
        $i = 0;

        // parse rows to deep array
        foreach ($lines as $line) {
            if (!trim($line)) {
                $i++;
                continue;
            }
            $rowsMap[$i][] = str_split($line);
        }

        // rotate to columns
        foreach ($rowsMap as $k => $rows) {
            $columns = $rows;
            array_unshift($columns, null);
            $columns = call_user_func_array('array_map', $columns);
            $columns = array_map('array_reverse', $columns);
            $columnsMap[$k] = $columns;
        }

        // count
        $result = 0;
        foreach ($rowsMap as $k => $rows) {
            $result += $this->findMirror($rows) * 100;
            $result += $this->findMirror($columnsMap[$k]);
        }

        return $result;
    }

    private function findMirror(array $rows)
    {
        $candidates = array();
        $s = sizeof($rows);

        // find double lines
        $lastRow = $rows[0];
        for ($i = 1; $i < $s; $i++) {
            $currentRow = $rows[$i];
            if ($currentRow == $lastRow) {
                $smudge = false;
                if ($this->checkMirror($rows, $i,$smudge)){
                    if($smudge)
                        $candidates[] = $i;
                }
            }

            if ($this->matchesWithSmudge($currentRow, $lastRow)) {
                $smudge = false;
                if ($this->checkMirror($rows, $i, $smudge))
                    if($smudge)
                        $candidates[] = $i;
            }

            $lastRow = $currentRow;
        }

        return $candidates[0] ?? 0;
    }

    private function matchesWithSmudge($a, $b)
    {
        return levenshtein(implode($a), implode($b)) == 1;
    }

    private function checkMirror(array $rows, int $c, &$smudge = false)
    {
        $valid = true;
        for ($i = 0; $i < sizeof($rows); $i++) {

            if (!isset($rows[$c + $i]) || !isset($rows[$c - $i - 1])) {
                break;
            }

            $matchesWithSmudge = false;
            if (!$smudge) {
                if ($matchesWithSmudge = $this->matchesWithSmudge($rows[$c + $i], $rows[$c - $i - 1])) {
                    $matchesWithSmudge = true;
                    $smudge = true;
                }
            }

            if ($rows[$c + $i] != $rows[$c - $i - 1] && !$matchesWithSmudge) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

}