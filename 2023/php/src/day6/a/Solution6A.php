<?php declare(strict_types=1);

namespace day6\a;
use Monolog\Logger;

final class Solution6A
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        preg_match_all("/[0-9]+/",$lines[0],$timeMatches);
        preg_match_all("/[0-9]+/",$lines[1],$distanceMatches);

        $timeMatches = $timeMatches[0];
        $distanceMatches = $distanceMatches[0];

        $gameCount = array();

        for($g = 0; $g < sizeof($timeMatches); $g++){
            $gameCount[$g] = 0;
            for($pt = 0; $pt < intval($timeMatches[$g]); $pt++){
                $distance = $pt * (intval($timeMatches[$g])-$pt);
                if($distance > $distanceMatches[$g]){
                    $gameCount[$g]++;
                }
            }
        }

        $result = 1;
        foreach($gameCount as $g){
            $result *= $g;
        }

        return $result;
    }
}