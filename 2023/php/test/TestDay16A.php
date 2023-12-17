<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day16\a\Solution16A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay16A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(46, Solution16A::getResult("../resources/16/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(7034, Solution16A::getResult("../resources/16/input.txt", TestLogger::createTestLogger()));
    }

}