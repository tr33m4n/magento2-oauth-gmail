<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use tr33m4n\OauthGmail\Model\Config\Provider;

class SendTest extends AbstractButton
{
    /**
     * SendTest constructor.
     *
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly Provider $configProvider,
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct(
            $context,
            $data,
            $secureRenderer
        );
    }

    /**
     * @inheritDoc
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $this->addData(
            [
                'button_label' => __('Send Test'),
                'class_suffix' => 'send-test',
                'html_id' => $element->getHtmlId(),
                'button_url' => $this->configProvider->hasAuthCredentials()
                    ? $this->getUrl('oauthgmail/email/test')
                    : null
            ]
        );

        return $this->_toHtml();
    }
}
