<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day15\b\Solution15B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay15B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(145, Solution15B::getResult("../resources/15/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(269747, Solution15B::getResult("../resources/15/input.txt", TestLogger::createTestLogger()));
    }

}