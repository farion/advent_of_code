<?php declare(strict_types=1);

namespace day23\a;

use Monolog\Logger;

final class Solution23A
{
    private array $map;
    private array $end;
    private array $DIR_MAP = [
        0 => [0, 1, "v"],
        1 => [-1, 0, "<"],
        2 => [0, -1, "^"],
        3 => [1, 0, ">"]
    ];

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution23A())->getNonStaticResult($inputFile, $logger);
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
                $this->map[$x][$y] = $line[$x];
            }
        }

        $start = [1, 0];
        $this->end = [sizeof($this->map) - 2, sizeof($this->map[0]) - 1];
        return max($this->getNextNode($start, 0, 0));
    }

    private function getNextNode(array $p, int $d, int $l)
    {
        if ($p[0] == $this->end[0] && $p[1] == $this->end[1]) {
            return [$l];
        }
        
        $outputs = $this->getOutputs($p);

        $r = [];
        for ($nd = 0; $nd < 4; $nd++) {
            if ($d == ($nd + 2) % 4 || !isset($outputs[$nd])) continue;
            $r = array_merge($this->getNextNode($outputs[$nd], $nd, $l + 1), $r);
        }
        return $r;
    }

    private function getOutputs(array $p)
    {
        $outputs = [];
        for ($i = 0; $i < 4; $i++) {
            $x = $p[0] + $this->DIR_MAP[$i][0];
            $y = $p[1] + $this->DIR_MAP[$i][1];
            $d = $this->DIR_MAP[$i][2];
            if (isset($this->map[$x][$y]) && ($this->map[$x][$y] == "." || $this->map[$x][$y] == $d))
                $outputs[$i] = [$x, $y];
        }
        return $outputs;
    }
}