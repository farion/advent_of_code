<?php declare(strict_types=1);

namespace day15\b;

use Monolog\Logger;

final class Solution15B
{
    private array $boxes = array();

    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution15B())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $content = str_replace(array("\r", "\n"), '', $content);
        $strings = explode(",", $content);

        $steps = array();
        foreach ($strings as $string) {
            preg_match("/^([a-z]+)([-=])([0-9]*)$/", $string, $matches);
            $steps[] = array("lense" => $matches[1],
                "action" => $matches[2],
                "focal" => $matches[3],
                "hash" => $this->getHash($matches[1]));
        }

        foreach ($steps as $step) {
            if ($step["action"] === "-") {
                if (isset($this->boxes[$step["hash"]])) {
                    foreach ($this->boxes[$step["hash"]] as $k => $lense) {
                        if ($lense["lense"] == $step["lense"]) {
                            unset($this->boxes[$step["hash"]][$k]);
                        }
                    }
                }
            } elseif ($step["action"] === "=") {
                if (isset($this->boxes[$step["hash"]])) {
                    $replaced = false;
                    foreach ($this->boxes[$step["hash"]] as $k => $lense) {
                        if ($lense["lense"] == $step["lense"]) {
                            $this->boxes[$step["hash"]][$k]["focal"] = $step["focal"];
                            $replaced = true;
                            break;
                        }
                    }
                    if (!$replaced) {
                        $this->boxes[$step["hash"]][] = array("lense" => $step["lense"], "focal" => $step["focal"]);
                    }
                } else {
                    $this->boxes[$step["hash"]][] = array("lense" => $step["lense"], "focal" => $step["focal"]);
                }
            } else {
                throw new \Exception();
            }
        }

        $r = 0;
        foreach ($this->boxes as $k => $box) {
            $i = 0;
            foreach ($box as $slot) {
                $i++;
                $br = ($k + 1);
                $br *= $i;
                $br *= $slot["focal"];
                $r += $br;
            }
        }
        return $r;
    }

    private function getHash(string $string)
    {
        $hash = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            $hash += ord($string[$i]);
            $hash *= 17;
            $hash %= 256;
        }
        return $hash;
    }
}