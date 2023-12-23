<?php declare(strict_types=1);

namespace day21\b;

use Monolog\Logger;

final class Solution21B
{

    private Logger $logger;
    private array $grid = [];
    private int $startX;
    private int $startY;
    private int $height;
    private int $width;

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution21B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        for ($y = 0; $y < sizeof($lines); $y++) {
            $line = $lines[$y];
            if (!trim($line))
                break;
            for ($x = 0; $x < strlen($line); $x++) {
                $this->grid[$x][$y] = $line[$x];
                if ($line[$x] == "S") {
                    $this->startX = $x;
                    $this->startY = $y;
                }
            }
        }

        $rounds = 26501365;

        $this->height = sizeof($this->grid[0]);
        $this->width = sizeof($this->grid);

        $half = $rounds % $this->width;

        $mapExpand = (int)($rounds / $this->width);

        $x1 = $this->runTurn($half);
        $x2 = $this->runTurn($this->width + $half);
        $x3 = $this->runTurn($this->width * 2 + $half);

        $a = (pow($mapExpand, 2) - 3 * $mapExpand + 2) / 2;
        $b = (pow($mapExpand, 2) - 2 * $mapExpand + 0) / -1;
        $c = (pow($mapExpand, 2) - 1 * $mapExpand + 0) / 2;

        return $a * $x1 + $b * $x2 + $c * $x3;
    }

    private function nextTurn($steps): array
    {
        $nextsteps = [];
        foreach ($steps as $step) {
            foreach ($this->getNextStep($step[0], $step[1]) as $nextstep) {
                $nextsteps[$nextstep[0] . "-" . $nextstep[1]] = [$nextstep[0], $nextstep[1]];
            }
        }
        return $nextsteps;
    }

    private function getNextStep($x, $y): array
    {
        $steps = [];
        if ($this->isInGridOrExpand($x, $y - 1)) $steps[$x . "-" . ($y - 1)] = [$x, $y - 1];
        if ($this->isInGridOrExpand($x, $y + 1)) $steps[$x . "-" . ($y + 1)] = [$x, $y + 1];
        if ($this->isInGridOrExpand($x - 1, $y)) $steps[($x - 1) . "-" . $y] = [$x - 1, $y];
        if ($this->isInGridOrExpand($x + 1, $y)) $steps[($x + 1) . "-" . $y] = [$x + 1, $y];
        return $steps;
    }

    private function runTurn(int $rounds): int
    {
        $steps = [];
        $steps[$this->startX . "-" . $this->startY] = [$this->startX, $this->startY];
        for ($i = 0; $i < $rounds; $i++) {
            $steps = $this->nextTurn($steps);
        }
        return sizeof($steps);
    }

    private function isInGridOrExpand($x, int $y)
    {
        $newX = ($x % $this->width + $this->width) % $this->width;
        $newY = ($y % $this->height + $this->height) % $this->height;
        return isset($this->grid[$newX][$newY]) && $this->grid[$newX][$newY] != "#";
    }
}