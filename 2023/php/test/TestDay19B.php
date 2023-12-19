<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day19\b\Solution19B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay19B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(167409079868000, Solution19B::getResult("../resources/19/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(132380153677887, Solution19B::getResult("../resources/19/input.txt", TestLogger::createTestLogger()));
    }

}