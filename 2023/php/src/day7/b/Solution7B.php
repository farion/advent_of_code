<?php declare(strict_types=1);

namespace day7\b;

use Monolog\Logger;
use PHPUnit\Logging\Exception;

final class Solution7B
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $cards = array();

        foreach ($lines as $line) {
            preg_match_all("/([A-Z0-9]+) ([0-9]+)/", $line, $card);
            $cards[] = array("hand" => $card[1][0], "bid" => $card[2][0]);
        }

        usort($cards, function ($a, $b) {
            return self::cmp_cards($a, $b);
        });

        $result = 0;
        for ($i = 0; $i < sizeof($cards); $i++) {
            $result += $cards[$i]["bid"] * ($i + 1);
        }
        return $result;
    }

    private static function cmp_cards($c1, $c2)
    {
        $v1 = self::getCardValue($c1);
        $v2 = self::getCardValue($c2);

        if ($v1 < $v2)
            return -1;

        if ($v1 > $v2)
            return 1;

        $h1 = preg_replace(array("/A/", "/K/", "/Q/", "/J/", "/T/"), array("E", "D", "C", "1", "A"), $c1["hand"]);
        $h2 = preg_replace(array("/A/", "/K/", "/Q/", "/J/", "/T/"), array("E", "D", "C", "1", "A"), $c2["hand"]);

        for ($i = 0; $i < 5; $i++) {
            if ($h1[$i] != $h2[$i]) {
                return $h1[$i] > $h2[$i];
            }
        }

        return 0;
    }

    private static function getCardValue($c)
    {
        preg_match_all("/[A-Z0-9]/", $c["hand"], $handParts);
        $count = array();
        foreach ($handParts[0] as $handPart) {
            if (isset($count[$handPart])) {
                $count[$handPart]++;
            } else {
                $count[$handPart] = 1;
            }
        }
        switch (sizeof($count)) {
            case 1: //5
                return 5; // five
            case 2:
                if (in_array(4, $count)) { // 4:1
                    if (isset($count["J"]) && $count["J"] == 1)
                        return 5;
                    if(isset($count["J"]) && $count["J"] == 4)
                        return 5;
                    return 4; // four
                } else { // 3:2
                    if (isset($count["J"]) && $count["J"] == 2) // => 5:1
                        return 5;
                    if (isset($count["J"]) && $count["J"] == 3) // => 5:1
                        return 5;
                    return 3.5; // full house
                }
            case 3:
                if (in_array(3, $count)) { // 3:1:1
                    if (isset($count["J"]) && $count["J"] == 1) // => 4:1
                        return 4;
                    if (isset($count["J"]) && $count["J"] == 3) // => 4:1
                        return 4;
                    return 3; //three
                } else { // 2:2:1
                    if (isset($count["J"]) && $count["J"] == 2)
                        return 4;
                    if (isset($count["J"]) && $count["J"] == 1)
                        return 3.5;
                    return 2; //two pair
                }
            case 4: // 2:1:1:1
                if (isset($count["J"]) && $count["J"] == 1)
                    return 3;
                if (isset($count["J"]) && $count["J"] == 2)
                    return 3;
                return 1; //one pair
            case 5: //
                if (isset($count["J"]) && $count["J"] == 1)
                    return 1;
                return 0;
        }

        throw new Exception();
    }
}
