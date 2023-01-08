<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Config\Backend;

use InvalidArgumentException;
use Magento\Config\Model\Config\Backend\File;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use tr33m4n\OauthGmail\Model\Config\Provider;

class AuthFile extends File
{
    private const SERVICE_ACCOUNT_VALUE = 'service_account';

    private WriterInterface $configWriter;

    private SerializerInterface $serializer;

    /**
     * AuthFile constructor.
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @param array<string, mixed> $data
     */
    public function __construct(
        WriterInterface $configWriter,
        SerializerInterface $serializer,
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        UploaderFactory $uploaderFactory,
        File\RequestData\RequestDataInterface $requestData,
        Filesystem $filesystem, AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $uploaderFactory,
            $requestData,
            $filesystem,
            $resource,
            $resourceCollection,
            $data
        );

        $this->configWriter = $configWriter;
        $this->serializer = $serializer;
        // Ensure we do not save the auth JSON file in a public location
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    /**
     * @inheritDoc
     */
    public function beforeSave(): AuthFile
    {
        parent::beforeSave();

        $this->configWriter->save(Provider::XML_CONFIG_IS_SERVICE_ACCOUNT, $this->isServiceAccount());

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function _getAllowedExtensions(): array
    {
        return ['json'];
    }

    /**
     * Check if service account
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function isServiceAccount(): bool
    {
        if (!is_string($this->getValue())) {
            return false;
        }

        $filePath = sprintf(
            '%s%s%s',
            $this->_getUploadDir(),
            DIRECTORY_SEPARATOR,
            // Extract actual filename without scope info
            str_replace($this->_prependScopeInfo(''), '', $this->getValue())
        );

        if (!$this->_mediaDirectory->isExist($filePath)) {
            return false;
        }

        try {
            $authFileData = $this->serializer->unserialize($this->_mediaDirectory->readFile($filePath));
        } catch (InvalidArgumentException $invalidArgumentException) {
            return false;
        }

        return ($authFileData['type'] ?? null) === self::SERVICE_ACCOUNT_VALUE;
    }
}
