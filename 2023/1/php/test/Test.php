<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('a\\', __DIR__ . "/../a");
$loader->addPsr4('b\\', __DIR__ . "/../b");

use a\SolutionA;
use b\SolutionB;
use PHPUnit\Framework\TestCase;

final class Day1Test extends TestCase
{

    public function testA(): void
    {
        $this->assertSame(142, SolutionA::getResult("../test_a.txt"));
    }

    public function testB(): void
    {
        $this->assertSame(281, SolutionB::getResult("../test_b.txt"));
    }

    public function testResultA(): void
    {
        $this->assertSame(56506, SolutionA::getResult("../input.txt"));
    }

    public function testResultB(): void
    {
        $this->assertSame(56017, SolutionB::getResult("../input.txt"));
    }

}