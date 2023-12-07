<?php declare(strict_types=1);

namespace day7\a;

use PHPUnit\Logging\Exception;

final class Solution7A
{
    public static function getResult(string $inputFile): int
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

        $h1 = preg_replace(array("/A/", "/K/", "/Q/", "/J/", "/T/"), array("E", "D", "C", "B", "A"), $c1["hand"]);
        $h2 = preg_replace(array("/A/", "/K/", "/Q/", "/J/", "/T/"), array("E", "D", "C", "B", "A"), $c2["hand"]);

        for($i = 0; $i < 5; $i++){
            if($h1[$i] != $h2[$i]){
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
            case 1:
                return 99; // five
            case 2:
                if (in_array(4, $count)) {
                    return 90; // four
                } else {
                    return 80; // full house
                }
            case 3:
                if (in_array(3, $count)) {
                    return 70; //three
                } else {
                    return 60; //two
                }
            case 4:
                return 50; //one
            case 5:
                return 40; //high
        }

        throw new Exception();
    }
}