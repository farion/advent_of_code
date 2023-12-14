<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day14\b\Solution14B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay14B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(64, Solution14B::getResult("../resources/14/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(103445, Solution14B::getResult("../resources/14/input.txt", TestLogger::createTestLogger()));
    }

}