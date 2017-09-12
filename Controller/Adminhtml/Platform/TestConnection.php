<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Controller\Adminhtml\Platform;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Buzzi\Base\Platform\SdkFactory;
use Buzzi\Sdk;

class TestConnection extends Action
{
    const FULL_ACTION_NAME = 'buzzi_base/platform/testConnection';

    /**
     * @var \Buzzi\Base\Model\Config\Api
     */
    protected $configApi;

    /**
     * @var \Buzzi\Base\Platform\SdkFactory
     */
    protected $sdkFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Buzzi\Base\Model\Config\Api $configApi
     * @param \Buzzi\Base\Platform\SdkFactory $sdkFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Buzzi\Base\Model\Config\Api $configApi,
        \Buzzi\Base\Platform\SdkFactory $sdkFactory
    ) {
        $this->configApi = $configApi;
        $this->sdkFactory = $sdkFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $response = [
            'status' => 'fail',
            'message' => __('Invalid credentials.')
        ];

        $environment = $this->getRequest()->getParam('environment');
        $host = $this->getRequest()->getParam('host');
        $authId = $this->getRequest()->getParam('auth_id');
        $authSecret = $this->getRequest()->getParam('auth_secret');

        if (!empty($environment) && !empty($authId) && !empty($authSecret)) {
            try {
                $sdk = $this->createSdk($environment, $host, $authId, $authSecret);
                $sdk->getSupportService()->isAuthorized();
                $response = [
                    'status' => 'success',
                    'message' => __('Connected Successfully.')
                ];
            } catch (\Buzzi\Exception\HttpException $e) {
                $response['message'] = $e->getMessage();
            }
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($response);
        return $resultJson;
    }

    /**
     * Creates Buzzi Sdk object
     *
     * @param string $environment
     * @param string $host
     * @param string $authId
     * @param string $authSecret
     * @return \Buzzi\Sdk
     */
    protected function createSdk($environment, $host, $authId, $authSecret)
    {
        $sdk = $this->sdkFactory->create(
            [
                SdkFactory::CONFIG_ENVIRONMENT => $environment,
                Sdk::CONFIG_HOST => $host,
                Sdk::CONFIG_AUTH_ID => $authId,
                Sdk::CONFIG_AUTH_SECRET => $this->updateEncryptedClientSecret($authSecret),
            ]
        );
        return $sdk;
    }

    /**
     * @param string $authSecret
     * @return string
     */
    protected function updateEncryptedClientSecret($authSecret)
    {
        return $authSecret == '******' ? $this->configApi->getAuthSecret() : $authSecret;
    }
}
