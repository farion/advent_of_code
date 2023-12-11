<?php declare(strict_types=1);

namespace day2\a;

use Monolog\Logger;

final class Solution2A
{
    private static $CONTENT = array(
        "red" => 12,
        "green" => 13,
        "blue" => 14
    );

    public static function getResult(string $inputFile, Logger $logger): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;
        foreach ($lines as $line) {
            $game = explode(":", $line);
            preg_match("([0-9]+)", $game[0], $gameMatches);
            $gameNumber = intval($gameMatches[0]);
            $sets = explode(";", $game[1]);
            $gameInvalid = false;
            foreach ($sets as $set) {
                $cubes = explode(",", trim($set));
                foreach ($cubes as $cube) {
                    $cubeData = explode(" ", trim($cube));
                    if (Solution2A::$CONTENT[$cubeData[1]] < intval($cubeData[0])) {
                        $gameInvalid = true;
                        break;
                    }
                }
                if ($gameInvalid) break;
            }
            $result += !$gameInvalid?$gameNumber:0;
        }
        return $result;
    }
}