<?php declare(strict_types=1);

namespace day13\a;

use Monolog\Logger;
use RuntimeException;

final class Solution13A
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution13A())->getNonStaticResult($inputFile, $logger);
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
                $candidates[] = $i;
            }
            $lastRow = $currentRow;
        }

        $r = array();

        // check if it is a mirror
        foreach ($candidates as $c) {
            $valid = true;
            for($i = 0; $i < $s; $i++) {

                if(!isset($rows[$c + $i]) || !isset($rows[$c - $i - 1])) {
                    break;
                }

                if($rows[$c + $i] != $rows[$c - $i - 1]){
                    $valid = false;
                    break;
                }
            }

            if($valid)
                $r[] = $c;
        }

        return $r[0] ?? 0;
    }

}