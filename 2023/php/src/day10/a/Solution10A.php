<?php declare(strict_types=1);

namespace day10\a;

use Exception;
use Monolog\Logger;

final class Solution10A
{
    private Logger $logger;
    private array $map;

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution10a())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $startpoint = array();
        foreach ($lines as $k => $line) {
            if (!trim($line))
                continue;
            for ($i = 0; $i < strlen($line); $i++) {
                $this->map[$i][$k] = $line[$i];
                if ($line[$i] == 'S') {
                    $startpoint = array($i, $k);
                }
            }
        }

        return $this->findNextStep($startpoint) / 2;
    }

    private function findNextStep(array $p, array $last = null): int
    {

        $n = isset($this->map[$p[0]][$p[1] - 1]) ? array($p[0], $p[1] - 1) : null;
        $s = isset($this->map[$p[0]][$p[1] + 1]) ? array($p[0], $p[1] + 1) : null;
        $w = isset($this->map[$p[0] - 1][$p[1]]) ? array($p[0] - 1, $p[1]) : null;
        $e = isset($this->map[$p[0] + 1][$p[1]]) ? array($p[0] + 1, $p[1]) : null;

        $symbol = $this->map[$p[0]][$p[1]];

        if ($symbol == 'S') {

            if ($last !== null) {
                return 0;
            }

            foreach (array($n, $s, $w, $e) as $r) {
                if ($r !== null && $this->map[$r[0]][$r[1]] !== '.') {
                    if (!isset($a)) $a = $r;
                    else $b = $r;
                }
            }
        }

        if ($symbol == 'F') {
            $a = $e;
            $b = $s;
        }

        if ($symbol == '7') {
            $a = $w;
            $b = $s;
        }

        if ($symbol == 'J') {
            $a = $n;
            $b = $w;
        }

        if ($symbol == 'L') {
            $a = $n;
            $b = $e;
        }

        if ($symbol == '|') {
            $a = $n;
            $b = $s;
        }

        if ($symbol == '-') {
            $a = $w;
            $b = $e;
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

        $this->logger->debug($p[0] . ":" . $p[1] . " -> " . $next[0] . ":" . $next[1] . " = " . ($this->map[$next[0]][$next[1]]));

        return 1 + $this->findNextStep($next, $p);
    }

}