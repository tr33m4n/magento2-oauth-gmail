<?php

namespace tr33m4n\OauthGoogleMail\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Token
 *
 * @package tr33m4n\OauthGoogleMail\Model\ResourceModel
 */
class Token extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('google_oauth_mail', 'token_id');
    }
}
