<?php

namespace tr33m4n\OauthGmail\Block\Adminhtml\System\Config;

use InvalidArgumentException;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use tr33m4n\OauthGmail\Model\GetGoogleClient;

/**
 * Class Authenticate
 *
 * @package tr33m4n\OauthGmail\Block\Adminhtml\System\Config
 */
class Authenticate extends AbstractButton
{
    /**
     * @var \tr33m4n\OauthGmail\Model\GetGoogleClient
     */
    private $getGoogleClient;

    /**
     * Authenticate constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\GetGoogleClient         $getGoogleClient
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
    protected function _getElementHtml(AbstractElement $element) : string
    {
        try {
            $buttonUrl = $this->getGoogleClient->execute()->createAuthUrl();
        } catch (InvalidArgumentException $exception) {
            $buttonUrl = null;
        }

        $this->addData(
            [
                'button_label' => __('Authenticate'),
                'class_suffix' => 'authenticate',
                'html_id' => $element->getHtmlId(),
                'button_url' => $buttonUrl
            ]
        );

        return $this->_toHtml();
    }
}
