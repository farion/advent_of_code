<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day3\a\Solution3A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay3A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(4361, Solution3A::getResult("../resources/3/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(533784, Solution3A::getResult("../resources/3/input.txt", TestLogger::createTestLogger()));
    }

}