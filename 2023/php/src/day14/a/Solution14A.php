<?php declare(strict_types=1);

namespace day14\a;

use Monolog\Logger;
use RuntimeException;

final class Solution14A
{
    private array $columns = array();


    public static function getResult(string $inputFile, Logger $logger): int
    {
        return (new Solution14A())->getNonStaticResult($inputFile, $logger);
    }

    public function getNonStaticResult(string $inputFile, Logger $logger): int
    {
        $this->logger = $logger;

        $content = file_get_contents($inputFile);
        $lines = explode("\n", $content);

        foreach($lines AS $k => $line){
            for($i = 0; $i < strlen($line); $i++) {
                $this->columns[$i][$k] = $line[$i];
            }
        }

        $this->tiltToNorth();

        return $this->countWeight();
    }

    private function tiltToNorth(): void
    {
        for($c = 0; $c < sizeof($this->columns); $c++){
            $this->tiltColumnToNorth($c);
        }
    }

    private function tiltColumnToNorth(int $c): void
    {
        for($i = 0; $i < sizeOf($this->columns[$c]); $i++){
            if($this->columns[$c][$i] === "O") {
                $j = $i;
                while(isset($this->columns[$c][$j-1]) && $this->columns[$c][$j-1] === "."){
                    $this->columns[$c][$j-1] = "O";
                    $this->columns[$c][$j] = ".";
                    $j--;
                }
            }
        }
    }

    private function countWeight(): int
    {
        $r = 0;
        foreach($this->columns AS $column){
            $reverseColumns = array_combine( array_keys( $column ), array_reverse( array_values( $column ) ) );
            foreach ($reverseColumns AS $k => $reverseColumn){
                if($reverseColumn === "O"){
                    $r += $k+1;
                }
            }
        }
        return $r;
    }

}