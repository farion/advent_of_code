<?php declare(strict_types=1);

namespace day24\a;

use Monolog\Logger;

final class Solution24A
{
    private array $hails = [];

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution24A())->getNonStaticResult($inputFile, $logger);
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
            preg_match("/^ *(-?[0-9]+), +(-?[0-9]+), +(-?[0-9]+) +@ +(-?[0-9]+), +(-?[0-9]+), +(-?[0-9]+) *$/", $line, $matches);
            $this->hails[] = [intval($matches[1]), intval($matches[2]), intval($matches[3]), intval($matches[4]), intval($matches[5]), intval($matches[6])];
        }

        $r = 0;
        $min = 200000000000000;
        $max = 400000000000000;

        foreach ($this->hails as $i1 => $l1) {
            foreach ($this->hails as $i2 => $l2) {

                if ($i1 > $i2)
                    continue;

                $xa = $l1[0];
                $ya = $l1[1];
                $vax = $l1[3];
                $vay = $l1[4];
                $xb = $l2[0];
                $yb = $l2[1];
                $vbx = $l2[3];
                $vby = $l2[4];

                $teiler = $vby * $vax - $vbx * $vay;
                if ($teiler != 0) {
                    $a = ($vbx * ($ya - $yb) - $vby * ($xa - $xb)) / $teiler;
                    $b = ($vax * ($ya - $yb) - $vay * ($xa - $xb)) / $teiler;
                    $x = $xa + $a * $vax;
                    $y = $ya + $a * $vay;
                    $r += ($a > 0 && $b > 0 && $x >= $min && $x <= $max && $y >= $min && $y <= $max) ? 1 : 0;
                }
            }
        }

        return $r;
    }
}