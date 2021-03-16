<?php

namespace tr33m4n\OauthGoogleMail\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use tr33m4n\OauthGoogleMail\Api\Data\TokenInterface;
use tr33m4n\OauthGoogleMail\Model\ResourceModel\Token as TokenResource;

/**
 * Class Token
 *
 * @package tr33m4n\OauthGoogleMail\Model
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
    public function getAccessToken() : ?string
    {
        return $this->getData(self::KEY_ACCESS_TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken(string $accessToken) : TokenInterface
    {
        return $this->setData(self::KEY_ACCESS_TOKEN, $accessToken);
    }

    /**
     * @inheritDoc
     */
    public function getRefreshToken() : ?string
    {
        return $this->getData(self::KEY_REFRESH_TOKEN);
    }

    /**
     * @inheritDoc
     */
    public function setRefreshToken(string $refreshToken) : TokenInterface
    {
        return $this->setData(self::KEY_REFRESH_TOKEN, $refreshToken);
    }

    /**
     * @inheritDoc
     */
    public function getTokenType() : ?string
    {
        return $this->getData(self::KEY_TOKEN_TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setTokenType(string $tokenType) : TokenInterface
    {
        return $this->setData(self::KEY_TOKEN_TYPE, $tokenType);
    }

    /**
     * @inheritDoc
     */
    public function getScope() : ?string
    {
        return $this->getData(self::KEY_SCOPE);
    }

    /**
     * @inheritDoc
     */
    public function setScope(string $scope) : TokenInterface
    {
        return $this->setData(self::KEY_SCOPE, $scope);
    }

    /**
     * @inheritDoc
     */
    public function getExpiresIn() : ?int
    {
        return $this->getData(self::KEY_EXPIRES_IN);
    }

    /**
     * @inheritDoc
     */
    public function setExpiresIn(int $expiresIn) : TokenInterface
    {
        return $this->setData(self::KEY_EXPIRES_IN, $expiresIn);
    }

    /**
     * @inheritDoc
     */
    public function getCreated() : ?int
    {
        return $this->getData(self::KEY_CREATED);
    }

    /**
     * @inheritDoc
     */
    public function setCreated(int $created) : TokenInterface
    {
        return $this->setData(self::KEY_CREATED, $created);
    }
}
