<?php declare(strict_types=1);

namespace day4\b;

final class Solution4B
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;

        $cards = array();

        foreach ($lines as $line) {
            $splittedLine = preg_split('/[|:]/', $line);
            preg_match_all("([0-9]+)", $splittedLine[1], $winning);
            preg_match_all("([0-9]+)", $splittedLine[2], $having);
            preg_match("([0-9]+)", $splittedLine[0], $cardNumber);
            $lineResult = 0;
            $winning = $winning[0];
            $having = $having[0];
            foreach ($winning as $winNumber) {
                if (in_array($winNumber, $having)) {
                    $lineResult++;
                }
            }
            $cardNumber = intval($cardNumber[0]);
            $cards[$cardNumber] = $lineResult;
        }

        foreach ($cards as $i => $card) {
            $result += self::processCard($i, $cards);
        }

        return $result;
    }

    private static function processCard(int $cardNumber, array &$cards): int
    {
        $result = 1;
        for ($i = $cardNumber + 1; $i <= $cardNumber + $cards[$cardNumber]; $i++) {
            $result += self::processCard($i, $cards);
        }
        return $result;
    }
}