<?php declare(strict_types=1);

namespace day5\a;

final class Solution5A
{
    public static function getResult(string $inputFile): int
    {
        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        $data = array();

        $currentSource = "seeds";

        foreach ($lines as $line) {
            if (trim($line) === "") {
                continue;
            }

            if (preg_match("/^seeds:.*/", $line)) {
                preg_match_all("/[0-9]+/", $line, $seeds);
                foreach ($seeds[0] as $seed){
                    $data[$currentSource][] = intval($seed);
                }
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

        $locations = array();
        foreach($data["seeds"] as $seed) {
            $locations[] = self::getTarget("seed", "location", $seed, $data);
        }

        return min($locations);
    }

    private static function getTarget(string $source, string $target, int $initial, array $data)
    {
        $destination = $initial;
        foreach($data[$source]["mapping"] as $mapping){
            if($initial >= $mapping["sourceStart"] && $initial < $mapping["sourceStart"] + $mapping["range"]){
                $destination = $mapping["destinationStart"] + ($initial - $mapping["sourceStart"]);
                break;
            }
        }
        if($data[$source]["target"] == $target){
            return $destination;
        }
        return self::getTarget($data[$source]["target"], $target, $destination, $data);
    }
}