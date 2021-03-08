<?php

namespace tr33m4n\GoogleOauthMail\Model;

use tr33m4n\GoogleOauthMail\Model\ResourceModel\Token\CollectionFactory;

/**
 * Class GetAccessToken
 *
 * @package tr33m4n\GoogleOauthMail\Model
 */
class GetAccessToken
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
     * Get access token
     *
     * @return string
     */
    public function execute() : ?string
    {
        /** @var \tr33m4n\GoogleOauthMail\Model\ResourceModel\Token\Collection $collection */
        $latestAccessToken = $this->collectionFactory->create()
            ->addFieldToSelect('token')
            ->setPageSize(1)
            ->setCurPage(1)
            ->setOrder('created_at')
            ->getFirstItem();

        return null !== $latestAccessToken->getId()
            ? $latestAccessToken->getToken()
            : null;
    }
}
