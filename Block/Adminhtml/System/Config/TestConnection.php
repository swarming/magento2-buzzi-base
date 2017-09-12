<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Buzzi\Base\Controller\Adminhtml\Platform\TestConnection as ControllerTestConnection;

class TestConnection extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var string
     */
    protected $_template = 'system/config/test_connection.phtml';

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->setHtmlId($element->getHtmlId());
        return $this->_toHtml();
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()
            ->unsCanUseWebsiteValue()
            ->unsCanUseDefaultValue();
        return parent::render($element);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getButtonLabel()
    {
        return __('Test connection');
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->_urlBuilder->getUrl(ControllerTestConnection::FULL_ACTION_NAME);
    }

    /**
     * @return string
     */
    public function getWebsiteCode()
    {
        return $this->getRequest()->getParam('website', '');
    }
}
