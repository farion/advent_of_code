<?php declare(strict_types=1);

namespace day21\b;

use Monolog\Logger;
use RuntimeException;

final class Solution21B
{
    private array $modules = array();

    private array $signalQueue = array();

    private Logger $logger;

    private int $lowCount = 0;

    private int $highCount = 0;

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution21B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        for ($y = 0; $y < sizeof($lines); $y++) {
            $line = $lines[$y];
            if (!trim($line))
                break;

            preg_match("/^([a-z%&]+) -> ([a-z, ]+)$/", $line, $matches1);
            preg_match_all("/[a-z]+/", $matches1[2], $matches2);
            if ($matches1[1][0] == "&") {
                $moduleName = substr($matches1[1], 1);
                $moduleType = "conjunction";

            } elseif ($matches1[1][0] == "%") {
                $moduleName = substr($matches1[1], 1);
                $moduleType = "flipflop";
            } else {
                $moduleName = "broadcaster";
                $moduleType = "broadcaster";
            }

            $this->modules[$moduleName] = ["type" => $moduleType, "targets" => $matches2[0]];
        }

        foreach ($this->modules as $name => $module) {
            if ($module["type"] == "conjunction") {
                $inputStates = [];
                foreach ($this->modules as $inputName => $inputModule) {
                    if (in_array($name, $inputModule["targets"])) {
                        $inputStates[$inputName] = 0;
                    }
                }
                $this->modules[$name]["inputStates"] = $inputStates;
            }
            if ($module["type"] == "flipflop") {
                $this->modules[$name]["state"] = 0;
            }

            foreach($module["targets"] as $target){
                if(!isset($this->modules[$target])){
                    $this->modules[$target] = ["type" => "sink", "targets" => []];
                }
            }
        }

        for ($i = 0; $i < 1000; $i++) {
            $this->pushToQueue("button", "broadcaster", 0);
            $this->runQueue();
        }

        return $this->lowCount*$this->highCount;
    }

    private function pushToQueue(string $input, string $target, int $signal): void
    {
        $this->signalQueue[] = ["input" => $input, "target" => $target, "signal" => $signal];
    }

    private function getSignalFromQueue(): array
    {
        return array_shift($this->signalQueue);
    }

    private function runQueue()
    {
        while (sizeof($this->signalQueue)) {
            $nextSignal = $this->getSignalFromQueue();
            $this->logger->debug($nextSignal["input"] . " -" . ($nextSignal["signal"] ? "high" : "low") . "-> " . $nextSignal["target"]);
            if ($nextSignal["signal"] == 1) {
                $this->highCount++;
            } else {
                $this->lowCount++;
            }
            $this->pushSignal($nextSignal);
        }

    }

    private function pushSignal(array $nextSignal)
    {
        $module = &$this->modules[$nextSignal["target"]];
        $input = $nextSignal["target"];
        $prev = $nextSignal["input"];

        if ($module["type"] == "broadcaster") {
            foreach ($module["targets"] as $target) {
                $this->pushToQueue($input, $target, $nextSignal["signal"]);
            }
        } elseif ($module["type"] == "conjunction") {
            if (!isset($module["inputStates"][$prev]))
                throw new RuntimeException();
            $this->modules[$input]["inputStates"][$prev] = $nextSignal["signal"];
            $allHigh = true;
            foreach ($this->modules[$input]["inputStates"] as $inputState) {
                if ($inputState == 0) {
                    $allHigh = false;
                    break;
                }
            }

            foreach ($module["targets"] as $target) {
                $this->pushToQueue($input, $target, $allHigh ? 0 : 1);
            }
        } elseif ($module["type"] == "flipflop") {
            if ($nextSignal["signal"] == 0) {
                $module["state"] = $module["state"] ? 0 : 1;
                foreach ($module["targets"] as $target) {
                    $this->pushToQueue($input, $target, $module["state"]);
                }
            }
        }elseif($module["type"] == "sink"){
            // nothing to do
        }else{
            throw new RuntimeException();
        }
    }
}