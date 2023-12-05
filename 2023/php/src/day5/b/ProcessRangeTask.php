<?php

use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;

class ProcessRangeTask implements Task
{
    private mixed $curLoc = null;

    public function __construct(
        private readonly string $sourceStart,
        private readonly string $range,
        private readonly array  $data,
        private readonly int    $seek

    )
    {
    }

    private function searchSeed(int $seed): string
    {
        $r = self::getTarget("seed", "location", $seed);
        if ($this->curLoc == null) {
            $this->curLoc = $r["destination"];
        } else {
            $this->curLoc = min($this->curLoc, $r["destination"]);
        }
        return $r["pathid"];
    }

    public function run(Channel $channel, Cancellation $cancellation): mixed
    {

        for ($j = 0; $j < intval($this->range); $j++) {
            $seed = intval($this->sourceStart) + $j;
            $pathid = $this->searchSeed($seed);

            if ($j + $this->seek >= intval($this->range))
                continue;

            $j += ($pathid == self::searchSeed($seed + $this->seek)) ? $this->seek : 0;
        }

        return $this->curLoc;
    }


    private function getTarget(string $source, string $target, int $initial, $pathid = ""): array
    {
        if ($source == $target)
            return array();

        $destination = $initial;
        $hit = "-1";

        foreach ($this->data[$source]["mapping"] as $i => $mapping) {
            if ($initial >= $mapping["sourceStart"] && $initial < $mapping["sourceStart"] + $mapping["range"]) {
                $destination = $mapping["destinationStart"] + ($initial - $mapping["sourceStart"]);
                $hit = $i;
                break;
            }
        }

        if ($this->data[$source]["target"] == $target) {
            return array(
                "destination" => $destination,
                "pathid" => $pathid . $source . "-to-" . $this->data[$source]["target"] . ":" . $hit . ">"
            );
        }

        return self::getTarget($this->data[$source]["target"], $target, $destination, $pathid);
    }
}
