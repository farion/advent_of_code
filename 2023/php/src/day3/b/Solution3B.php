<?php declare(strict_types=1);

namespace day3\b;

use Monolog\Logger;

final class Solution3B
{
    public static function getResult(string $inputFile, Logger $logger): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $numbers = array();
        $gears = array();
        foreach ($lines as $i => $line) {
            preg_match_all("([0-9]+)", $line, $line_numbers, PREG_OFFSET_CAPTURE);
            preg_match_all("([*]+)", $line, $line_gears, PREG_OFFSET_CAPTURE);
            $numbers[$i] = Solution3B::morph_pattern_matches($line_numbers[0]);
            $gears[$i] = Solution3b::morph_pattern_matches($line_gears[0]);
        }

        $found_numbers = array();
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
                        if (isset($gears[$s_y][$s_x])) {
                            if (!isset($found_numbers[$s_y . ":" . $s_x])) {
                                $found_numbers[$s_y . ":" . $s_x] = array();
                            }
                            $found_numbers[$s_y . ":" . $s_x][] = $number;
                            $found = true;
                            break;
                        }
                    }
                    if ($found) break;
                }
            }
        }

        $result = 0;
        foreach ($found_numbers as $tuple_candidate) {
            if (sizeof($tuple_candidate) === 2) {
                $result += $tuple_candidate[0] * $tuple_candidate[1];
            }
        }

        return $result;
    }

    private static function morph_pattern_matches($matches): array
    {
        $result = array();
        foreach ($matches as $match) {
            $result[$match[1]] = $match[0];
        }
        return $result;
    }
}