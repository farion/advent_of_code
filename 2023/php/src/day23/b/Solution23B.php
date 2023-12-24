<?php declare(strict_types=1);

namespace day23\b;

use Monolog\Logger;

final class Solution23B
{
    private array $DIR_MAP = [
        0 => [0, 1, "v"],
        1 => [-1, 0, "<"],
        2 => [0, -1, "^"],
        3 => [1, 0, ">"]
    ];
    private array $map;
    private array $end;
    private int $max = 0;
    private array $edges = [];
    private array $start;

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution23B())->getNonStaticResult($inputFile, $logger);
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

        $this->start = [1, 0];
        $this->end = [sizeof($this->map) - 2, sizeof($this->map[0]) - 1];
        $this->findEdges(null, $this->start, null, 0);
        $this->addInvertedEdges();
        $this->findLongestPath($this->start, []);
        return $this->max;
    }

    private function findEdges(array|null $lastNode, array $next, array|null $prev, $l): void
    {
        $outputs = $this->getOutputs($next);
        $isNode = $this->isNode($next, $outputs);

        $l++;
        if ($isNode && $lastNode != null) {
            if (isset($this->edges[$lastNode[0]][$lastNode[1]][$next[0]][$next[1]])) return;
            $this->edges[$lastNode[0]][$lastNode[1]][$next[0]][$next[1]] = $l;
            $lastNode = $next;
            $l = 0;
        }

        foreach ($outputs as $o) {
            if ($prev != null && $o[0] == $prev[0] && $o[1] == $prev[1]) continue;
            $this->findEdges($isNode && $lastNode == null ? $next : $lastNode, $o, $next, $l);
        }
    }

    private function findLongestPath(array $p, array $h, int $l = 0): void
    {
        $h[$p[0]][$p[1]] = true;

        if ($p[0] == $this->end[0] && $p[1] == $this->end[1]) {
            $this->max = max($l, $this->max);
            return;
        }

        foreach ($this->edges[$p[0]][$p[1]] as $x => $routes)
            foreach ($routes as $y => $rl)
                if (!isset($h[$x][$y]))
                    $this->findLongestPath([$x, $y], $h, $l + $rl);
    }

    private function getOutputs(array $p): array
    {
        $outputs = [];
        for ($i = 0; $i < 4; $i++) {
            $x = $p[0] + $this->DIR_MAP[$i][0];
            $y = $p[1] + $this->DIR_MAP[$i][1];
            if (isset($this->map[$x][$y]) && ($this->map[$x][$y] != "#"))
                $outputs[$i] = [$x, $y];
        }
        return $outputs;
    }

    private function addInvertedEdges(): void
    {
        foreach ($this->edges as $x1 => $a)
            foreach ($a as $y1 => $b)
                foreach ($b as $x2 => $c)
                    foreach ($c as $y2 => $route)
                        $this->edges[$x2][$y2][$x1][$y1] = $route;
    }

    private function isNode(array $p, array $os): bool
    {
        return ($p[0] == $this->start[0] && $p[1] == $this->start[1]) ||
            ($p[0] == $this->end[0] && $p[1] == $this->end[1]) ||
            sizeof($os) != 2;
    }
}