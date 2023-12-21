<?php declare(strict_types=1);

namespace day20\b;

use Monolog\Logger;
use RuntimeException;

final class Solution20B
{
    private array $modules = array();

    private array $signalQueue = array();

    private Logger $logger;

    private int $presses = 0;

    private array $conjIn = [];

    private array $conjOut = [];

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution20B())->getNonStaticResult($inputFile, $logger);
    }

    private function getNonStaticResult(string $inputFile, Logger $logger)
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

            foreach ($module["targets"] as $target) {
                if (!isset($this->modules[$target])) {
                    $this->modules[$target] = ["type" => "sink", "targets" => []];
                }
            }
        }

        $lastConjunction = $this->findLastConjunction("rx", 0);

        foreach ($this->modules[$lastConjunction[0]]["inputStates"] as $input => $_) {
            $this->conjIn[$input] = true;
        }

        do {
            $this->presses++;
            $this->pushToQueue("button", "broadcaster", 0);
        } while (!$this->runQueue($lastConjunction[1]));

        $r = null;
        foreach ($this->conjOut as $c) {
            if ($r == null)
                $r = $c;
            else
                $r = $this->kgv($r, $c);
        }
        return $r;
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

    private function pushToQueue(string $input, string $target, int $signal): void
    {
        $this->signalQueue[] = ["input" => $input, "target" => $target, "signal" => $signal];
    }

    private function getSignalFromQueue(): array
    {
        return array_shift($this->signalQueue);
    }

    private function runQueue(int $searchSignal): bool
    {
        while (sizeof($this->signalQueue)) {
            $nextSignal = $this->getSignalFromQueue();
            $this->logger->debug($nextSignal["input"] . " -" . ($nextSignal["signal"] ? "high" : "low") . "-> " . $nextSignal["target"]);

            if ($nextSignal["signal"] == $searchSignal) {
                if (isset($this->conjIn[$nextSignal["input"]])) {
                    $this->conjOut[$nextSignal["input"]] = $this->presses;
                }
            }
            if (sizeof($this->conjOut) == sizeof($this->conjIn))
                return true;

            $this->pushSignal($nextSignal);
        }
        return false;

    }

    private function pushSignal(array $nextSignal): void
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
        } elseif ($module["type"] == "sink") {
            // nothing to do
        } else {
            throw new RuntimeException();
        }
    }

    private function findLastConjunction(string $name, int $goal): array
    {
        foreach ($this->modules as $cand => $module) {
            if (in_array($name, $module["targets"])) {
                if ($module["type"] == "flipflop") {
                    return $this->findLastConjunction($cand, $goal ? 0 : 1);
                } elseif ($module["type"] == "conjunction") {
                    return [$cand, $goal ? 0 : 1];
                }
            }
        }

        throw new RuntimeException();
    }
}