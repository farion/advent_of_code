<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day12\b\Solution12B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay12B extends TestCase
{
    public function testExample(): void
    {
        $this->assertSame(525152, Solution12B::getResult("../resources/12/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(1909291258644, Solution12B::getResult("../resources/12/input.txt", TestLogger::createTestLogger()));
    }

}