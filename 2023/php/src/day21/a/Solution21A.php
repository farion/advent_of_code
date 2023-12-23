<?php declare(strict_types=1);

namespace day21\a;

use Monolog\Logger;

final class Solution21A
{

    private Logger $logger;
    private array $grid = [];
    private int $startX;
    private int $startY;

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution21A())->getNonStaticResult($inputFile, $logger);
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

        return $this->runTurn(64);
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
        if ($this->isInGrid($x, $y - 1)) $steps[$x . "-" . ($y - 1)] = [$x, $y - 1];
        if ($this->isInGrid($x, $y + 1)) $steps[$x . "-" . ($y + 1)] = [$x, $y + 1];
        if ($this->isInGrid($x - 1, $y)) $steps[($x - 1) . "-" . $y] = [$x - 1, $y];
        if ($this->isInGrid($x + 1, $y)) $steps[($x + 1) . "-" . $y] = [$x + 1, $y];
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

    private function isInGrid($x, int $y)
    {
        return isset($this->grid[$x][$y]) && $this->grid[$x][$y] != "#";
    }
}