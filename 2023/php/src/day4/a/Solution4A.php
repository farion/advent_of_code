<?php declare(strict_types=1);

namespace day4\a;

final class Solution4A
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;

        foreach ($lines as $i => $line) {
            $splittedLine = preg_split('/[|:]/', $line);
            preg_match_all("([0-9]+)",$splittedLine[1],$winning);
            preg_match_all("([0-9]+)",$splittedLine[2],$having);
            $winning = $winning[0];
            $having = $having[0];
            $lineResult = 0;
            foreach($winning as $winNumber){
                if(in_array($winNumber, $having)){
                    if($lineResult == 0){
                        $lineResult = 1;
                    }else{
                        $lineResult *= 2;
                    }
                }
            }
            $result += $lineResult;
        }

        return $result;
    }
}