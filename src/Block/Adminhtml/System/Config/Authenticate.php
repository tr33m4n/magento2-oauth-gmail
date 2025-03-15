<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\System\Config;

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use tr33m4n\OauthGmail\Api\GmailClientInterface;

class Authenticate extends AbstractButton
{
    /**
     * Authenticate constructor.
     *
     * @param array<int|string, mixed> $data
     */
    public function __construct(
        private readonly GmailClientInterface $gmailClient,
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        try {
            $buttonUrl = $this->gmailClient->getAuthUrl();
        } catch (Exception $exception) {
            $this->_logger->error($exception);

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
