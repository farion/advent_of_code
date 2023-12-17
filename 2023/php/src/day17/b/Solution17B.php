<?php declare(strict_types=1);

namespace day17\b;

use Monolog\Logger;

final class Solution17B
{

    /*
     * too high 1443
     * too high 1448
     */

    private int $currentMin;

    private array $history = array();

    private static array $MOVES = [
        ">" => [
            [[0, 1], "v"],
            [[0, -1], "^"],
            [[1, 0], ">"]
        ],
        "<" => [
            [[0, 1], "v"],
            [[0, -1], "^"],
            [[-1, 0], "<"]
        ],
        "v" => [
            [[-1, 0], "<"],
            [[1, 0], ">"],
            [[0, 1], "v"]
        ],
        "^" => [
            [[-1, 0], "<"],
            [[1, 0], ">"],
            [[0, -1], "^"]
        ]
    ];
    private int $maxX;
    private int $maxY;


    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution17B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        foreach ($lines as $y => $line) {
            if (!trim($line))
                continue;
            for ($x = 0; $x < strlen($line); $x++) {
                $this->grid[$x][$y] = $line[$x];
            }
        }

        $this->maxX = sizeof($this->grid) - 1;
        $this->maxY = sizeOf($this->grid[0]) - 1;

        /*
        echo $this->maxX."\n";
        echo $this->maxY."\n";
        exit();*/

        $this->findPath(0, 0, ">", 0, 0, "");
        $this->findPath(0, 0, "v", 0, 0, "");

        print_r($this->history);

        return $this->currentMin;
    }

    private function findPath(int $x, int $y, string $d, int $straightMoves, int $loss, $h)
    {
        $nextSteps = array();

        foreach (self::$MOVES[$d] as $move) {
            if ($straightMoves > 10)
                continue; //skip same direction after three moves in the same direction

            if($straightMoves < 4 && $move[1] !== $d)
                continue;

            $newX = $x + $move[0][0];
            $newY = $y + $move[0][1];
            if (isset($this->grid[$newX][$newY])) {
                $straightMovesNew = $move[1] === $d ? ($straightMoves + 1) : 1;
                $newLoss = $loss + $this->grid[$newX][$newY];
                if ((isset($this->currentMin) &&
                        $this->currentMin <= $newLoss) ||
                    (isset($this->gridMinLoad[$newX][$newY][$move[1]][$straightMovesNew]) &&
                        $this->gridMinLoad[$newX][$newY][$move[1]][$straightMovesNew] <= $newLoss)) {
                    continue;
                }

                // echo $x . ":" . $y . " " . $d . " " . $straightMoves . " = " . $loss . " => " . $newX . ":" . $newY . " " . $move[1] . " " . $straightMovesNew . " = " . $newLoss . "\n";
                $this->gridMinLoad[$newX][$newY][$move[1]][$straightMovesNew] = $newLoss;
                if ($newX != $this->maxX || $newY != $this->maxY) {
                    $nextSteps[] = array($newX, $newY, $move[1], $straightMovesNew, $newLoss, $h . $move[1]);
                } else {
                    if($straightMovesNew >= 4) {
                        $this->currentMin = isset($this->currentMin) ? min($this->currentMin, $newLoss) : $newLoss;
                        echo "Current Min = " . $this->currentMin . "\n";
                        $this->history[] = $h . $move[1] . " = " . $newLoss;
                    }
                }
            }
        }

        usort($nextSteps, function ($a, $b) {
            if($a[0]+$a[1] < $b[0]+$b[1]){
                return 1;
            }elseif($a[0]+$a[1] > $b[0]+$b[1]){
                return -1;
            }else{
                return $a[4] > $b[4];
            }
        });


        foreach ($nextSteps as $step) {
            $this->findPath($step[0], $step[1], $step[2], $step[3], $step[4], $step[5]);
        }

    }

}