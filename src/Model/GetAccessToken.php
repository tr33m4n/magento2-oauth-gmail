<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Model\ResourceModel\Token\CollectionFactory;

class GetAccessToken
{
    /**
     * GetAccessToken constructor.
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory
    ) {
    }

    /**
     * Get access token
     */
    public function execute(): ?TokenInterface
    {
        /** @var \tr33m4n\OauthGmail\Api\Data\TokenInterface $latestAccessToken */
        $latestAccessToken = $this->collectionFactory->create()
            ->setPageSize(1)
            ->setCurPage(1)
            ->setOrder(TokenInterface::KEY_CREATED)
            ->getFirstItem();

        return null !== $latestAccessToken->getId()
            ? $latestAccessToken
            : null;
    }
}
