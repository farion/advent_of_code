<?php declare(strict_types=1);

namespace day18\lib;

class Solver
{
    private static array $MAPPING = [
        "R" => [1, 0],
        "L" => [-1, 0],
        "U" => [0, -1],
        "D" => [0, 1]
    ];

    public function getResult(array $pipe): int
    {
        $points = [];
        $points[] = [0, 0];
        $x = 0;
        $y = 0;
        $edges = 0;
        foreach ($pipe as $p) {
            $x += self::$MAPPING[$p[0]][0] * $p[1];
            $y += self::$MAPPING[$p[0]][1] * $p[1];
            $edges += $p[1];
            $points[] = [$x, $y];
        }
        $points[] = [0, 0];

        $sum1 = 0;
        $sum2 = 0;
        foreach ($points as $i => $point) {
            if (isset($points[$i + 1])) {
                $sum1 += ($point[0] * $points[$i + 1][1]);
                $sum2 += ($point[1] * $points[$i + 1][0]);
            }
        }

        return abs(($sum1 - $sum2) / 2) + ($edges / 2) + 1;
    }
}