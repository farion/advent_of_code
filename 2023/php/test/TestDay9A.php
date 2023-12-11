<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day9\a\Solution9A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay9A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(114, Solution9A::getResult("../resources/9/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(1974232246, Solution9A::getResult("../resources/9/input.txt", TestLogger::createTestLogger()));
    }

}