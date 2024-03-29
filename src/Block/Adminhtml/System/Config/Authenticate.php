<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\System\Config;

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use tr33m4n\OauthGmail\Model\Client\GetClient;

class Authenticate extends AbstractButton
{
    private GetClient $getClient;

    private Context $context;

    /**
     * Authenticate constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\Client\GetClient             $getClient
     * @param \Magento\Backend\Block\Template\Context                $context
     * @param array<int|string, mixed>                               $data
     * @param \Magento\Framework\View\Helper\SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        GetClient $getClient,
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        $this->getClient = $getClient;
        $this->context = $context;

        parent::__construct($context, $data, $secureRenderer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element) : string
    {
        try {
            $buttonUrl = $this->getClient->execute()->createAuthUrl();
        } catch (Exception $exception) {
            $this->context->getLogger()->error($exception);

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
