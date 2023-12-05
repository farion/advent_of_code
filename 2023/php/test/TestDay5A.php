<?php declare(strict_types=1);

$loader = require __DIR__ . '/../vendor/autoload.php';

use day5\a\Solution5A;
use PHPUnit\Framework\TestCase;

final class TestDay5A extends TestCase
{

    public function testExample(): void
    {
        $this->assertSame(13, Solution5A::getResult("../resources/4/test_a.txt"));
    }

    public function testResult(): void
    {
        $this->assertSame(23235, Solution5A::getResult("../resources/4/input.txt"));
    }

}