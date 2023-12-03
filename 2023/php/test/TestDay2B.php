<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day2\b\Solution2B;
use PHPUnit\Framework\TestCase;

final class TestDay2B extends TestCase
{
    public function testExample(): void
    {
        $this->assertSame(2286, Solution2B::getResult("../resources/2/test_b.txt"));
    }

    public function testResult(): void
    {
        $this->assertSame(68638, Solution2B::getResult("../resources/2/input.txt"));
    }

}