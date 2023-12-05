<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day5\b\Solution5B;
use PHPUnit\Framework\TestCase;

final class TestDay5B extends TestCase
{
    public function testExample(): void
    {
        $this->assertSame(30, Solution5B::getResult("../resources/4/test_b.txt"));
    }

    public function testResult(): void
    {
        $this->assertSame(5920640, Solution5B::getResult("../resources/4/input.txt"));
    }

}