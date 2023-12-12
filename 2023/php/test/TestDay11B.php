<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day10\a\Solution10A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay10A extends TestCase
{

    public function testExample1(): void
    {
        $this->assertSame(4, Solution10A::getResult("../resources/10/test_a0.txt", TestLogger::createTestLogger(), TestLogger::createTestLogger()));
    }

    public function testExample2(): void
    {
        $this->assertSame(8, Solution10A::getResult("../resources/10/test_a1.txt", TestLogger::createTestLogger(), TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(6820, Solution10A::getResult("../resources/10/input.txt", TestLogger::createTestLogger(), TestLogger::createTestLogger()));
    }

}