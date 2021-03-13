<?php

namespace tr33m4n\GoogleOauthMail\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Authenticate
 *
 * @package tr33m4n\GoogleOauthMail\Block\Adminhtml\System\Config
 */
class SendTest extends Field
{
    /**
     * @inheritDoc
     */
    protected $_template = 'tr33m4n_GoogleOauthMail::system/config/send-test.phtml';

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element) : string
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element) : string
    {
        $this->addData(
            [
                'button_label' => __('Send Test'),
                'html_id' => $element->getHtmlId(),
                'button_url' => $this->_urlBuilder->getUrl('google-oauth-mail/email/test')
            ]
        );

        return $this->_toHtml();
    }
}
