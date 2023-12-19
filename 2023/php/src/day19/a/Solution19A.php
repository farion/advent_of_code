<?php declare(strict_types=1);

namespace day19\a;

use Monolog\Logger;
use RuntimeException;

final class Solution19A
{
    private array $workflows = array();

    private array $parts = array();

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution19A())->getNonStaticResult($inputFile, $logger);
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

        $y++;
        for (; $y < sizeof($lines); $y++) {
            $line = $lines[$y];
            if (!trim($line))
                break;
            preg_match_all("/([a-z])=([0-9]+)/", $line, $matches);

            $part = [];
            for ($i = 0; $i < sizeof($matches[0]); $i++) {
                $part[$matches[1][$i]] = $matches[2][$i];
            }

            $this->parts[] = $part;
        }

        $acceptedParts = $this->startWorkflow();

        $r = 0;
        foreach($acceptedParts AS $part){
            $r += $part["x"];
            $r += $part["m"];
            $r += $part["a"];
            $r += $part["s"];
        }

        return $r;
    }

    private function startWorkflow()
    {
        $acceptedParts = [];
        foreach ($this->parts as $part) {
            if($this->runWorkflow($part)){
                $acceptedParts[] = $part;
            }
        }
        return $acceptedParts;
    }

    private function runWorkflow(array $part, $workflowName = "in")
    {
        $wf = $this->workflows[$workflowName];

        $next = null;
        foreach ($wf as $step) {
            if ($step[0] == "*") {
                $next = $step[3];
                break;
            }
            if ($step[1] == "<") {
                if ($part[$step[0]] < $step[2]) {
                    $next = $step[3];
                    break;
                }
            }
            if ($step[1] == ">") {
                if ($part[$step[0]] > $step[2]) {
                    $next = $step[3];
                    break;
                }
            }
        }

        if ($next == "R") {
            return false;
        } elseif ($next == "A") {
            return true;
        } else {
            return $this->runWorkflow($part, $next);
        }
    }
}