<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day1\a\Solution1A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay1A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(142, Solution1A::getResult("../resources/1/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(56506, Solution1A::getResult("../resources/1/input.txt", TestLogger::createTestLogger()));
    }

}