<?php

namespace tr33m4n\GoogleOauthMail\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Authenticate
 *
 * @package tr33m4n\GoogleOauthMail\Block\Adminhtml\System\Config
 */
class Authenticate extends Field
{
    /**
     * @inheritDoc
     */
    protected $_template = 'tr33m4n_GoogleOauthMail::system/config/authenticate.phtml';

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
        $originalData = $element->getOriginalData();
        $buttonLabel = !empty($originalData['button_label']) ? $originalData['button_label'] : 'Authenticate';
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl('google_oauth/system_config_authenticate/authenticate'),
            ]
        );

        return $this->_toHtml();
    }
}
