<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day7\b\Solution7B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay7B extends TestCase
{

    public function testExample(): void
    {
        $this->assertEqualsWithDelta(5905, Solution7B::getResult("../resources/7/test_b.txt", TestLogger::createTestLogger()), 0);
    }

    public function testResult(): void
    {
        $this->assertEqualsWithDelta(251003917, Solution7B::getResult("../resources/7/input.txt", TestLogger::createTestLogger()), 0);
    }

}