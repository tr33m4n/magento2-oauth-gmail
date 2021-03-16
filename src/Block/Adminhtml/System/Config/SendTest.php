<?php

namespace tr33m4n\OauthGmail\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Authenticate
 *
 * @package tr33m4n\OauthGmail\Block\Adminhtml\System\Config
 */
class SendTest extends AbstractButton
{
    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element) : string
    {
        $this->addData(
            [
                'button_label' => __('Send Test'),
                'class_suffix' => 'send-test',
                'html_id' => $element->getHtmlId(),
                'button_url' => $this->getUrl('oauthgmail/email/test')
            ]
        );

        return $this->_toHtml();
    }
}
