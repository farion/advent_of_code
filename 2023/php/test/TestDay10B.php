<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day10\b\Solution10B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay10B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(10, Solution10B::getResult("../resources/10/test_b.txt", TestLogger::createTestLogger(), TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(337, Solution10B::getResult("../resources/10/input.txt", TestLogger::createTestLogger(), TestLogger::createTestLogger()));
    }

}