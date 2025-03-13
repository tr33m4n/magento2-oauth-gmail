<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\BlockInterface;
use tr33m4n\OauthGmail\Model\Config\Provider;

class ImpersonatedEmails extends AbstractFieldArray
{
    private ?BlockInterface $scopesRenderer = null;

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(
            Provider::EMAIL_KEY,
            [
                'label' => __('Email'),
                'class' => 'required-entry validate-email'
            ]
        );

        $this->addColumn(
            Provider::SCOPES_KEY,
            [
                'label' => __('Scopes'),
                'renderer' => $this->getScopesRenderer()
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Email');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $scopes = $row->getData('scopes');
        if (is_array($scopes)) {
            foreach ($scopes as $scope) {
                $options['option_' . $this->getScopesRenderer()->calcOptionHash($scope)] = 'selected="selected"';
            }
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Get scopes renderer
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getScopesRenderer(): BlockInterface
    {
        if (null === $this->scopesRenderer) {
            $this->scopesRenderer = $this->getLayout()->createBlock(
                ScopesColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->scopesRenderer;
    }
}
