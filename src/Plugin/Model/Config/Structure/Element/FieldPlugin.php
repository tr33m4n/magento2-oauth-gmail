<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Plugin\Model\Config\Structure\Element;

use Magento\Config\Model\Config\Structure\Element\Field;

class FieldPlugin
{
    /**
     * @param \Magento\Config\Model\Config\Structure\Element\Field              $subject
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\Field[] $result
     * @return \Magento\Config\Model\Config\Structure\Element\Dependency\Field[]
     */
    public function afterGetDependencies(Field $subject, array $result): array
    {
        return $result;
    }
}
