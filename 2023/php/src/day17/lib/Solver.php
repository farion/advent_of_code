<?php

namespace day17\lib;

use Fisharebest\Algorithm\Dijkstra;

class Solver
{
    private array $grid = array();
    private array $edges = array();
    private int $maxX;
    private int $maxY;

    public function getResult(string $inputFile, int $minSlide, int $maxSlide): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        foreach ($lines as $y => $line) {
            if (!trim($line))
                continue;
            for ($x = 0; $x < strlen($line); $x++) {
                $this->grid[$x][$y] = $line[$x];
            }
        }

        $this->maxX = sizeof($this->grid) - 1;
        $this->maxY = sizeOf($this->grid[0]) - 1;

        // fill edges
        foreach ($this->grid as $x => $col) {
            foreach ($col as $y => $cost) {
                for ($i = $minSlide; $i <= $maxSlide; $i++) {
                    $this->setCost("-", $x - $i, $y, "|", $x, $y);
                    $this->setCost("-", $x + $i, $y, "|", $x, $y);
                    $this->setCost("|", $x, $y - $i, "-", $x, $y);
                    $this->setCost("|", $x, $y + $i, "-", $x, $y);
                }
            }
        }

        // fill entry and out point
        $this->edges["S"]["0:0-"] = 0;
        $this->edges["S"]["0:0|"] = 0;
        $this->edges[$this->maxX . ":" . $this->maxY . "|"]["T"] = 0;
        $this->edges[$this->maxX . ":" . $this->maxY . "-"]["T"] = 0;
        $this->edges["T"] = [];

        // do some nice dijkstra
        $algorithm = new Dijkstra($this->edges);
        $route = $algorithm->shortestPaths("S", "T");

        // calculate costs on first found path
        $cost = 0;
        for ($i = 0; $i < sizeof($route[0]); $i++) {
            if ($i == 0)
                continue;
            $cost += $this->edges[$route[0][$i - 1]][$route[0][$i]];
        }

        return $cost;
    }


    private function setCost(string $prevSplit, int $prevX, int $prevY, string $split, int $x, int $y): void
    {
        if (!isset($this->grid[$prevX][$prevY]))
            return;

        if ($prevX == $this->maxX && $prevY == $this->maxY)
            return;

        $from = $prevX . ":" . $prevY . $prevSplit;
        $to = $x . ":" . $y . $split;

        $c = 0;
        if ($prevX == $x && $prevY < $y) {
            for ($cy = $prevY + 1; $cy <= $y; $cy++) {
                $c += $this->grid[$x][$cy];
            }
        }
        if ($prevX == $x && $prevY > $y) {
            for ($cy = $prevY - 1; $cy >= $y; $cy--) {
                $c += $this->grid[$x][$cy];
            }
        }
        if ($prevX < $x && $prevY == $y) {
            for ($cx = $prevX + 1; $cx <= $x; $cx++) {
                $c += $this->grid[$cx][$y];
            }
        }
        if ($prevX > $x && $prevY == $y) {
            for ($cx = $prevX - 1; $cx >= $x; $cx--) {
                $c += $this->grid[$cx][$y];
            }
        }

        $this->edges[$from][$to] = $c;
    }
}