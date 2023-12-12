<?php

namespace day11\lib;

use Monolog\Logger;

class Solver
{

    private Logger $logger;
    private array $universe = array();

    public function getResult(string $inputFile, Logger $logger, $factor = 2): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        foreach ($lines as $y => $line) {
            for ($x = 0; $x < strlen($line); $x++) {
                $this->universe[$y][$x] = $line[$x];
            }
        }

        $galaxies = $this->nameGalaxies();
        $this->printUniverse();

        $pairs = $this->findGalaxyPairs($galaxies);

        $emptyYs = $this->getEmptyRowYs();
        $this->universe = $this->rotateUniverse($this->universe);
        $emptyXs = $this->getEmptyRowYs();
        $this->universe = $this->rotateUniverse($this->universe);

        return $this->calculateShortestPathSum($pairs, $emptyXs, $emptyYs, $factor);
    }

    private function printUniverse()
    {

        foreach ($this->universe as $y => $rows) {
            $line = "";
            foreach ($rows as $x => $cell) {
                $line .= $cell;
            }
            $this->logger->debug($line);
        }
        $this->logger->debug("----");
    }

    private function rotateUniverse(array $expandedUniverse)
    {
        $rotatedUniverse = array();
        foreach ($expandedUniverse as $y => $row) {
            foreach ($row as $x => $cell) {
                $rotatedUniverse[$x][$y] = $cell;
            }
        }
        return $rotatedUniverse;
    }

    private function nameGalaxies()
    {
        $galaxy = 1;
        $galaxies = array();
        foreach ($this->universe as $y => $row) {
            foreach ($row as $x => $cell) {
                if ($this->universe[$y][$x] === "#") {
                    $this->universe[$y][$x] = $galaxy;
                    $galaxies[$galaxy] = array($x, $y);
                    $galaxy++;
                }
            }
        }

        return $galaxies;
    }

    private function findGalaxyPairs(array $galaxies)
    {
        $pairs = array();
        foreach ($galaxies as $name => $galaxy) {
            foreach ($galaxies as $name2 => $galaxy2) {
                if ($name < $name2) {
                    $pairs[] = array(
                        "from" => array("name" => $name, "galaxy" => $galaxy),
                        "to" => array("name" => $name2, "galaxy" => $galaxy2)
                    );
                }
            }
        }
        return $pairs;
    }

    private function calculateShortestPathSum(array $pairs, $emptyXs, $emptyYs, $factor)
    {
        $pathSum = 0;
        foreach ($pairs as $pair) {
            $pathSum += $this->calculateShortestPath($pair, $emptyXs, $emptyYs, $factor);
        }
        return $pathSum;
    }

    private function calculateShortestPath(array $pair, $emptyXs, $emptyYs, $factor)
    {
        $g1 = $pair["from"]["galaxy"];
        $g2 = $pair["to"]["galaxy"];

        $xdiff = $this->getDiff($g1[0], $g2[0], $emptyXs, $factor);
        $ydiff = $this->getDiff($g1[1], $g2[1], $emptyYs, $factor);

        $r = $xdiff + $ydiff;
        $this->logger->debug($pair["from"]["name"] . " -> " . $pair["to"]["name"] . " = " . $r);

        return $r;
    }

    private function getEmptyRowYs()
    {
        $emptyYs = array();
        foreach ($this->universe as $y => $rows) {
            $isEmpty = true;
            foreach ($rows as $x => $cell) {
                if ($cell != ".") {
                    $isEmpty = false;
                    break;
                }
            }

            if ($isEmpty) {
                $emptyYs[] = $y;
            }
        }
        return $emptyYs;

    }

    private function getDiff(mixed $x1, mixed $x2, $emptyXs, $factor)
    {
        $min = min($x1, $x2);
        $max = max($x1, $x2);
        $diff = 0;

        for ($i = $min; $i < $max; $i++) {
            if (array_search($i, $emptyXs) === false) {
                $diff++;
            } else {
                $diff += $factor;
            }
        }

        return $diff;
    }
}