<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day20\a\Solution20A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay20A extends TestCase
{

    public function testExample0(): void
    {
        $this->assertSame(32000000, Solution20A::getResult("../resources/20/test_a0.txt", TestLogger::createTestLogger()));
    }

    public function testExample1(): void
    {
        $this->assertSame(11687500, Solution20A::getResult("../resources/20/test_a1.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(841763884, Solution20A::getResult("../resources/20/input.txt", TestLogger::createTestLogger()));
    }

}