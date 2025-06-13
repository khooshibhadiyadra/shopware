<?php

declare(strict_types=1);

namespace ICTECHProductReviewWithRealUploadedImageVideo;

use Doctrine\DBAL\Connection;
use Exception;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use ICTECHProductReviewWithRealUploadedImageVideo\Util\MediaFolder;

class ICTECHProductReviewWithRealUploadedImageVideo extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
        $this->getMediaFolder()->installMedia($installContext->getContext());
    }

    /**
     * @param UninstallContext $uninstallContext
     * @return void
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        /* Keep UserData? Then do nothing here */
        if ($uninstallContext->keepUserData()) {
            return;
        }

        /**
         * @var Connection $connection
         */
        $connection = $this->container->get(Connection::class);
        try {
            $connection->executeStatement(
                'DELETE FROM system_config WHERE configuration_key LIKE :domain',
                [
                    'domain' => '%ICTECHProductReviewWithRealUploadedImageVideo.config%',
                ]
            );
        } catch (Exception $e) {
        }

        $this->getMediaFolder()->uninstallMedia($uninstallContext->getContext());
    }

    private function getMediaFolder(): Mediafolder
    {
        /** @var EntityRepository $mediaFolderReposiry */
        $mediaFolderRepository = $this->container->get('media_folder.repository');

        /** @var EntityRepository $mediaDefaultFolderRepository */
        $mediaDefaultFolderRepository = $this->container->get('media_default_folder.repository');

        return new Mediafolder(
            $mediaFolderRepository,
            $mediaDefaultFolderRepository
        );
    }
}