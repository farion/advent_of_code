<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day19\a\Solution19A;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay19A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(19114, Solution19A::getResult("../resources/19/test_a.txt", TestLogger::createTestLogger()));
    }

    public function testResult(): void
    {
        $this->assertSame(476889, Solution19A::getResult("../resources/19/input.txt", TestLogger::createTestLogger()));
    }

}