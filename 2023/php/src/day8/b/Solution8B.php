<?php declare(strict_types=1);

namespace day8\b;

use Exception;

final class Solution8B
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $d = $lines[0];

        $net = array();
        for ($i = 1; $i < sizeof($lines); $i++) {
            $line = preg_replace("/[,()]|= /", "", $lines[$i]);
            $lineNet = explode(" ", $line);
            if (sizeof($lineNet) == 3) {
                $net[$lineNet[0]] = array($lineNet[1], $lineNet[2]);
            }
        }

        $result = 1;
        foreach ($net as $k => $n) {
            if ($k[2] == "A") {
                $result = self::kgv($result, self::findPath($d, $net, $n));
            }
        }
        return $result;
    }

    private static function findPath($d, $net, $curNet): int
    {
        $found = false;
        $wayLength = 0;
        while (!$found) {
            for ($i = 0; $i < strlen($d); $i++) {
                $wayLength++;
                if ($d[$i] == "L") {
                    $nextNetName = $curNet[0];
                } elseif ($d[$i] == "R") {
                    $nextNetName = $curNet[1];
                }else{
                    throw new Exception();
                }
                if ($nextNetName[2] == "Z") {
                    $found = true;
                    break;
                }
                $curNet = $net[$nextNetName];
            }
        }
        return $wayLength;
    }

    private static function kgv($m_in, $n_in)
    {
        $m = max($m_in, $n_in);
        $n = min($m_in, $n_in);
        while ($n !== 0) {
            $ggv = $n;
            $n = $m % $n;
            $m = $ggv;
        }
        return abs($m_in * $n_in) / $ggv;
    }

}
