<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day6\a\Solution6A;
use PHPUnit\Framework\TestCase;

final class TestDay6A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(288, Solution6A::getResult("../resources/6/test_a.txt"));
    }

    public function testResult(): void
    {
        $this->assertSame(800280, Solution6A::getResult("../resources/6/input.txt"));
    }

}