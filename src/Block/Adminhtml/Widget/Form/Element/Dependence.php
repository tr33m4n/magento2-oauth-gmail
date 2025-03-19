<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\Widget\Form\Element;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Form\Element\Dependence as DefaultDependence;

class Dependence extends DefaultDependence
{
    private const JS_BLOCK_ALIAS = 'tr33m4n.config.edit.js';

    /**
     * {@inheritdoc}
     *
     * Due to how the parent block `_toHtml` calls a new instance of the `FormElementDependenceController` class in the
     * DOM rather than through typical require JS inheritance, we cannot add a mixin to `mage/adminhtml/form` and
     * expect things to load predictably. Instead, render our extension JS before the class call, ensuring the
     * extension exists before performing any function
     *
     * @param string $html
     */
    protected function _afterToHtml($html): string
    {
        $this->addChild(
            self::JS_BLOCK_ALIAS,
            Template::class,
            ['template' => 'tr33m4n_OauthGmail::system/config/form-js.phtml']
        );

        return $this->getChildHtml(self::JS_BLOCK_ALIAS) . parent::_afterToHtml($html);
    }
}
