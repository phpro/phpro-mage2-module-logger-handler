![](https://github.com/phpro/phpro-mage2-module-logger-handler/workflows/.github/workflows/grumphp.yml/badge.svg)

# Logger Handler for Magento 2

This module allows you to easily configure custom log files. Especially useful when building several integrations, each requiring a separate log file.

## Installation

`composer require phpro/mage2-module-logger-handler`

## How to use

This module is only a basic building block. You can build on top of this to create custom log files in your projects. 
Below you can find an example implementation.

### Stores configuration

To use a custom log file, you can add the following to the system config. This module also provides a source model for the log levels.

    <!-- system.xml -->
    <?xml version="1.0"?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
        <system>
            <section id="module" sortOrder="10" showInDefault="1" showInWebsite="1" showInStore="1">
                <group id="log_type" translate="label" sortOrder="20" showInDefault="1">
                    <field id="log_file_name" translate="label" type="text" sortOrder="10" showInDefault="1">
                        <label>Log File Name</label>
                    </field>
                    <field id="log_level" translate="label" type="select" sortOrder="20" showInDefault="1">
                        <label>Log Level</label>
                        <source_model>Phpro\LoggerHandler\Config\LogLevelsSource</source_model> <!-- Custom source model which is available in this module -->
                    </field>
                </group>
            </section>
        </system>
    </config>


### Configuration class

You will need to create a Configuration class which implements the LogConfiguration interface. This Configuration class will be used by the Logger Handler.

This is an example of a Configuration class which uses the stores configuration defined above.

    <?php
    
    namespace Vendor\Module\Config;
    
    use Phpro\LoggerHandler\Config\LogConfiguration;
    use Magento\Framework\App\Config\ScopeConfigInterface;
    
    class SystemConfiguration implements LogConfiguration
    {
        const XML_LOG_FILE_NAME = 'module/log_type/log_file_name';
        const XML_LOG_LEVEL = 'module/log_type/log_level';
        const LOG_DIR = 'var' . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR;
    
        /**
         * @var ScopeConfigInterface
         */
        private $config;
    
        public function __construct(ScopeConfigInterface $config)
        {
            $this->config = $config;
        }
    
        /**
         * This function should return the full path to the log file starting from the magento root
         */
        public function getLogFileName(): string
        {
            return self::LOG_DIR . $this->config->getValue(self::XML_LOG_FILE_NAME);
        }
    
        public function getLogLevel(): string
        {
            return $this->config->getValue(self::XML_LOG_LEVEL);
        }
    }


### Virtual Types

You will need to create the following Virtual Types to use a custom logger in a service class.

    <!-- di.xml -->
    <?xml version="1.0" ?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
        <virtualType name="[vendor_module_logtype]_logger_handler" type="Phpro\LoggerHandler\Logger\Handler">
            <arguments>
                <argument name="config" xsi:type="object">Vendor\Module\Config\SystemConfiguration</argument> <!-- Configuration class created above -->
            </arguments>
        </virtualType>
        <virtualType name="[vendor_module_logtype]_logger" type="Monolog\Logger">
            <arguments>
                <argument name="name" xsi:type="string">[module-logtype]-logger</argument> <!-- channel name; will also show in log files -->
                <argument name="handlers" xsi:type="array">
                    <item name="stream" xsi:type="object">[vendor_module_logtype]_logger_handler</item> <!-- refers to the logger handler VirtualType -->
                </argument>
            </arguments>
        </virtualType>
        
        <!-- inject custom logger in service class -->
        <type name="Vendor\Module\Service\DoSomething">
            <arguments>
                <argument name="logger" xsi:type="object">[vendor_module_logtype]_logger</argument> <!-- refers to the logger VirtualType -->
            </arguments>
        </type>
    </config>
