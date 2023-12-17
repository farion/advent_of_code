<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day17\a\Solution17A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay17A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(102, Solution17A::getResult("../resources/17/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(1263, Solution17A::getResult("../resources/17/input.txt", TestLogger::createTestLogger()));
    }

}