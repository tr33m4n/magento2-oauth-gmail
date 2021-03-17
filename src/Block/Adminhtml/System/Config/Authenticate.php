<?php

namespace tr33m4n\OauthGmail\Block\Adminhtml\System\Config;

use InvalidArgumentException;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use tr33m4n\OauthGmail\Model\Client\GetClient;

/**
 * Class Authenticate
 *
 * @package tr33m4n\OauthGmail\Block\Adminhtml\System\Config
 */
class Authenticate extends AbstractButton
{
    /**
     * @var \tr33m4n\OauthGmail\Model\Client\GetClient
     */
    private $getClient;

    /**
     * Authenticate constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\Client\GetClient             $getClient
     * @param \Magento\Backend\Block\Template\Context                $context
     * @param array                                                  $data
     * @param \Magento\Framework\View\Helper\SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        GetClient $getClient,
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->getClient = $getClient;

        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Google\Exception
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element) : string
    {
        try {
            $buttonUrl = $this->getClient->execute()->createAuthUrl();
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
