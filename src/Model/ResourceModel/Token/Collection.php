<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\ResourceModel\Token;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use tr33m4n\OauthGmail\Model\ResourceModel\Token as TokenResource;
use tr33m4n\OauthGmail\Model\Token;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct() : void
    {
        $this->_init(Token::class, TokenResource::class);
    }
}
