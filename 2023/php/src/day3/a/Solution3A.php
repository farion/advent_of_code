<?php declare(strict_types=1);

namespace day3\a;

final class Solution3A
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $result = 0;
        $numbers = array();
        $symbols = array();
        foreach ($lines as $i => $line) {
            preg_match_all("([0-9]+)", $line, $line_numbers, PREG_OFFSET_CAPTURE);
            preg_match_all("([^0-9.]+)", $line, $line_symbols, PREG_OFFSET_CAPTURE);
            $numbers[$i] = Solution3A::morph_pattern_matches($line_numbers[0]);
            $symbols[$i] = Solution3A::morph_pattern_matches($line_symbols[0]);
        }
        foreach ($numbers as $y => $line_numbers) {
            foreach ($line_numbers as $x => $number) {
                $w = strlen($number);
                $x_l = $x - 1;
                $x_r = $x + $w;
                $y_t = $y - 1;
                $y_b = $y + 1;
                $found = false;
                for ($s_y = $y_t; $s_y <= $y_b; $s_y++) {
                    for ($s_x = $x_l; $s_x <= $x_r; $s_x++) {
                        if (isset($symbols[$s_y][$s_x])) {
                            $result += $number;
                            $found = true;
                            break;
                        }
                    }
                    if ($found) break;
                }
            }
        }

        return $result;
    }

    private static function morph_pattern_matches($matches)
    {
        $result = array();
        foreach($matches as $match){
            $result[$match[1]] = $match[0];
        }
        return $result;
    }
}