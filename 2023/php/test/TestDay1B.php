<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day1\b\Solution1B;
use PHPUnit\Framework\TestCase;

final class TestDay1B extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(281, Solution1B::getResult("../resources/1/test_b.txt"));
    }

    public function testResult(): void
    {
        $this->assertSame(56017, Solution1B::getResult("../resources/1/input.txt"));
    }

}