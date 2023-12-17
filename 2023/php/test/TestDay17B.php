<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day17\b\Solution17B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay17B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(94, Solution17B::getResult("../resources/17/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testExampleB(): void
    {
        $this->assertSame(71, Solution17B::getResult("../resources/17/test_b.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(1411, Solution17B::getResult("../resources/17/input.txt", TestLogger::createTestLogger()));
    }

}