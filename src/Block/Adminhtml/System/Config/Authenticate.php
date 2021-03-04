<?php

namespace tr33m4n\GoogleOauthMail\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use tr33m4n\GoogleOauthMail\Model\GetGoogleClient;

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
     * @var \tr33m4n\GoogleOauthMail\Model\GetGoogleClient
     */
    private $getGoogleClient;

    /**
     * Authenticate constructor.
     *
     * @param \tr33m4n\GoogleOauthMail\Model\GetGoogleClient         $getGoogleClient
     * @param \Magento\Backend\Block\Template\Context                $context
     * @param array                                                  $data
     * @param \Magento\Framework\View\Helper\SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        GetGoogleClient $getGoogleClient,
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->getGoogleClient = $getGoogleClient;

        parent::__construct($context, $data, $secureRenderer);
    }

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
                'button_url' => $this->getGoogleClient->execute()->createAuthUrl(),
            ]
        );

        return $this->_toHtml();
    }
}
