<?php

declare(strict_types=1);

namespace ICTECHProductReviewWithRealUploadedImageVideo\Util;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class MediaFolder
{
    private const FOLDER_NAME = 'Product Review Media';
    private const ENTITY_NAME = 'product_review';

    private EntityRepository $mediaFolderRepository;
    private EntityRepository $mediaDefaultFolderRepository;
    private ?LoggerInterface $logger;

    public function __construct(
        EntityRepository $mediaFolderRepository,
        EntityRepository $mediaDefaultFolderRepository,
        ?LoggerInterface $logger = null
    ) {
        $this->mediaFolderRepository = $mediaFolderRepository;
        $this->mediaDefaultFolderRepository = $mediaDefaultFolderRepository;
        $this->logger = $logger;
    }

    public function installMedia(Context $context): void
    {
        $this->createMediaFolderContent($context);
    }

    public function uninstallMedia(Context $context): void
    {
        $this->deleteMediaFolderContent($context);
    }

    private function createMediaFolderContent(Context $context): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::FOLDER_NAME));

        $existing = $this->mediaFolderRepository->searchIds($criteria, $context)->getIds();

        if (!empty($existing)) {
            return; // Folder already exists
        }

        $defaultFolderId = $this->createReviewMediaFolder($context);
        $mediaFolderId = Uuid::randomHex();

        $mediaFolder = [
            [
                'id' => $mediaFolderId,
                'name' => self::FOLDER_NAME,
                'defaultFolderId' => $defaultFolderId,
                'child_count' => 0,
                'configuration' => [
                    'id' => Uuid::randomHex(),
                    'createThumbnails' => true,
                    'keepAspectRatio' => true,
                    'thumbnailQuality' => 80,
                ],
            ]
        ];

        try {
            $this->mediaFolderRepository->create($mediaFolder, $context);
        } catch (UniqueConstraintViolationException $exception) {
            $this->log('Media folder creation failed (already exists): ' . $exception->getMessage());
        }
    }

    private function createReviewMediaFolder(Context $context): string
    {
        $mediaDefaultFolderId = Uuid::randomHex();

        $mediaDefaultFolder = [
            [
                'id' => $mediaDefaultFolderId,
                'associationFields' => ['media'],
                'entity' => self::ENTITY_NAME,
            ]
        ];

        try {
            $this->mediaDefaultFolderRepository->create($mediaDefaultFolder, $context);
        } catch (UniqueConstraintViolationException $exception) {
            $this->log('Default folder creation failed (already exists): ' . $exception->getMessage());
        }

        return $mediaDefaultFolderId;
    }

    private function deleteMediaFolderContent(Context $context): void
    {
        // Get media folder ID
        $mediaFolderCriteria = new Criteria();
        $mediaFolderCriteria->addFilter(new EqualsFilter('name', self::FOLDER_NAME));

        $mediaFolder = $this->mediaFolderRepository->search($mediaFolderCriteria, $context)->first();
        $mediaFolderId = $mediaFolder?->getId();

        // Get default folder ID
        $defaultFolderCriteria = new Criteria();
        $defaultFolderCriteria->addFilter(new EqualsFilter('entity', self::ENTITY_NAME));

        $defaultFolder = $this->mediaDefaultFolderRepository->search($defaultFolderCriteria, $context)->first();
        $defaultFolderId = $defaultFolder?->getId();

        // Delete only if both exist
        try {
            if ($mediaFolderId) {
                $this->mediaFolderRepository->delete([['id' => $mediaFolderId]], $context);
            }

            if ($defaultFolderId) {
                $this->mediaDefaultFolderRepository->delete([['id' => $defaultFolderId]], $context);
            }
        } catch (UniqueConstraintViolationException $exception) {
            $this->log('Media folder deletion failed: ' . $exception->getMessage());
        }
    }

    private function log(string $message): void
    {
        if ($this->logger !== null) {
            $this->logger->warning($message);
        }
    }
}
