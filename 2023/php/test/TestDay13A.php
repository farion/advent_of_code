<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day13\a\Solution13A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay13A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(405, Solution13A::getResult("../resources/13/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(30518, Solution13A::getResult("../resources/13/input.txt", TestLogger::createTestLogger()));
    }

}