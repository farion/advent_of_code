<?php declare(strict_types=1);

namespace day21\a;

use Monolog\Logger;

final class Solution21A
{

    private Logger $logger;

    private array $grid = [];

    private array $steps = [];

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

        $this->runTurn(64);

        return $this->getCount();
    }

    private function nextTurn($i): array
    {
        $steps = [];
        foreach ($this->steps as $x => $stepCol) {
            foreach ($stepCol as $y => $stepCell) {
                foreach ($this->getNextStep($x, $y, $i) as $step) {
                    $steps[$step[0]][$step[1]] = true;
                }
            }
        }
        return $steps;
    }

    private function getNextStep($x, $y, $i):array
    {
        $steps = [];

        if (isset($this->grid[$x][$y - 1]) && $this->grid[$x][$y - 1] != "#")
            $steps[] = [$x, $y - 1];

        if (isset($this->grid[$x][$y + 1]) && $this->grid[$x][$y + 1] != "#")
            $steps[] = [$x, $y + 1];

        if (isset($this->grid[$x - 1][$y]) && $this->grid[$x - 1][$y] != "#")
            $steps[] = [$x - 1, $y];

        if (isset($this->grid[$x + 1][$y]) && $this->grid[$x + 1][$y] != "#")
            $steps[] = [$x + 1, $y];

        return $steps;

    }

    private function getCount(): int
    {
        $c = 0;
        foreach ($this->steps as $stepCol) {
            foreach ($stepCol as $stepCell) {
                $c++;
            }
        }
        return $c;
    }

    private function runTurn(int $rounds):void
    {
        $this->steps = [];
        $this->steps[$this->startX][$this->startY] = true;

        for ($i = 0; $i < $rounds; $i++) {
            $this->steps = $this->nextTurn($i);
        }
    }
}