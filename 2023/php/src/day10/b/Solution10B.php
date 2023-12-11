<?php declare(strict_types=1);

namespace day10\b;

use Exception;
use Monolog\Logger;

final class Solution10B
{
    private Logger $logger;
    private array $inputMap;

    private array $solutionMap;

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution10B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        // parse input and find Startpoint
        $startpoint = array();
        foreach ($lines as $k => $line) {
            if (!trim($line))
                continue;
            for ($i = 0; $i < strlen($line); $i++) {
                $this->inputMap[$i][$k] = $line[$i];
                if ($line[$i] == 'S') {
                    $startpoint = array($i, $k);
                }
            }
        }

        // prepare empty solution Map
        for ($x = 0; $x < sizeof($this->inputMap); $x++) {
            for ($y = 0; $y < sizeof($this->inputMap[0]); $y++) {
                $this->solutionMap[$x][$y] = ".";
            }
        }

        // fill solution map -> especially remove redundant pipe parts
        $this->findNextStep($startpoint);

        // mark known outside points
        $this->markAnOutsidePointTouchingPipe();
        $this->markBorderPointsAsOutside();

        // do the real stuff
        $this->markRemainingPoints();

        $this->printMap($this->inputMap);
        $this->printMap($this->solutionMap);

        // count inside
        return $this->countInsidePoints();

    }

    private function findNextStep(array $p, array $last = null): int
    {
        $this->solutionMap[$p[0]][$p[1]] = $this->inputMap[$p[0]][$p[1]];

        $n = isset($this->inputMap[$p[0]][$p[1] - 1]) ? array($p[0], $p[1] - 1) : null;
        $s = isset($this->inputMap[$p[0]][$p[1] + 1]) ? array($p[0], $p[1] + 1) : null;
        $w = isset($this->inputMap[$p[0] - 1][$p[1]]) ? array($p[0] - 1, $p[1]) : null;
        $e = isset($this->inputMap[$p[0] + 1][$p[1]]) ? array($p[0] + 1, $p[1]) : null;

        $symbol = $this->inputMap[$p[0]][$p[1]];

        $m = $this->inputMap;

        if ($symbol == 'S') {

            // replace S with correct symbol
            if (($m[$e[0]][$e[1]] === '7' || $m[$e[0]][$e[1]] === 'J' || $m[$e[0]][$e[1]] === '-') &&
                ($m[$w[0]][$w[1]] === 'L' || $m[$w[0]][$w[1]] === 'F' || $m[$w[0]][$w[1]] === '-')) {
                $this->solutionMap[$p[0]][$p[1]] = '-';
                $a = array($p[0] + 1, $p[1]);
                $b = array($p[0] - 1, $p[1]);
            } elseif (($m[$n[0]][$n[1]] === '7' || $m[$n[0]][$n[1]] === 'F' || $m[$n[0]][$n[1]] === '|') &&
                ($m[$s[0]][$s[1]] === 'L' || $m[$s[0]][$s[1]] === 'J' || $m[$s[0]][$s[1]] === '|')) {
                $this->solutionMap[$p[0]][$p[1]] = '|';
                $a = array($p[0], $p[1] - 1);
                $b = array($p[0], $p[1] + 1);
            } elseif (($m[$e[0]][$e[1]] === '7' || $m[$e[0]][$e[1]] === 'J' || $m[$e[0]][$e[1]] === '-') &&
                ($m[$s[0]][$s[1]] === 'L' || $m[$s[0]][$s[1]] === 'J' || $m[$s[0]][$s[1]] === '|')) {
                $this->solutionMap[$p[0]][$p[1]] = 'F';
                $a = array($p[0] + 1, $p[1]);
                $b = array($p[0], $p[1] + 1);
            } elseif (($m[$e[0]][$e[1]] === '7' || $m[$e[0]][$e[1]] === 'J' || $m[$e[0]][$e[1]] === '-') &&
                ($m[$n[0]][$n[1]] === '7' || $m[$n[0]][$n[1]] === 'F' || $m[$n[0]][$n[1]] === '|')) {
                $this->solutionMap[$p[0]][$p[1]] = 'L';
                $a = array($p[0], $p[1] - 1);
                $b = array($p[0] + 1, $p[1]);
            } elseif (($m[$n[0]][$n[1]] === '7' || $m[$n[0]][$n[1]] === 'F' || $m[$n[0]][$n[1]] === '|') &&
                ($m[$w[0]][$w[1]] === 'L' || $m[$w[0]][$w[1]] === 'F' || $m[$w[0]][$w[1]] === '-')) {
                $this->solutionMap[$p[0]][$p[1]] = 'J';
                $a = array($p[0], $p[1] - 1);
                $b = array($p[0] - 1, $p[1]);
            } elseif (($m[$s[0]][$s[1]] === 'L' || $m[$s[0]][$s[1]] === 'J' || $m[$s[0]][$s[1]] === '|') &&
                ($m[$w[0]][$w[1]] === 'L' || $m[$w[0]][$w[1]] === 'F' || $m[$w[0]][$w[1]] === '-')) {
                $this->solutionMap[$p[0]][$p[1]] = '7';
                $a = array($p[0] - 1, $p[1]);
                $b = array($p[0], $p[1] + 1);
            } else {
                throw new Exception();
            }

            if ($last !== null) {
                return 0;
            }

        }

        switch ($symbol) {
            case "F":
                $a = $e;
                $b = $s;
                break;
            case "7":
                $a = $w;
                $b = $s;
                break;
            case "J":
                $a = $n;
                $b = $w;
                break;
            case "L":
                $a = $n;
                $b = $e;
                break;
            case "|":
                $a = $n;
                $b = $s;
                break;
            case "-":
                $a = $w;
                $b = $e;
                break;
        }

        if ($last !== null) {
            if ($a[0] === $last[0] && $a[1] === $last[1]) {
                $next = $b;
            } else if ($b[0] === $last[0] && $b[1] === $last[1]) {
                $next = $a;
            }
        } else {
            $next = $a;
        }

        if (!isset($next)) {
            throw new Exception();
        }

        return 1 + $this->findNextStep($next, $p);
    }


    private function markBorderPointsAsOutside()
    {
        for ($x = 0; $x < sizeof($this->solutionMap); $x++) {
            if ($this->solutionMap[$x][0] === ".") {
                $this->solutionMap[$x][0] = "O";
            }
            if ($this->solutionMap[$x][sizeof($this->solutionMap[0]) - 1] === ".") {
                $this->solutionMap[$x][sizeof($this->solutionMap[0]) - 1] = "O";
            }
        }

        for ($y = 0; $y < sizeof($this->solutionMap[0]); $y++) {
            if ($this->solutionMap[0][$y] === ".") {
                $this->solutionMap[0][$y] = "O";
            }
            if ($this->solutionMap[sizeof($this->solutionMap) - 1][$y] === ".") {
                $this->solutionMap[sizeof($this->solutionMap) - 1][$y] = "O";
            }
        }
    }

    private function markRemainingPoints()
    {
        foreach ($this->solutionMap as $x => $column) {
            foreach ($column as $y => $cell) {
                if ($cell === ".") {
                    $this->markRemainingPoint($x, $y);
                }
            }
        }
    }

    private function countInsidePoints()
    {
        $r = 0;
        foreach ($this->solutionMap as $x => $column) {
            foreach ($column as $y => $cell) {
                if ($cell === "I") {
                    $r++;
                }
            }
        }
        return $r;
    }

    private function markRemainingPoint(int $x, int $y)
    {
        $this->logger->debug("Start " . $x . ":" . $y );
        $checked = array(array($x, $y));
        $pipe = $this->findPipe($x, $y, $checked);

        if ($pipe !== null) {
            $r = $this->traversePipe(null, null, $pipe[0], $pipe[1], $pipe[0], $pipe[1], $checked);
        } else {
            $r = "O";
        }

        $this->logger->debug("----");

        foreach ($checked as $c) {
            $this->solutionMap[$c[0]][$c[1]] = $r;
        }
    }

    private function traversePipe(int|null $px, int|null $py, int $x, int $y, int $startx, int $starty, &$checked)
    {
        // We traverse always left or top

        $symbol = $this->solutionMap[$x][$y];
        $check = array();

        // Startpoint with no direction yet
        if ($px === null && $py === null) {
            switch ($symbol) {
                case "F":
                    $next = $this->right($x, $y);
                    break;
                case "L":
                    $next = $this->up($x, $y);
                    break;
                case "J":
                    $next = $this->up($x, $y);
                    break;
                case "7":
                    $next = $this->left($x, $y);
                    break;
                case "-":
                    $next = $this->right($x, $y);
                    break;
                case "|":
                    $next = $this->up($x, $y);
                    break;
                case "O":
                    $this->logger->debug("Traverse outside(2) -> O");
                    return "O";
                case "I":
                    $this->logger->debug("Traverse inside(2) -> I");
                    return "I";
                default:
                    throw new Exception();
            }

            // get next traversing step and traversed points
        } else {

            // We're back without hitting an O -> we are inside.
            if ($x === $startx && $y == $starty) {
                $this->logger->debug("Traverse loop " . $x . ":" . $y . " -> I");
                return "I";
            }

            // get direction from the entrance coordinate
            $d = $this->getDirection($px, $py, $x, $y);
            $this->logger->debug( "Traverse (" . $symbol . ")" . $d . " " . $px . ":" . $py . " -> " . $x . ":" . $y );

            switch ($symbol) {
                case "F":
                    if ($d == "l") {
                        $next = $this->down($x, $y);
                        // no check
                    } elseif ($d == "u") {
                        $next = $this->right($x, $y);
                        $check = array($this->left($x, $y), $this->up($x, $y), array($x - 1, $y - 1));
                    } else {
                        throw new Exception();
                    }
                    break;
                case "L":
                    if ($d == "d") {
                        $next = $this->right($x, $y);
                        // no check
                    } elseif ($d == "l") {
                        $next = $this->up($x, $y);
                        $check = array($this->left($x, $y), $this->down($x, $y), array($x - 1, $y + 1));
                    } else {
                        throw new Exception();
                    }
                    break;
                case "7":
                    if ($d == "r") {
                        $next = $this->down($x, $y);
                        $check = array($this->right($x, $y), $this->up($x, $y), array($x + 1, $y - 1));
                    } elseif ($d == "u") {
                        $next = $this->left($x, $y);
                        // no check
                    } else {
                        throw new Exception();
                    }
                    break;
                case "J":
                    if ($d == "d") {
                        $next = $this->left($x, $y);
                        $check = array($this->right($x, $y), $this->down($x, $y), array($x + 1, $y + 1));
                    } elseif ($d == "r") {
                        $next = $this->up($x, $y);
                        // no check
                    } else {
                        throw new Exception();
                    }
                    break;
                case "-":
                    if ($d == "r") {
                        $next = $this->right($x, $y);
                        $check = array($this->up($x, $y));
                    } elseif ($d == "l") {
                        $next = $this->left($x, $y);
                        $check = array($this->down($x, $y));
                    } else {
                        throw new Exception();
                    }
                    break;
                case "|":
                    if ($d == "d") {
                        $next = $this->down($x, $y);
                        $check = array($this->right($x, $y));
                    } elseif ($d == "u") {
                        $next = $this->up($x, $y);
                        $check = array($this->left($x, $y));
                    } else {
                        throw new Exception();
                    }
                    break;
                case "O":
                    $this->logger->debug( "Traverse outside(2) -> O");
                    return "O";
                case "I":
                    $this->logger->debug( "Traverse inside(2) -> I");
                    return "I";
                default:
                    throw new Exception();
            }
        }

        // add new points to the lot of traversed ones
        foreach ($check as $c) {
            if (isset($this->solutionMap[$c[0]][$c[1]]) && $this->solutionMap[$c[0]][$c[1]] === ".")
                $checked[] = $c;
        }

        // check if we hit an already decided point
        foreach ($check as $c) {
            if (isset($this->solutionMap[$c[0]][$c[1]]) &&
                $this->solutionMap[$c[0]][$c[1]] === "O") {
                $this->logger->debug( "Traverse outside -> O");
                return "O";
            }
            if (isset($this->solutionMap[$c[0]][$c[1]]) &&
                $this->solutionMap[$c[0]][$c[1]] === "I") {
                $this->logger->debug( "Traverse inside -> I");
                return "I";
            }
        }

        // go on
        return $this->traversePipe($x, $y, $next[0], $next[1], $startx, $starty, $checked);
    }

    private function markAnOutsidePointTouchingPipe()
    {
        foreach ($this->solutionMap as $x => $column) {
            foreach ($column as $y => $cell) {
                if ($cell) {
                    $this->solutionMap[$x][$y] = "O";
                    return;
                }
            }
        }

        throw new Exception();
    }

    private function findPipe(int $x, int $y, &$checked)
    {

        $myx = $x;
        while (isset($this->solutionMap[$myx][$y]) && $this->solutionMap[$myx][$y] === ".") {
            $checked[] = array($myx, $y);
            $myx++;
        }

        if (!isset($this->solutionMap[$myx][$y])) {
            return null;
        }
        if ($this->solutionMap[$myx][$y] !== ".") {
            $this->logger->debug( "Found pipe at " . $myx . ":" . $y );
            return array($myx, $y);
        }

        return $this->findPipe($x, $y + 1, $checked);

    }

    private function up($x, $y)
    {
        return array($x, $y - 1);
    }

    private function down($x, $y)
    {
        return array($x, $y + 1);
    }

    private function left($x, $y)
    {
        return array($x - 1, $y);
    }

    private function right($x, $y)
    {
        return array($x + 1, $y);
    }

    private function getDirection(?int $px, ?int $py, int $x, int $y)
    {
        if ($px == $x && $py + 1 == $y) {
            return "d";
        }
        if ($px == $x && $py - 1 == $y) {
            return "u";
        }
        if ($px + 1 == $x && $py == $y) {
            return "r";
        }
        if ($px - 1 == $x && $py == $y) {
            return "l";
        }
        throw new Exception();
    }

    private function printMap(array $m)
    {
        $m2 = array();
        foreach ($m as $x => $column) {
            foreach ($column as $y => $cell) {
                $m2[$y][$x] = $cell;
            }
        }

        foreach ($m2 as $row) {
            $line = "";
            foreach ($row as $cell) {
                $line .= $cell;
            }
            $this->logger->debug($line);
        }
    }

}