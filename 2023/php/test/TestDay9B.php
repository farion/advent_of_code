<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day9\b\Solution9B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay9B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(2, Solution9B::getResult("../resources/9/test_b.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(928, Solution9B::getResult("../resources/9/input.txt", TestLogger::createTestLogger()));
    }

}