<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day8\b\Solution8B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay8B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(6, Solution8B::getResult("../resources/8/test_b.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(14299763833181, Solution8B::getResult("../resources/8/input.txt", TestLogger::createTestLogger()));
    }

}