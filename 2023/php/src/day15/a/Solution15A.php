<?php declare(strict_types=1);

namespace day15\a;

use Monolog\{Logger};
use RuntimeException;

final class Solution15A
{


    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution15A())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $content = str_replace(array("\r", "\n"), '', $content);
        $strings = explode(",", $content);

        $r = 0;
        foreach ($strings as $string) {
            $r += $this->getHash($string);
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