<?php

declare(strict_types=1);

namespace tr33m4n\OauthGmail\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Filesystem;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\MediaStorage\Model\File\UploaderFactory;

class AuthFile extends File
{
    /**
     * AuthFile constructor.
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @param \Magento\Framework\Model\Context                                           $context
     * @param \Magento\Framework\Registry                                                $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface                         $config
     * @param \Magento\Framework\App\Cache\TypeListInterface                             $cacheTypeList
     * @param \Magento\MediaStorage\Model\File\UploaderFactory                           $uploaderFactory
     * @param \Magento\Config\Model\Config\Backend\File\RequestData\RequestDataInterface $requestData
     * @param \Magento\Framework\Filesystem                                              $filesystem
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null               $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null                         $resourceCollection
     * @param array<string, mixed>                                                       $data
     */
    public function __construct(
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
        // Ensure we do not save the auth JSON file in a public location
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);

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
    }

    /**
     * @inheritDoc
     */
    protected function _getAllowedExtensions(): array
    {
        return ['json'];
    }
}
