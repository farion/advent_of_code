<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day16\b\Solution16B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay16B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(51, Solution16B::getResult("../resources/16/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(7759, Solution16B::getResult("../resources/16/input.txt", TestLogger::createTestLogger()));
    }

}