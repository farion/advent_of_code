<?php declare(strict_types=1);

namespace day16\a;

use Monolog\Logger;
use RuntimeException;

final class Solution16A
{
    private array $grid = array();

    private array $energy = array();

    private static array $MOVES = [
        ">" => [
            "\\" => [[[0, 1], "v"]],
            "/" => [[[0, -1], "^"]],
            "-" => [[[1, 0], ">"]],
            "|" => [[[0, 1], "v"], [[0, -1], "^"]],
            "." => [[[1, 0], ">"]]
        ],
        "<" => [
            "\\" => [[[0, -1], "^"]],
            "/" => [[[0, 1], "v"]],
            "-" => [[[-1, 0], "<"]],
            "|" => [[[0, 1], "v"], [[0, -1], "^"]],
            "." => [[[-1, 0], "<"]]
        ],
        "v" => [
            "\\" => [[[1, 0], ">"]],
            "/" => [[[-1, 0], "<"]],
            "-" => [[[-1, 0], "<"], [[1, 0], ">"]],
            "|" => [[[0, 1], "v"]],
            "." => [[[0, 1], "v"]]
        ],
        "^" => [
            "\\" => [[[-1, 0], "<"]],
            "/" => [[[1, 0], ">"]],
            "-" => [[[-1, 0], "<"], [[1, 0], ">"]],
            "|" => [[[0, -1], "^"]],
            "." => [[[0, -1], "^"]]
        ]
    ];


    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution16A())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        foreach ($lines as $y => $line) {
            if (!trim($line))
                continue;
            for ($x = 0; $x < strlen($line); $x++) {
                $this->grid[$x][$y] = $line[$x];
            }
        }

        $this->calculateEnergy(0, 0, ">");

        return $this->getEnergy();
    }

    private function calculateEnergy(int $x, int $y, string $d): void
    {
        if (!isset($this->grid[$x][$y]) || isset($this->energy[$x][$y][$d])) {
            return;
        }

        $this->energy[$x][$y][$d] = "#";
        $n = $this->grid[$x][$y];

        $moves = self::$MOVES[$d][$n];

        foreach ($moves as $move) {
            $this->calculateEnergy($x + $move[0][0], $y + $move[0][1], $move[1]);
        }

    }

    private function printEnergy(): void
    {
        for ($x = 0; $x < sizeof($this->grid); $x++) {
            for ($y = 0; $y < sizeof($this->grid[$x]); $y++) {
                if (isset($this->energy[$x][$y])) {
                    echo "#";
                }else{
                    echo $this->grid[$x][$y];
                }
            }
            echo "\n";
        }
    }

    private function getEnergy(): int
    {
        $r = 0;
        for ($x = 0; $x < sizeof($this->grid); $x++) {
            for ($y = 0; $y < sizeof($this->grid[$x]); $y++) {
                if (isset($this->energy[$x][$y])) {
                    $r += 1;
                }
            }
        }
        return $r;
    }
}