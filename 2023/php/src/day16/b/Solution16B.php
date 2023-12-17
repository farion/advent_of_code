<?php declare(strict_types=1);

namespace day16\b;

use Monolog\Logger;

final class Solution16B
{
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

    private array $grid = [];

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution16B())->getNonStaticResult($inputFile, $logger);
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

        $candidates = [];

        $maxX = sizeof($this->grid) - 1;
        $maxY = sizeOf($this->grid[0]) - 1;

        for ($x = 0; $x < sizeof($this->grid); $x++) {
            for ($y = 0; $y < sizeof($this->grid[$x]); $y++) {

                if ($x == 0)
                    $candidates[] = $this->getCandidate($x, $y, ">");
                if ($y == 0)
                    $candidates[] = $this->getCandidate($x, $y, "v");
                if ($x == $maxX)
                    $candidates[] = $this->getCandidate($x, $y, "<");
                if ($y == $maxY)
                    $candidates[] = $this->getCandidate($x, $y, "^");

            }
        }

        print_r($candidates);

        return max($candidates);

    }

    private function calculateEnergy(int $x, int $y, string $d, &$energy): void
    {
        if (!isset($this->grid[$x][$y]) || isset($energy[$x][$y][$d])) {
            return;
        }

        $energy[$x][$y][$d] = "#";
        $n = $this->grid[$x][$y];

        $moves = self::$MOVES[$d][$n];

        foreach ($moves as $move) {
            $this->calculateEnergy($x + $move[0][0], $y + $move[0][1], $move[1], $energy);
        }

    }

    private function printEnergy(): void
    {
        for ($x = 0; $x < sizeof($this->grid); $x++) {
            for ($y = 0; $y < sizeof($this->grid[$x]); $y++) {
                if (isset($this->energy[$x][$y])) {
                    echo "#";
                } else {
                    echo $this->grid[$x][$y];
                }
            }
            echo "\n";
        }
    }

    private function getEnergy(&$energy): int
    {
        $r = 0;
        for ($x = 0; $x < sizeof($this->grid); $x++) {
            for ($y = 0; $y < sizeof($this->grid[$x]); $y++) {
                if (isset($energy[$x][$y])) {
                    $r += 1;
                }
            }
        }
        return $r;
    }

    private function getCandidate(int $x, int $y, string $d): int
    {
        $grid = array();
        $energy = array();
        $this->calculateEnergy($x, $y, $d, $energy);
        return $this->getEnergy($energy);
    }
}