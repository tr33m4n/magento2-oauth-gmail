<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class AuthType implements OptionSourceInterface
{
    public const AUTH_TYPE_FILE = 'file';

    public const AUTH_TYPE_CLIENT_ID_SECRET = 'client_id_secret';

    /**
     * {@inheritdoc}
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['label' => __('Client ID/Secret'), 'value' => self::AUTH_TYPE_CLIENT_ID_SECRET],
            ['label' => __('File'), 'value' => self::AUTH_TYPE_FILE]
        ];
    }
}
