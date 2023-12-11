<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day4\b\Solution4B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay4B extends TestCase
{
    public function testExample(): void
    {
        $this->assertSame(30, Solution4B::getResult("../resources/4/test_b.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(5920640, Solution4B::getResult("../resources/4/input.txt", TestLogger::createTestLogger()));
    }

}