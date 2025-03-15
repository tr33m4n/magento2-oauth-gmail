<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Magento\Framework\Model\AbstractModel;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Model\ResourceModel\Token as TokenResource;

class Token extends AbstractModel implements TokenInterface
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(TokenResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken(): ?string
    {
        $accessToken = $this->getData(self::KEY_ACCESS_TOKEN);
        if (!is_string($accessToken)) {
            return null;
        }

        return $accessToken;
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken(string $accessToken): TokenInterface
    {
        return $this->setData(self::KEY_ACCESS_TOKEN, $accessToken);
    }

    /**
     * @inheritDoc
     */
    public function getRefreshToken(): ?string
    {
        $refreshToken = $this->getData(self::KEY_REFRESH_TOKEN);
        if (!is_string($refreshToken)) {
            return null;
        }

        return $refreshToken;
    }

    /**
     * @inheritDoc
     */
    public function setRefreshToken(string $refreshToken): TokenInterface
    {
        return $this->setData(self::KEY_REFRESH_TOKEN, $refreshToken);
    }

    /**
     * @inheritDoc
     */
    public function getTokenType(): ?string
    {
        $tokenType = $this->getData(self::KEY_TOKEN_TYPE);
        if (!is_string($tokenType)) {
            return null;
        }

        return $tokenType;
    }

    /**
     * @inheritDoc
     */
    public function setTokenType(string $tokenType): TokenInterface
    {
        return $this->setData(self::KEY_TOKEN_TYPE, $tokenType);
    }

    /**
     * @inheritDoc
     */
    public function getScope(): ?string
    {
        $scope = $this->getData(self::KEY_SCOPE);
        if (!is_string($scope)) {
            return null;
        }

        return $scope;
    }

    /**
     * @inheritDoc
     */
    public function setScope(string $scope): TokenInterface
    {
        return $this->setData(self::KEY_SCOPE, $scope);
    }

    /**
     * @inheritDoc
     */
    public function getExpiresIn(): ?int
    {
        $expiresIn = $this->getData(self::KEY_EXPIRES_IN);
        if (!is_int($expiresIn)) {
            return null;
        }

        return $expiresIn;
    }

    /**
     * @inheritDoc
     */
    public function setExpiresIn(int $expiresIn): TokenInterface
    {
        return $this->setData(self::KEY_EXPIRES_IN, $expiresIn);
    }

    /**
     * @inheritDoc
     */
    public function getCreated(): ?int
    {
        $created = $this->getData(self::KEY_CREATED);
        if (!is_int($created)) {
            return null;
        }

        return $created;
    }

    /**
     * @inheritDoc
     */
    public function setCreated(int $created): TokenInterface
    {
        return $this->setData(self::KEY_CREATED, $created);
    }
}
