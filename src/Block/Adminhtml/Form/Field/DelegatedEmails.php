<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use tr33m4n\OauthGmail\Model\Config\Provider;

class DelegatedEmails extends AbstractFieldArray
{
    private ?ScopesColumn $scopesRenderer = null;

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
        $this->_addButtonLabel = (string) __('Add Email');
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
                if (!is_scalar($scope)) {
                    continue;
                }

                $options['option_' . $this->getScopesRenderer()->calcOptionHash((string) $scope)] =
                    'selected="selected"';
            }
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Get scopes renderer
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getScopesRenderer(): ScopesColumn
    {
        if (!$this->scopesRenderer instanceof ScopesColumn) {
            /** @var \tr33m4n\OauthGmail\Block\Adminhtml\Form\Field\ScopesColumn $scopesColumns */
            $scopesColumns = $this->getLayout()->createBlock(
                ScopesColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );

            $this->scopesRenderer = $scopesColumns;
        }

        return $this->scopesRenderer;
    }
}
