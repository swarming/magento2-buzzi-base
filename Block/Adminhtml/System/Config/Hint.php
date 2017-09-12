<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\Base\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Hint extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var string
     */
    protected $_template = 'system/config/hint.phtml';

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function render(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
