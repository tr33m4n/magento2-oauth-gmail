<?php

namespace tr33m4n\OauthGmail\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class AbstractButton
 *
 * @package tr33m4n\OauthGmail\Block\Adminhtml\System\Config
 */
abstract class AbstractButton extends Field
{
    /**
     * @inheritDoc
     */
    protected $_template = 'tr33m4n_OauthGmail::system/config/button.phtml';

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element) : string
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }
}
