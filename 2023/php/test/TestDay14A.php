<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day14\a\Solution14A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay14A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(136, Solution14A::getResult("../resources/14/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(108840, Solution14A::getResult("../resources/14/input.txt", TestLogger::createTestLogger()));
    }

}