<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Plugin\Model\Config\Structure\Element;

use Magento\Config\Model\Config\Structure\Element\Dependency\Field as DependencyField;
use Magento\Config\Model\Config\Structure\Element\Field;
use ReflectionProperty;
use tr33m4n\OauthGmail\Model\Config\Provider;

class FieldPlugin
{
    private const FILE_FIELD_PATH = 'system_oauth_gmail_auth_file';

    private Provider $configProvider;

    /**
     * FieldPlugin constructor.
     */
    public function __construct(
        Provider $configProvider
    ) {
        $this->configProvider = $configProvider;
    }

    /**
     * Ensure that we only show fields if the auth file is a service account
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     * @throws \ReflectionException
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\Field[] $result
     * @return \Magento\Config\Model\Config\Structure\Element\Dependency\Field[]
     */
    public function afterGetDependencies(Field $subject, array $result): array
    {
        if (!array_key_exists(self::FILE_FIELD_PATH, $result) || !$this->configProvider->isServiceAccount()) {
            return $result;
        }

        if (!$result[self::FILE_FIELD_PATH] instanceof DependencyField) {
            return $result;
        }

        /**
         * The simplest solution is to change the objects property with reflection, rather than recreate the params that
         * originally created the immutable class
         */
        $refProperty = new ReflectionProperty(get_class($result[self::FILE_FIELD_PATH]), '_values');

        $refProperty->setAccessible(true);
        $refProperty->setValue($result[self::FILE_FIELD_PATH], [$this->configProvider->getAuthFile()]);

        return $result;
    }
}
