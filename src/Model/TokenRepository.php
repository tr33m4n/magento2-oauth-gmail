<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Api\TokenRepositoryInterface;
use tr33m4n\OauthGmail\Model\ResourceModel\Token as TokenResource;
use tr33m4n\OauthGmail\Model\ResourceModel\Token\CollectionFactory;

class TokenRepository implements TokenRepositoryInterface
{
    /**
     * TokenRepository constructor.
     */
    public function __construct(
        private readonly TokenResource $tokenResource,
        private readonly CollectionFactory $collectionFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getLatest(): ?TokenInterface
    {
        /** @var \tr33m4n\OauthGmail\Model\Token $latestAccessToken */
        $latestAccessToken = $this->collectionFactory->create()
            ->setPageSize(1)
            ->setCurPage(1)
            ->setOrder(TokenInterface::KEY_CREATED)
            ->getFirstItem();

        return null !== $latestAccessToken->getId()
            ? $latestAccessToken
            : null;
    }

    /**
     * @inheritDoc
     */
    public function save(TokenInterface $token): TokenInterface
    {
        try {
            /** @var \tr33m4n\OauthGmail\Model\Token $token */
            $this->tokenResource->save($token);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__('Unable to save the access token'), $exception);
        }

        return $token;
    }
}
