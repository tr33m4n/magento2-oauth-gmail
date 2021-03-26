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
        $element->unsetData('scope')->unsetData('can_use_website_value')->unsetData('can_use_default_value');

        return parent::render($element);
    }
}
