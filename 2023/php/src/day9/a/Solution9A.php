<?php declare(strict_types=1);

namespace day9\a;

final class Solution9A
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $nextNumbers = array();

        foreach($lines as $line){
            if(!trim($line))
                continue;
            $lineData = array();
            foreach(explode(" ",$line) as $linePart){
                $lineData[] = intval($linePart);
            }
            $nextNumbers[] = self::getNextNumber($lineData);
        }

        return array_sum($nextNumbers);
    }

    private static function getNextNumber(array $lineData)
    {
        $zeroLine = true;
        foreach($lineData as $number){
            if($number !== 0){
                $zeroLine = false;
                break;
            }
        }

        if($zeroLine){
            return 0;
        }

        $lastNumber = null;
        $nextHistory = array();
        foreach($lineData as $number){
            if($lastNumber === null){
                $lastNumber = $number;
                continue;
            }
            $nextHistory[] = $number - $lastNumber;
            $lastNumber = $number;
        }

        $diff = self::getNextNumber($nextHistory);
        return  $lineData[sizeof($lineData)-1] + $diff;
    }
}