<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day13\b\Solution13B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay13B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(400, Solution13B::getResult("../resources/13/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(36735, Solution13B::getResult("../resources/13/input.txt", TestLogger::createTestLogger()));
    }

}