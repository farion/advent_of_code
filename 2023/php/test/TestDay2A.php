<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day2\a\Solution2A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay2A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(8, Solution2A::getResult("../resources/2/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(2776, Solution2A::getResult("../resources/2/input.txt", TestLogger::createTestLogger()));
    }

}