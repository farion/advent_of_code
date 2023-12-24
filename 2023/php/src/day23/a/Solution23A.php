<?php declare(strict_types=1);

namespace day23\a;

use Monolog\Logger;

final class Solution23A
{

    private array $bricks = [];
    private array $space = [];

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
                $f = $this->canFall($b);
                if ($f > 0) {
                    $this->fall($b, $f);
                    $fallen = true;
                }

            }
        } while ($fallen);

        $disintegragted = 0;
        foreach ($this->bricks as $b) {
            $isrequired = false;
            foreach ($this->bricks as $cand) {
                if ($cand["name"] == $b["name"]) continue;
                if ($this->canFall($cand, $b) > 0) $isrequired = true;
            }
            if (!$isrequired) $disintegragted++;
        }

        return $disintegragted;
    }

    private function canFall(mixed $b, $ignore = null): int
    {
        $i = 0;
        while (true) {
            $i++;
            for ($x = min($b["a"][0], $b["b"][0]); $x <= max($b["a"][0], $b["b"][0]); $x++) {
                for ($y = min($b["a"][1], $b["b"][1]); $y <= max($b["a"][1], $b["b"][1]); $y++) {
                    for ($z = min($b["a"][2], $b["b"][2]); $z <= max($b["a"][2], $b["b"][2]); $z++) {
                        if ($z - $i >= 1 &&
                            (!isset($this->space[$x][$y][$z - $i]) ||
                                $this->space[$x][$y][$z - $i] == $b["name"] ||
                                ($ignore != null && $this->space[$x][$y][$z - $i] == $ignore["name"]))) {
                            continue;
                        }
                        return $i - 1;
                    }
                }
            }
        }
    }

    private function fall(mixed $b, $i)
    {
        for ($x = min($b["a"][0], $b["b"][0]); $x <= max($b["a"][0], $b["b"][0]); $x++) {
            for ($y = min($b["a"][1], $b["b"][1]); $y <= max($b["a"][1], $b["b"][1]); $y++) {
                for ($z = min($b["a"][2], $b["b"][2]); $z <= max($b["a"][2], $b["b"][2]); $z++) {
                    unset($this->space[$x][$y][$z]);
                    $this->space[$x][$y][$z - $i] = $b["name"];
                }
            }
        }
        $this->bricks[$b["name"]]["a"][2] -= $i;
        $this->bricks[$b["name"]]["b"][2] -= $i;
    }

}