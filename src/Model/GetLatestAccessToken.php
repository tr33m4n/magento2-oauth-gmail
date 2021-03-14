<?php

namespace tr33m4n\GoogleOauthMail\Model;

use tr33m4n\GoogleOauthMail\Api\Data\TokenInterface;
use tr33m4n\GoogleOauthMail\Model\ResourceModel\Token\CollectionFactory;

/**
 * Class GetLatestAccessToken
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class GetLatestAccessToken
{
    /**
     * @var \tr33m4n\GoogleOauthMail\Model\ResourceModel\Token\CollectionFactory
     */
    private $collectionFactory;

    /**
     * GetAccessToken constructor.
     *
     * @param \tr33m4n\GoogleOauthMail\Model\ResourceModel\Token\CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get latest access token
     *
     * @return \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface|null
     */
    public function execute() : ?TokenInterface
    {
        /** @var \tr33m4n\GoogleOauthMail\Api\Data\TokenInterface $latestAccessToken */
        $latestAccessToken = $this->collectionFactory->create()
            ->addFieldToSelect(TokenInterface::KEY_ACCESS_TOKEN)
            ->setPageSize(1)
            ->setCurPage(1)
            ->setOrder(TokenInterface::KEY_CREATED)
            ->getFirstItem();

        return null !== $latestAccessToken->getId()
            ? $latestAccessToken
            : null;
    }
}
