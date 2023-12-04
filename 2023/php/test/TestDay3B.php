<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day3\b\Solution3B;
use PHPUnit\Framework\TestCase;

final class TestDay3B extends TestCase
{
    public function testExample(): void
    {
        $this->assertSame(467835, Solution3B::getResult("../resources/3/test_b.txt"));
    }

    public function testResult(): void
    {
        $this->assertSame(78826761, Solution3B::getResult("../resources/3/input.txt"));
    }

}