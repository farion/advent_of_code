<?php declare(strict_types=1);

namespace day19\b;

use Monolog\Logger;
use RuntimeException;

final class Solution19B
{
    private array $workflows = array();

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution19B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);
        $y = 0;
        for (; $y < sizeof($lines); $y++) {
            $line = $lines[$y];
            if (!trim($line))
                break;
            preg_match("/^([a-z]+)\{(.*)}$/", $line, $matches1);
            preg_match_all("/(([a-z])([<>])([0-9]+):([A-z]+))|([A-z]+)/", $matches1[2], $matches2);
            $workflow = [];
            for ($i = 0; $i < sizeof($matches2[0]); $i++) {
                if ($matches2[6][$i]) {
                    $workflow[] = ["*", "*", "*", $matches2[6][$i]];
                } else {
                    $workflow[] = [$matches2[2][$i], $matches2[3][$i], $matches2[4][$i], $matches2[5][$i]];
                }
            }
            $this->workflows[$matches1[1]] = $workflow;
        }

        $acceptedParts = $this->startWorkflow();

        $r = 0;
        foreach ($acceptedParts as $acceptedPart) {
            $r += ($acceptedPart["x"][1] - $acceptedPart["x"][0] + 1) *
                ($acceptedPart["m"][1] - $acceptedPart["m"][0] + 1) *
                ($acceptedPart["a"][1] - $acceptedPart["a"][0] + 1) *
                ($acceptedPart["s"][1] - $acceptedPart["s"][0] + 1);
        }

        return $r;
    }

    private function startWorkflow($limits = ["x" => [1, 4000], "m" => [1, 4000], "a" => [1, 4000], "s" => [1, 4000]], $workflowName = "in"): array
    {
        $r = [];
        foreach ($this->workflows[$workflowName] as $step) {
            if ($step[1] == "*") {
                if ($step[3] == "A")
                    $r[] = $limits;
                elseif ($step[3] != "R")
                    $r = array_merge($r, $this->startWorkflow($limits, $step[3]));
            } elseif ($step[1] == "<") {
                if ($limits[$step[0]][0] < $step[2] && $limits[$step[0]][1] < $step[2]) {
                    $r = array_merge($r, $this->startWorkflow($limits, $step[3]));
                    break;
                } elseif ($limits[$step[0]][0] < $step[2]) {
                    $leftLimits = $limits;
                    $leftLimits[$step[0]][1] = $step[2] - 1;
                    $limits[$step[0]][0] = $step[2];
                    if ($leftLimits[$step[0]][0] <= $leftLimits[$step[0]][1]) {
                        if ($step[3] == "A")
                            $r[] = $leftLimits;
                        elseif ($step[3] != "R")
                            $r = array_merge($r, $this->startWorkflow($leftLimits, $step[3]));
                    }
                }
            } elseif ($step[1] == ">") {
                if ($limits[$step[0]][0] > $step[2] && $limits[$step[0]][1] > $step[2]) {
                    $r = array_merge($r, $this->startWorkflow($limits, $step[3]));
                    break;
                } elseif ($limits[$step[0]][1] > $step[2]) {
                    $rightLimits = $limits;
                    $rightLimits[$step[0]][0] = $step[2] + 1;
                    $limits[$step[0]][1] = $step[2];
                    if ($rightLimits[$step[0]][0] <= $rightLimits[$step[0]][1]) {
                        if ($step[3] == "A")
                            $r[] = $rightLimits;
                        elseif ($step[3] != "R")
                            $r = array_merge($r, $this->startWorkflow($rightLimits, $step[3]));
                    }
                }
            }
        }
        return $r;

    }
}