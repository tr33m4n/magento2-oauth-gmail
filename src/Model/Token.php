<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Magento\Framework\Model\AbstractModel;
use tr33m4n\GoogleOauthMail\Api\Data\TokenInterface;
use tr33m4n\GoogleOauthMail\Model\ResourceModel\Token as TokenResource;

/**
 * Class Token
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class Token extends AbstractModel implements TokenInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(TokenResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getToken() : string
    {
        return $this->getData(self::KEY_TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setToken(string $token) : TokenInterface
    {
        return $this->setData(self::KEY_TOKEN, $token);
    }
}
