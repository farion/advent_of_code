<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day7\a\Solution7A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay7A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(6440, Solution7A::getResult("../resources/7/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(251029473, Solution7A::getResult("../resources/7/input.txt", TestLogger::createTestLogger()));
    }

}