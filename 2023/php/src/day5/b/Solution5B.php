<?php declare(strict_types=1);

namespace day5\b;

final class Solution5B
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $data = array();

        $currentSource = "seeds";
        $seeds = array();

        foreach ($lines as $line) {
            if (trim($line) === "") {
                continue;
            }

            if (preg_match("/^seeds:.*/", $line)) {
                preg_match_all("/([0-9]+) ([0-9]+)/", $line, $seeds);

            } elseif (preg_match("/^([a-z]+)-to-([a-z]+).*$/", $line, $categoryMatches)) {
                $currentSource = $categoryMatches[1];
                $currentTarget = $categoryMatches[2];
                $data[$currentSource] = array(
                    "target" => $currentTarget,
                    "mapping" => array()
                );
            } else {
                preg_match_all("/[0-9]+/", $line, $mappingMatches);
                $data[$currentSource]["mapping"][] = array(
                    "destinationStart" => intval($mappingMatches[0][0]),
                    "sourceStart" => intval($mappingMatches[0][1]),
                    "range" => intval($mappingMatches[0][2])
                );
            }
        }


        $curLoc = null;
        for ($i = 0; $i < sizeof($seeds[1]); $i++) {
            echo "Searching seed " . $seeds[1][$i] . "-" . $seeds[2][$i] . " -> curLoc: " . $curLoc . "\n";
            for ($j = 0; $j < intval($seeds[2][$i]); $j++) {
                $seed = intval($seeds[1][$i]) + $j;
                $loc = self::getTarget("seed", "location", $seed, $data);
                if ($curLoc == null) {
                    $curLoc = $loc;
                } else {
                    $curLoc = min($curLoc, $loc);
                }
            }
        }

        //  return self::getTarget("seed","location",956462721,$data);

        return $curLoc;
    }

    private static function getTarget(string $source, string $target, int $initial, array $data)
    {

        if ($source == $target)
            return 0;

        $destination = $initial;
        foreach ($data[$source]["mapping"] as $mapping) {
            if ($initial >= $mapping["sourceStart"] && $initial < $mapping["sourceStart"] + $mapping["range"]) {
                $destination = $mapping["destinationStart"] + ($initial - $mapping["sourceStart"]);
                break;
            }
        }
        if ($data[$source]["target"] == $target) {
            return $destination;
        }
        return self::getTarget($data[$source]["target"], $target, $destination, $data);
    }
}