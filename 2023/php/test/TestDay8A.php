<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day8\a\Solution8A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay8A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(2, Solution8A::getResult("../resources/8/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(18157, Solution8A::getResult("../resources/8/input.txt", TestLogger::createTestLogger()));
    }

}