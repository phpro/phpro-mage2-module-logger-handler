<?php

namespace Phpro\LoggerHandler\Logger;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;
use Monolog\Processor\PsrLogMessageProcessor;
use Phpro\LoggerHandler\Config\LogConfiguration;

/**
 * Override Base Logger Handler to make log file name and log level configurable
 */
class Handler extends Base
{
    public function __construct(
        LogConfiguration $config,
        DriverInterface $filesystem,
        string $filePath = null
    ) {
        $fileName = $config->getLogFileName();
        $this->loggerType = $config->getLogLevel();
        $this->pushProcessor(new PsrLogMessageProcessor()); // @codingStandardsIgnoreLine

        parent::__construct($filesystem, $filePath, $fileName);
    }
}
