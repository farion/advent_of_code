<?php declare(strict_types=1);

namespace day14\b;

use Monolog\Logger;
use RuntimeException;

final class Solution14B
{
    private array $columns = array();

    private $cache = array();


    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution14B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        foreach ($lines as $k => $line) {
            for ($i = 0; $i < strlen($line); $i++) {
                $this->columns[$i][$k] = $line[$i];
            }
        }

        $cycles = 1000000000;
        for ($i = 0; $i < $cycles; $i++) {

            $this->tiltToNorth();
            $this->tiltToWest();
            $this->tiltToSouth();
            $this->tiltToEast();
            $situationId = $this->getSituationId();

            if (isset($this->cache[$situationId])) {
                $skip = $i - $this->cache[$situationId];
                if ($i + $skip < $cycles) {
                    $i = $cycles - floor(($cycles - $i) % $skip);
                }
            } else {
                $this->cache[$situationId] = $i;
            }
        }

        return $this->countWeight();
    }

    private function tiltToNorth(): void
    {
        for ($c = 0; $c < sizeof($this->columns); $c++) {
            $this->tiltColumnToNorth($c);
        }
    }

    private function tiltToSouth(): void
    {
        for ($c = 0; $c < sizeof($this->columns); $c++) {
            $this->tiltColumnToSouth($c);
        }
    }

    private function tiltToWest(): void
    {
        for ($r = 0; $r < sizeof($this->columns[0]); $r++) {
            $this->tiltRowsToWest($r);
        }
    }

    private function tiltToEast(): void
    {
        for ($r = 0; $r < sizeof($this->columns[0]); $r++) {
            $this->tiltRowsToEast($r);
        }
    }

    private function tiltColumnToNorth(int $x): void
    {
        for ($y = 0; $y < sizeOf($this->columns[$x]); $y++) {
            if ($this->columns[$x][$y] === "O") {
                $j = $y;
                while (isset($this->columns[$x][$j - 1]) && $this->columns[$x][$j - 1] === ".") {
                    $this->columns[$x][$j - 1] = "O";
                    $this->columns[$x][$j] = ".";
                    $j--;
                }
            }
        }
    }

    private function tiltColumnToSouth(int $c)
    {
        for ($i = sizeOf($this->columns[$c]) - 1; $i >= 0; $i--) {
            if ($this->columns[$c][$i] === "O") {
                $j = $i;
                while (isset($this->columns[$c][$j + 1]) && $this->columns[$c][$j + 1] === ".") {
                    $this->columns[$c][$j + 1] = "O";
                    $this->columns[$c][$j] = ".";
                    $j++;
                }
            }
        }
    }

    private function tiltRowsToWest(int $y): void
    {
        for ($x = 0; $x < sizeof($this->columns); $x++) {
            if ($this->columns[$x][$y] === "O") {
                $j = $x;
                while (isset($this->columns[$j - 1][$y]) && $this->columns[$j - 1][$y] === ".") {
                    $this->columns[$j - 1][$y] = "O";
                    $this->columns[$j][$y] = ".";
                    $j--;
                }
            }
        }
    }

    private function tiltRowsToEast(int $r): void
    {
        for ($i = sizeof($this->columns) - 1; $i >= 0; $i--) {
            if ($this->columns[$i][$r] === "O") {
                $j = $i;
                while (isset($this->columns[$j + 1][$r]) && $this->columns[$j + 1][$r] === ".") {
                    $this->columns[$j + 1][$r] = "O";
                    $this->columns[$j][$r] = ".";
                    $j++;
                }
            }
        }
    }

    private function getSituationId(): string
    {
        $hash = "";
        for ($y = 0; $y < sizeof($this->columns[0]); $y++) {
            for ($x = 0; $x < sizeof($this->columns); $x++) {
                $hash .= $this->columns[$x][$y];
            }
        }
        return $hash;
    }

    private function countWeight(): int
    {
        $r = 0;
        foreach ($this->columns as $column) {
            $r2 = 0;
            $reverseColumns = array_combine(array_keys($column), array_reverse(array_values($column)));
            foreach ($reverseColumns as $k => $reverseColumn) {
                if ($reverseColumn === "O") {
                    $r2 += $k + 1;
                }
            }
            $r += $r2;
        }

        return $r;
    }

    /* debug
    private function printMap()
    {
        for ($y = 0; $y < sizeof($this->columns[0]); $y++) {
            for ($x = 0; $x < sizeof($this->columns); $x++) {
                echo $this->columns[$x][$y];
            }
            echo "\n";
        }
        echo "\n";
    }
    */


}