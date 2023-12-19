<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day18\b\Solution18B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay18B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(952408144115, Solution18B::getResult("../resources/18/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(40343619199142, Solution18B::getResult("../resources/18/input.txt", TestLogger::createTestLogger()));
    }

}