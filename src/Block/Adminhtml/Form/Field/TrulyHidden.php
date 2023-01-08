<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\Form\Field;

use Magento\Framework\Data\Form\Element\Hidden;

class TrulyHidden extends Hidden
{
    /**
     * @inheritDoc
     */
    public function getDefaultHtml(): string
    {
        return '';
    }
}
