<?php
declare(strict_types = 1);

namespace Phpro\LoggerHandler\Config;

use Magento\Framework\Data\OptionSourceInterface;
use Psr\Log\LogLevel;

class LogLevelsSource implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'value' => LogLevel::EMERGENCY,
                'label' => LogLevel::EMERGENCY
            ],
            [
                'value' => LogLevel::ALERT,
                'label' => LogLevel::ALERT
            ],
            [
                'value' => LogLevel::CRITICAL,
                'label' => LogLevel::CRITICAL
            ],
            [
                'value' => LogLevel::ERROR,
                'label' => LogLevel::ERROR
            ],
            [
                'value' => LogLevel::WARNING,
                'label' => LogLevel::WARNING
            ],
            [
                'value' => LogLevel::NOTICE,
                'label' => LogLevel::NOTICE
            ],
            [
                'value' => LogLevel::INFO,
                'label' => LogLevel::INFO
            ],
            [
                'value' => LogLevel::DEBUG,
                'label' => LogLevel::DEBUG
            ]
        ];
    }
}
