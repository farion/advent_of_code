<?php declare(strict_types=1);

namespace day5\b;

use Amp\Parallel\Worker\Execution;
use ProcessRangeTask;
use function Amp\Future\await;
use function Amp\Parallel\Worker\submit;

final class Solution5B
{
    private static int $SEEK = 10000;

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

        $executions = [];
        for ($i = 0; $i < sizeof($seeds[1]); $i++) {
            $executions[$i] = submit(new ProcessRangeTask($seeds[1][$i], $seeds[2][$i], $data, self::$SEEK));
        }

        $responses = await(array_map(
            fn(Execution $e) => $e->getFuture(),
            $executions,
        ));

        return min($responses);
    }
}