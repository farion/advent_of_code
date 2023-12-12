<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day11\a\Solution11A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay11A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(374, Solution11A::getResult("../resources/11/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(9805264, Solution11A::getResult("../resources/11/input.txt", TestLogger::createTestLogger()));
    }

}