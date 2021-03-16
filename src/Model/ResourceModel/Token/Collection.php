<?php

namespace tr33m4n\OauthGmail\Model\ResourceModel\Token;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use tr33m4n\OauthGmail\Model\Token;
use tr33m4n\OauthGmail\Model\ResourceModel\Token as TokenResource;

/**
 * Class Collection
 *
 * @package tr33m4n\OauthGmail\Model\ResourceModel\Token
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(Token::class, TokenResource::class);
    }
}
