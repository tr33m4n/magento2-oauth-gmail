<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Plugin\Model\Config\Structure\Element;

use Magento\Config\Model\Config\Structure\Element\Dependency\Field as DependencyField;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;
use Magento\Config\Model\Config\Structure\Element\Field;
use tr33m4n\OauthGmail\Model\Config\Provider;

class FieldPlugin
{
    private const FILE_FIELD_PATH = 'system_oauth_gmail_auth_file';

    private const INVALID_VALUE = '__INVALID__';

    /**
     * FieldPlugin constructor.
     */
    public function __construct(
        private readonly Provider $configProvider,
        private readonly FieldFactory $fieldFactory
    ) {
    }

    /**
     * Ensure that we only show fields if the auth file is a service account
     *
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @throws \ReflectionException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\Field[] $result
     * @return \Magento\Config\Model\Config\Structure\Element\Dependency\Field[]
     */
    public function afterGetDependencies(Field $subject, array $result): array
    {
        if (!array_key_exists(self::FILE_FIELD_PATH, $result)) {
            return $result;
        }

        $dependencyFieldObject = $result[self::FILE_FIELD_PATH];
        if (!$dependencyFieldObject instanceof DependencyField) {
            return $result;
        }

        $negate = !filter_var($dependencyFieldObject->getValues()[0] ?? null, FILTER_VALIDATE_BOOLEAN);

        /**
         * The simplest solution is to change the objects property with reflection, rather than recreate the params that
         * originally created the immutable class
         */
        $refProperty = new ReflectionProperty(get_class($result[self::FILE_FIELD_PATH]), '_values');
        $refProperty->setAccessible(true);
        $refProperty->setValue(
            $result[self::FILE_FIELD_PATH],
            [$this->configProvider->isServiceAccount() ? $this->configProvider->getAuthFile() : self::INVALID_VALUE]
        );

        if ($negate) {
            $refProperty = new ReflectionProperty(get_class($result[self::FILE_FIELD_PATH]), '_isNegative');
            $refProperty->setAccessible(true);
            $refProperty->setValue($result[self::FILE_FIELD_PATH], true);
        }

        return $result;
    }
}
