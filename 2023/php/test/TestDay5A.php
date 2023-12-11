<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day5\a\Solution5A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay5A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(35, Solution5A::getResult("../resources/5/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(196167384, Solution5A::getResult("../resources/5/input.txt", TestLogger::createTestLogger()));
    }

}