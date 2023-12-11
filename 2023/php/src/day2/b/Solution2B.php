<?php declare(strict_types=1);

namespace day2\b;

use Monolog\Logger;

final class Solution2B
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;
        foreach ($lines as $line) {
            $game = explode(":", $line);
            $sets = explode(";", $game[1]);
            $mins = array();
            foreach ($sets as $set) {
                $cubes = explode(",", trim($set));
                foreach ($cubes as $cube) {
                    $cubeData = explode(" ", trim($cube));
                    $cubeCount = intval($cubeData[0]);
                    $cubeColor = $cubeData[1];
                    if(isset($mins[$cubeColor])) {
                        if($mins[$cubeColor] < $cubeCount){
                            $mins[$cubeColor] = $cubeCount;
                        }
                    }else{
                        $mins[$cubeColor] = $cubeCount;
                    }
                }
            }
            $min_result = 1;
            foreach($mins as $min){
                $min_result *= $min;
            }
            $result += $min_result;
        }
        return $result;
    }
}