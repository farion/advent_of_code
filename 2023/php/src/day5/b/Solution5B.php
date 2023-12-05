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
                usort($data[$currentSource]["mapping"], function ($a, $b) {
                    return $a["sourceStart"] > $b["sourceStart"];
                });
            }
        }

        $curLoc = null;
        $seek = 20000;
        for ($i = 0; $i < sizeof($seeds[1]); $i++) {
            for ($j = 0; $j < intval($seeds[2][$i]); $j++) {

                $seed = intval($seeds[1][$i]) + $j;
                $r = self::getTarget("seed", "location", $seed, $data);

                if ($curLoc == null) {
                    $curLoc = $r["destination"];
                } else {
                    $curLoc = min($curLoc, $r["destination"]);
                }

                //try seek
                if ($j + $seek < intval($seeds[2][$i])) {
                    $r2 = self::getTarget("seed", "location", $seed + $seek, $data);
                    if($r2["pathid"] == $r["pathid"]){
                        if ($curLoc == null) {
                            $curLoc = $r2["destination"];
                        } else {
                            $curLoc = min($curLoc, $r["destination"]);
                        }
                        $j += $seek;
                    }
                }
            }
        }

        return $curLoc;
    }

    private
    static function getTarget(string $source, string $target, int $initial, array $data, $pathid = "")
    {

        if ($source == $target)
            return 0;

        $destination = $initial;

        $hit = "-1";

        foreach ($data[$source]["mapping"] as $i => $mapping) {
            if ($initial >= $mapping["sourceStart"] && $initial < $mapping["sourceStart"] + $mapping["range"]) {
                $destination = $mapping["destinationStart"] + ($initial - $mapping["sourceStart"]);
                $hit = $i;
                break;
            }

        }

        if ($data[$source]["target"] == $target) {
            return array(
                "destination" => $destination,
                "pathid" => $pathid . $source . "-to-" . $data[$source]["target"] . ":" . $hit . ">"
            );
        }
        return self::getTarget($data[$source]["target"], $target, $destination, $data, $pathid);

    }
}