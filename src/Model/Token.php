<?php

namespace tr33m4n\GoogleOauthMail\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
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
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;

    /**
     * Token constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime                  $dateTime
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        DateTime $dateTime,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->dateTime = $dateTime;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

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
    public function getAccessToken() : string
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
    public function getScope() : string
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
    public function getExpiresIn() : string
    {
        return $this->getData(self::KEY_EXPIRES_IN);
    }

    /**
     * @inheritDoc
     */
    public function setExpiresIn(int $expiresIn) : TokenInterface
    {
        return $this->setData(self::KEY_EXPIRES_IN, $this->dateTime->gmtDate(null, time() + $expiresIn));
    }

    /**
     * @inheritDoc
     */
    public function beforeSave() : AbstractModel
    {
        if ($this->isObjectNew() && !$this->getCreatedAt()) {
            $this->setCreatedAt($this->dateTime->gmtDate());
        }

        return parent::beforeSave();
    }
}
