<?php

namespace tr33m4n\OauthGmail\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Token
 *
 * @package tr33m4n\OauthGmail\Model\ResourceModel
 */
class Token extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('oauth_gmail_token', 'token_id');
    }
}
