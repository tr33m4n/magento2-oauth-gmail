<?php
declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model;

use tr33m4n\OauthGmail\Api\Data\TokenInterface;
use tr33m4n\OauthGmail\Model\ResourceModel\Token\CollectionFactory;

class GetAccessToken
{
    private CollectionFactory $collectionFactory;

    /**
     * GetAccessToken constructor.
     *
     * @param \tr33m4n\OauthGmail\Model\ResourceModel\Token\CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get access token
     *
     * @return \tr33m4n\OauthGmail\Api\Data\TokenInterface|null
     */
    public function execute() : ?TokenInterface
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
