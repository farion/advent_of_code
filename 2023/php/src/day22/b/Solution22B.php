<?php declare(strict_types=1);

namespace day22\b;

use Monolog\Logger;

final class Solution22B
{
    private array $bricks = [];
    private array $space = [];

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution22B())->getNonStaticResult($inputFile, $logger);
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
            preg_match("/^([0-9]+),([0-9]+),([0-9]+)~([0-9]+),([0-9]+),([0-9]+)$/", $line, $matches);
            $this->bricks[$y] = [
                "name" => $y,
                "char" => chr($y + 65),
                "a" => [$matches[1], $matches[2], $matches[3]],
                "b" => [$matches[4], $matches[5], $matches[6]]];
        }

        foreach ($this->bricks as $i => $b) {
            for ($x = min($b["a"][0], $b["b"][0]); $x <= max($b["a"][0], $b["b"][0]); $x++) {
                for ($y = min($b["a"][1], $b["b"][1]); $y <= max($b["a"][1], $b["b"][1]); $y++) {
                    for ($z = min($b["a"][2], $b["b"][2]); $z <= max($b["a"][2], $b["b"][2]); $z++) {
                        $this->space[$x][$y][$z] = $b["name"];
                    }
                }
            }
        }

        do {
            $fallen = false;
            foreach ($this->bricks as $b) {
                $f = $this->canFall($b, $this->space);
                if ($f > 0) {
                    $this->fall($b, $f, $this->space, $this->bricks);
                    $fallen = true;
                }

            }
        } while ($fallen);

        $falls = 0;
        foreach ($this->bricks as $b) {
            $myspace = $this->space;
            $mybricks = $this->bricks;

            $falling = [];

            do {
                $fallen = false;
                foreach ($mybricks as $cand) {
                    $f = $this->canFall($cand, $myspace, $b);
                    if ($f > 0) {
                        $this->fall($cand, $f, $myspace, $mybricks);
                        $fallen = true;
                        $falling[$cand["name"]] = true;
                    }
                }
            } while ($fallen);
            $falls += sizeof($falling);
        }

        return $falls;
    }

    private function canFall(mixed $b, &$space, $ignore = null): int
    {
        $i = 0;
        while (true) {
            $i++;
            for ($x = min($b["a"][0], $b["b"][0]); $x <= max($b["a"][0], $b["b"][0]); $x++) {
                for ($y = min($b["a"][1], $b["b"][1]); $y <= max($b["a"][1], $b["b"][1]); $y++) {
                    for ($z = min($b["a"][2], $b["b"][2]); $z <= max($b["a"][2], $b["b"][2]); $z++) {
                        if ($z - $i >= 1 &&
                            (!isset($space[$x][$y][$z - $i]) ||
                                $space[$x][$y][$z - $i] == $b["name"] ||
                                ($ignore != null && $space[$x][$y][$z - $i] == $ignore["name"]))) {
                            continue;
                        }
                        return $i - 1;
                    }
                }
            }
        }
    }

    private function fall(mixed $b, $i, &$space, &$bricks)
    {
        for ($x = min($b["a"][0], $b["b"][0]); $x <= max($b["a"][0], $b["b"][0]); $x++) {
            for ($y = min($b["a"][1], $b["b"][1]); $y <= max($b["a"][1], $b["b"][1]); $y++) {
                for ($z = min($b["a"][2], $b["b"][2]); $z <= max($b["a"][2], $b["b"][2]); $z++) {
                    unset($space[$x][$y][$z]);
                    $space[$x][$y][$z - $i] = $b["name"];
                }
            }
        }
        $bricks[$b["name"]]["a"][2] -= $i;
        $bricks[$b["name"]]["b"][2] -= $i;
    }

}
