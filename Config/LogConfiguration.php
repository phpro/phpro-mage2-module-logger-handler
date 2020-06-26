<?php
declare(strict_types = 1);

namespace Phpro\LoggerHandler\Config;

interface LogConfiguration
{
    public function getLogFileName(): string;

    public function getLogLevel(): string;
}
