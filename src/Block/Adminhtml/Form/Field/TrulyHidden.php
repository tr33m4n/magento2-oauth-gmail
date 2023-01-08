<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\Form\Field;

use Magento\Framework\Data\Form\Element\Hidden;

class TrulyHidden extends Hidden
{
    /**
     * When rendering the field, the scope label is still displayed. Set to `null` to visually hide the field entirely
     *
     * @return null
     */
    public function getScope()
    {
        return null;
    }
}
