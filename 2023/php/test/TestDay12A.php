<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day12\a\Solution12A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay12A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(21, Solution12A::getResult("../resources/12/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(7260, Solution12A::getResult("../resources/12/input.txt", TestLogger::createTestLogger()));
    }

}