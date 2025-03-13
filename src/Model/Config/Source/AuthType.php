<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Phrase;

class AuthType implements OptionSourceInterface
{
    public const AUTH_TYPE_FILE = 'file';

    public const AUTH_TYPE_CLIENT_ID_SECRET = 'client_id_secret';

    /**
     * {@inheritdoc}
     *
     * @return array<int, array{label: Phrase, value: string}>
     */
    public function toOptionArray(): array
    {
        return [
            ['label' => __('Client ID/Secret'), 'value' => self::AUTH_TYPE_CLIENT_ID_SECRET],
            ['label' => __('File'), 'value' => self::AUTH_TYPE_FILE]
        ];
    }
}
