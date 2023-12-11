<?php

namespace utils;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class TestLogger
{

    public static function createTestLogger(): Logger
    {
        $logger = new Logger("AoC2023Test");
        $stream_handler = new StreamHandler("php://stdout", Level::Info);
        $logger->pushHandler($stream_handler);
        return $logger;
    }
}