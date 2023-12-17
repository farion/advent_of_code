<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day15\a\Solution15A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay15A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(1320, Solution15A::getResult("../resources/15/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(507769, Solution15A::getResult("../resources/15/input.txt", TestLogger::createTestLogger()));
    }

}