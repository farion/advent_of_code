<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day18\a\Solution18A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay18A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(62, Solution18A::getResult("../resources/18/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(61865, Solution18A::getResult("../resources/18/input.txt", TestLogger::createTestLogger()));
    }

}