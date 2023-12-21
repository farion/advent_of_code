<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day20\b\Solution20B;
use PHPUnit\Framework\TestCase;
use utils\TestLogger;

final class TestDay20B extends TestCase
{
    public function testResult(): void
    {
        $this->assertSame(246006621493687, Solution20B::getResult("../resources/20/input.txt", TestLogger::createTestLogger()));
    }

}