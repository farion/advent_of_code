<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day11\b\Solution11B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay11B extends TestCase
{

    public function testExample1(): void
    {
        $this->assertSame(1030, Solution11B::getResult("../resources/11/test_a.txt", TestLogger::createTestLogger(), 10));
    }

    public function testExample2(): void
    {
        $this->assertSame(8410, Solution11B::getResult("../resources/11/test_a.txt", TestLogger::createTestLogger(), 100));
    }

    public function testResult(): void
    {
        $this->assertSame(779032247216, Solution11B::getResult("../resources/11/input.txt", TestLogger::createTestLogger(), 1000000));
    }

}