<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Block\Adminhtml\Form\Field;

use Magento\Config\Model\Config\Source\Email\Identity;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class ScopesColumn extends Select
{
    private Identity $identitySource;

    /**
     * ScopesColumn constructor.
     *
     * @param array<string, mixed> $data
     */
    public function __construct(
        Identity $identitySource,
        Context $context,
        array $data = []
    ) {
        $this->identitySource = $identitySource;

        parent::__construct($context, $data);
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName(string $value): ScopesColumn
    {
        return $this->setData('name', $value . '[]');
    }

    /**
     * Set "id" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputId(string $value): ScopesColumn
    {
        return $this->setId($value);
    }

    /**
     * @inheritDoc
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->identitySource->toOptionArray());
        }

        $this->setData('extra_params', 'multiple');

        return parent::_toHtml();
    }
}
