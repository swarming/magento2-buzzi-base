<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Platform;

use Buzzi\Sdk;
use Buzzi\Base\Model\Config\Source\Environment;

class SdkFactory
{
    const CONFIG_STORE = 'store';
    const CONFIG_ENVIRONMENT = 'environment';

    /**
     * @var \Buzzi\Base\Model\Config\Api
     */
    protected $configApi;

    /**
     * @param \Buzzi\Base\Model\Config\Api $configApi
     */
    public function __construct(
        \Buzzi\Base\Model\Config\Api $configApi
    ) {
        $this->configApi = $configApi;
    }

    /**
     * $config = [
     *     SdkFactory::CONFIG_STORE,
     *     SdkFactory::CONFIG_ENVIRONMENT,
     *     Sdk::CONFIG_HOST,
     *     Sdk::CONFIG_AUTH_ID,
     *     Sdk::CONFIG_AUTH_SECRET,
     *     Sdk::CONFIG_DEBUG,
     *     Sdk::CONFIG_LOG_FILE_NAME,
     * ]
     *
     * @param string[] $config
     * @return \Buzzi\Sdk
     */
    public function create(array $config = [])
    {
        $store = !empty($config[self::CONFIG_STORE]) ? $config[self::CONFIG_STORE] : null;
        unset($config[self::CONFIG_STORE]);

        $environment = !empty($config[self::CONFIG_ENVIRONMENT])
            ? $config[self::CONFIG_ENVIRONMENT]
            : $this->configApi->getEnvironment($store);
        $isSandbox = $environment == Environment::SANDBOX;
        unset($config[self::CONFIG_ENVIRONMENT]);

        $sdkConfig = array_merge(
            [
                Sdk::CONFIG_HOST => $this->configApi->getHost($store),
                Sdk::CONFIG_AUTH_ID => $this->configApi->getAuthId($store),
                Sdk::CONFIG_AUTH_SECRET => $this->configApi->getAuthSecret($store),
                Sdk::CONFIG_SANDBOX => $isSandbox,
                Sdk::CONFIG_DEBUG => $this->configApi->isDebug($store),
                Sdk::CONFIG_LOG_FILE_NAME => $this->configApi->getLogFilename()
            ],
            $config
        );

        return new Sdk($sdkConfig);
    }
}
