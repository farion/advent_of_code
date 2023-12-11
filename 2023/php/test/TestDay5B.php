<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day5\b\Solution5B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay5B extends TestCase
{
    public function testExample(): void
    {
        $this->assertSame(46, Solution5B::getResult("../resources/5/test_b.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(125742456, Solution5B::getResult("../resources/5/input.txt", TestLogger::createTestLogger()));
    }

}