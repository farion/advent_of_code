<?php declare(strict_types=1);

namespace day8\a;

use Exception;

final class Solution8A
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $d = $lines[0];

        $net = array();
        for($i = 1;$i < sizeof($lines); $i++){
            $line = preg_replace("/[,()]|= /","",$lines[$i]);
            $lineNet = explode(" ", $line);
            if(sizeof($lineNet) == 3) {
                $net[$lineNet[0]] = array($lineNet[1],$lineNet[2]);
            }
        }

        $found = false;
        $curNet = $net["AAA"];
        $wayLength = 0;
        while(!$found) {
            for ($i = 0; $i < strlen($d); $i++) {
                $wayLength++;
                if($d[$i] == "L"){
                    $nextNetName = $curNet[0];
                }elseif($d[$i] == "R"){
                    $nextNetName = $curNet[1];
                }else{
                    throw new Exception();
                }
                if($nextNetName == "ZZZ"){
                    $found = true;
                    break;
                }
                $curNet = $net[$nextNetName];
            }
        }

        return $wayLength;
    }

}