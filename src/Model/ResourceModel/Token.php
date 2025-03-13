<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Token extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('oauth_gmail_token', 'token_id');
    }
}
