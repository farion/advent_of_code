<?php declare(strict_types=1);

namespace day21\a;

use MathPHP\NumericalAnalysis\Interpolation\LagrangePolynomial;
use Monolog\Logger;
use RuntimeException;

final class Solution21A
{

    private int $t = 0;

    private Logger $logger;

    private array $grid = [];

    private array $steps = [];

    private array $cache = [];

    private array $biggrid = [];

    private int $minX = 0;
    private int $maxX = 0;

    private int $minY = 0;
    private int $maxY = 0;


    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution21A())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $this->egrid = [];

        for ($y = 0; $y < sizeof($lines); $y++) {
            $line = $lines[$y];
            if (!trim($line))
                break;
            for ($x = 0; $x < strlen($line); $x++) {
                $this->grid[$x][$y] = $line[$x];
                $this->egrid[$x][$y] = ".";
                if ($line[$x] == "S") {
                    $this->startX = $x;
                    $this->startY = $y;
                }
            }
        }

        $this->height = sizeof($this->grid[0]);
        $this->width = sizeof($this->grid);

        echo "Height: " . $this->height . "\n";
        echo "Width: " . $this->width . "\n";

        $half = (int)ceil($this->width / 2);

        /*
        $a = (int)ceil($this->width / 2);
        // echo $a . "=" . $this->runTurn($a) . "\n";
        $b = (int)ceil($this->width / 2) + $this->width;
        // echo $b . "=" . $this->runTurn($b) . "\n";
        $c = (int)ceil($this->width / 2) + ($this->width * 2);
        $d = (int)ceil($this->width / 2) + ($this->width * 3);

        $e = (int)ceil($this->width / 2) + ($this->width * 4);
        //  echo $c . "=" . $this->runTurn($c) . "\n";
        // echo "10=" . $this->runTurn(10) . "\n";
        // echo "50=" . $this->runTurn(50) . "\n";

        $points = [[$a, $this->runTurn($a)], [$b, $this->runTurn($b)], [$c, $this->runTurn($c)], [$d, $this->runTurn($d)], [$e, $this->runTurn($e)]];

        print_r($points);

        $f = function ($x) {
            return $x**2;
        };

        $p = LagrangePolynomial::interpolate($points);


        //  echo (26501365 - (26501365 % 78)) / 78;

        // expands = 3397610


        echo sprintf('%.10f', $p(100));
        //echo sprintf('%.10f',$p(26501365));
        echo "\n";
        */

        /*
        echo $this->runTurn($half)."\n";
        echo $this->runTurn($this->width)."\n";
        echo $this->runTurn($this->width * 2)."\n";
        echo $this->runTurn($this->width * 3)."\n";

        $this->printSituation();*/

        $runs = 26501365; //26501365;

        $c = (int)ceil($this->width / 2);
        $f = (int)floor($this->width / 2);

        $a = pow($c + 1, 2) - $this->runTurn($c);
        $b = $this->runTurn($this->width, $this->egrid) - $this->runTurn($this->width) - $a;
        $a2 = pow($f + 1, 2) - $this->runTurn($f);
        $b2 = $this->runTurn($this->width - 1, $this->egrid) - $this->runTurn($this->width - 1) - $a2;

        $d = pow(($runs * 2 + 1) / $this->width,2);
        $hd = ($d / 2) / 2;

        $d1 = pow(202300, 2);
        $d2 = pow(202301, 2);
        $r = (pow($runs + 1, 2)) - ($hd * $b + $hd * $b2 + $d1 * $b2 + $d2 * $b);


        //echo 26501365 / $this->width;

        echo sprintf("%.10f",$r) . "\n";


        return 0;
    }

    private function nextTurn($i)
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

    private function getNextStep($x, $y, $i)
    {

        $steps = [];

        /*
                if (!isset($this->biggrid[$x][$y - 1])) {
                    $this->expandTop();
                }

                if (!isset($this->biggrid[$x][$y + 1])) {
                    $this->expandBottom();
                }

                if (!isset($this->biggrid[$x - 1][$y])) {
                    $this->expandLeft();
                }

                if (!isset($this->biggrid[$x + 1][$y])) {
                    $this->expandRight();
                }
        */

        if (isset($this->biggrid[$x][$y - 1]) && $this->biggrid[$x][$y - 1] != "#")
            $steps[] = [$x, $y - 1];

        if (isset($this->biggrid[$x][$y + 1]) && $this->biggrid[$x][$y + 1] != "#")
            $steps[] = [$x, $y + 1];

        if (isset($this->biggrid[$x - 1][$y]) && $this->biggrid[$x - 1][$y] != "#")
            $steps[] = [$x - 1, $y];

        if (isset($this->biggrid[$x + 1][$y]) && $this->biggrid[$x + 1][$y] != "#")
            $steps[] = [$x + 1, $y];

        return $steps;

    }

    private function printSituation()
    {
        for ($y = $this->minY; $y <= $this->maxY; $y++) {
            for ($x = $this->minX; $x <= $this->maxX; $x++) {
                if (isset($this->steps[$x][$y]))
                    echo "O";
                else
                    echo $this->biggrid[$x][$y];
            }
            echo "\n";
        }
        echo "\n";

    }

    private function getCount(): int
    {
        $c = 0;
        foreach ($this->steps as $x => $stepCol) {
            foreach ($stepCol as $y => $stepCell) {
                $c++;
            }
        }
        return $c;
    }

    private function expandLeft()
    {
        $xg = 0;
        $yg = 0;
        for ($x = $this->minX - $this->width; $x < $this->minX; $x++) {
            $xg %= $this->width;
            for ($y = $this->minY; $y <= $this->maxY; $y++) {
                $yg %= $this->height;
                $this->biggrid[$x][$y] = $this->grid[$xg][$yg];
                $yg++;
            }
            $xg++;
        }
        $this->minX -= $this->width;

    }

    private function expandRight()
    {
        $xg = 0;
        $yg = 0;
        for ($x = $this->maxX + 1; $x <= $this->maxX + $this->width; $x++) {
            $xg %= $this->width;
            for ($y = $this->minY; $y <= $this->maxY; $y++) {
                $yg %= $this->height;
                $this->biggrid[$x][$y] = $this->grid[$xg][$yg];
                $yg++;
            }
            $xg++;
        }
        $this->maxX += $this->width;
    }

    private function expandTop()
    {
        $xg = 0;
        $yg = 0;
        for ($x = $this->minX; $x <= $this->maxX; $x++) {
            $xg %= $this->width;
            for ($y = $this->minY - $this->height; $y < $this->minY; $y++) {
                $yg %= $this->height;
                $this->biggrid[$x][$y] = $this->grid[$xg][$yg];
                $yg++;
            }
            $xg++;
        }
        $this->minY -= $this->height;
    }

    private function expandBottom()
    {
        $xg = 0;
        $yg = 0;
        for ($x = $this->minX; $x <= $this->maxX; $x++) {
            $xg %= $this->width;
            for ($y = $this->maxY + 1; $y <= $this->maxY + $this->height; $y++) {
                $yg %= $this->height;
                $this->biggrid[$x][$y] = $this->grid[$xg][$yg];
                $yg++;
            }
            $xg++;
        }
        $this->maxY += $this->height;

    }

    private function runTurn(int $rounds, $g = null)
    {
        $this->biggrid = $g ?: $this->grid;
        $this->steps = [];
        $this->steps[$this->startX][$this->startY] = true;
        $this->minX = 0;
        $this->minY = 0;
        $this->maxX = sizeof($this->grid) - 1;
        $this->maxY = sizeof($this->grid[0]) - 1;

        for ($i = 0; $i < $rounds; $i++) {
            $this->steps = $this->nextTurn($i);
        }

        return $this->getCount();
    }
}

/**
 * too low 7748
 * too low 7757
 */