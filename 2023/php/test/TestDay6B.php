<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day6\b\Solution6B;
use PHPUnit\Framework\TestCase;

final class TestDay6B extends TestCase
{

    public function testExample(): void
    {
        $this->assertEqualsWithDelta(71503, Solution6B::getResult("../resources/6/test_b.txt"), 0);
    }

    public function testResult(): void
    {
        $this->assertEqualsWithDelta(45128024, Solution6B::getResult("../resources/6/input.txt"), 0);
    }

}