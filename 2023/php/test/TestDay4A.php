<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day4\a\Solution4A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay4A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(13, Solution4A::getResult("../resources/4/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(23235, Solution4A::getResult("../resources/4/input.txt", TestLogger::createTestLogger()));
    }

}