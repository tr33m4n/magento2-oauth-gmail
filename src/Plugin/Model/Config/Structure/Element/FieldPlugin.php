<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Plugin\Model\Config\Structure\Element;

use Magento\Config\Model\Config\Structure\Element\Dependency\Field as DependencyField;
use Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory;
use Magento\Config\Model\Config\Structure\Element\Field;
use tr33m4n\OauthGmail\Model\Config\Provider;

class FieldPlugin
{
    private const FILE_FIELD_PATH = 'system/oauth_gmail/auth_file';

    private const SERVICE_ACCOUNT_FLAG = 'service_account';

    private const NOT_SERVICE_ACCOUNT_FLAG = 'not_service_account';

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
     * @param array<string, \Magento\Config\Model\Config\Structure\Element\Dependency\Field> $result
     * @return array<string, \Magento\Config\Model\Config\Structure\Element\Dependency\Field>
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \tr33m4n\OauthGmail\Exception\ConfigException
     */
    public function afterGetDependencies(Field $subject, array $result): array
    {
        $snakeCasedPath = str_replace('/', '_', self::FILE_FIELD_PATH);

        $dependencyFieldObject = $result[$snakeCasedPath] ?? null;
        if (!$dependencyFieldObject instanceof DependencyField) {
            return $result;
        }

        $dependencyFieldValue = $dependencyFieldObject->getValues()[0] ?? null;
        if ($dependencyFieldValue !== self::SERVICE_ACCOUNT_FLAG
            && $dependencyFieldValue !== self::NOT_SERVICE_ACCOUNT_FLAG
        ) {
            return $result;
        }

        $result[$snakeCasedPath] = $this->fieldFactory->create([
            'fieldData' => [
                'id' => self::FILE_FIELD_PATH,
                'value' => $this->configProvider->isServiceAccount()
                    ? $this->configProvider->getAuthFile()
                    : self::INVALID_VALUE,
                '_elementType' => 'field',
                'dependPath' => explode('/', self::FILE_FIELD_PATH)
            ]
        ]);

        return $result;
    }
}
