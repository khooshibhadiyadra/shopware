<?php

declare(strict_types=1);

namespace ICTECHProductReviewWithRealUploadedImageVideo\Storefront\Controller;

use Shopware\Core\Content\Media\MediaService;
use Shopware\Core\Content\Product\Exception\ReviewNotActiveExeption;
use Shopware\Core\Content\Product\SalesChannel\FindVariant\AbstractFindProductVariantRoute;
use Shopware\Core\Content\Product\SalesChannel\Review\AbstractProductReviewSaveRoute;
use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandlerInterface;
use Shopware\Core\Framework\Api\Context\SystemSource;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Page\Product\ProductPageLoader;
use Shopware\Storefront\Page\Product\QuickView\MinimalQuickViewPageLoader;
use Shopware\Core\Content\Product\SalesChannel\Review\ProductReviewsWidgetLoadedHook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Content\Media\File\FileNameProvider;
use Shopware\Core\Content\Media\File\MediaFile;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Util\Random;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\Routing\Attribute\Route;
use Shopware\Core\Content\Product\SalesChannel\Review\AbstractProductReviewLoader;
use Psr\Log\LoggerInterface;

#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
class ProductController extends StorefrontController
{
    private ProductPageLoader $productPageLoader;
    private MinimalQuickViewPageLoader $minimalQuickViewPageLoader;
    private AbstractProductReviewLoader $productReviewLoader;
    private FileSaver $mediaUpdater;
    private FileNameProvider $fileNameProvider;

    private AbstractFindProductVariantRoute $findVariantRoute;

    private AbstractProductReviewSaveRoute $productReviewSaveRoute;
    private SeoUrlPlaceholderHandlerInterface $seoUrlPlaceholderHandler;

    private SystemConfigService $systemConfigService;

    private EntityRepository $mediaRepository;
    private EntityRepository $mediaFolderRepository;
    private LoggerInterface $logger;

    public function __construct(
        ProductPageLoader $productPageLoader,
        AbstractFindProductVariantRoute $findVariantRoute,
        MinimalQuickViewPageLoader $minimalQuickViewPageLoader,
        AbstractProductReviewSaveRoute $productReviewSaveRoute,
        SeoUrlPlaceholderHandlerInterface $seoUrlPlaceholderHandler,
        AbstractProductReviewLoader $productReviewLoader,
        SystemConfigService $systemConfigService,
        FileSaver $mediaUpdater,
        FileNameProvider $fileNameProvider,
        EntityRepository $mediaRepository,
        EntityRepository $mediaFolderRepository,
        LoggerInterface $logger
    ) {
        $this->productPageLoader = $productPageLoader;
        $this->findVariantRoute = $findVariantRoute;
        $this->minimalQuickViewPageLoader = $minimalQuickViewPageLoader;
        $this->productReviewSaveRoute = $productReviewSaveRoute;
        $this->seoUrlPlaceholderHandler = $seoUrlPlaceholderHandler;
        $this->productReviewLoader = $productReviewLoader;
        $this->systemConfigService = $systemConfigService;
        $this->mediaUpdater = $mediaUpdater;
        $this->fileNameProvider = $fileNameProvider;
        $this->mediaRepository = $mediaRepository;
        $this->mediaFolderRepository = $mediaFolderRepository;
        $this->logger = $logger;
    }

    #[Route(path: '/product/{productId}/rating', name: 'frontend.detail.review.save', defaults: ['XmlHttpRequest' => true, '_loginRequired' => true], methods: ['POST'])]
    public function saveReview(string $productId, RequestDataBag $data, SalesChannelContext $context, Request $request): Response
    {
        $this->saveReviewImageVideoData($data, $context, $request);
        $this->checkReviewsActive($context);

        try {
            $this->productReviewSaveRoute->save($productId, $data, $context);
        } catch (ConstraintViolationException $formViolations) {
            return $this->forwardToRoute('frontend.product.reviews', [
                'productId' => $productId,
                'success' => -1,
                'formViolations' => $formViolations,
                'data' => $data,
            ], ['productId' => $productId]);
        }

        $forwardParams = [
            'productId' => $productId,
            'success' => 1,
            'data' => $data,
            'parentId' => $data->get('parentId'),
        ];

        if ($data->has('id')) {
            $forwardParams['success'] = 2;
        }

        return $this->forwardToRoute('frontend.product.reviews', $forwardParams, ['productId' => $productId]);
    }
    private function handleMediaRemoval(RequestDataBag $data, Context $context, string $type): void
    {
        $preview = $data->get('preview' . ucfirst($type));
        $remove  = $data->get('removePreview' . ucfirst($type));

//        if (empty($preview) || empty($remove)) {
//            return;
//        }
        if (!$preview || !$remove) {
            return;
        }
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $preview));

        $media = $this->mediaRepository->search($criteria, $context)->first();
        if ($media) {
            $this->mediaRepository->delete([['id' => $preview]], $context);
        }
    }
    private function assignExistingMedia(RequestDataBag $data, string $type): void
    {
        $preview = $data->get('preview' . ucfirst($type));
//        if (!empty($preview)) {
//            $data->ICTECHImageVideoData[$type] = $preview;
//        }
        if ($preview !== null && $preview !== '') {
            $data->ICTECHImageVideoData[$type] = $preview;
        }

    }
    private function getReviewMediaFolderId(Context $context): ?string
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'Product Review Media'));
        $folder = $this->mediaFolderRepository->search($criteria, $context)->first();

        return $folder ? $folder->getId() : null;
    }

    private function processUploadedFiles(Request $request, RequestDataBag $data, Context $context): void
    {
        $files = $request->files->has('attachement')
            ? $request->files->get('attachement')
            : $request->files;

        foreach ($files as $key => $file) {
            $this->handleSingleFileUpload($file, $key, $data, $context);
        }
    }
    private function handleSingleFileUpload($file, string $key, RequestDataBag $data, Context $context): void
    {
        if (!$file || !$file->isValid() || !$file->getRealPath()) {
            return;
        }

        $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $original . '_' . Random::getInteger(100, 1000);
        $mediaId  = Uuid::randomHex();

        if ($key === 'image' && $data->get('previewImage')) {
            $mediaId = $data->get('previewImage');
        }

        if ($key === 'video' && $data->get('previewVideo')) {
            $mediaId = $data->get('previewVideo');
        }

        $folderId = $this->getReviewMediaFolderId($context);

        $this->mediaRepository->upsert([[
            'id'             => $mediaId,
            'name'           => $fileName,
            'fileName'       => $fileName,
            'mimeType'       => $file->getClientMimeType(),
            'fileExtension'  => $file->guessExtension(),
            'mediaFolderId'  => $folderId,
        ]
        ], $context);

        try {
            $this->upload($file, $fileName, $mediaId, $context);
        } catch (\Exception $e) {
            $fallback = $fileName . '_' . Random::getInteger(100, 1000);
            $this->upload($file, $fallback, $mediaId, $context);
        }

        $data->ICTECHImageVideoData[$key] = $mediaId;
    }
    private function saveReviewImageVideoData(RequestDataBag $data, SalesChannelContext $context, Request $request): void
    {
        $coreContext = $context->getContext();

        $this->handleMediaRemoval($data, $coreContext, 'image');
        $this->handleMediaRemoval($data, $coreContext, 'video');

        $this->assignExistingMedia($data, 'image');
        $this->assignExistingMedia($data, 'video');

        $this->processUploadedFiles($request, $data, new Context(new SystemSource()));
    }

    /*upload media */
    private function upload($file, $fileName, $mediaId, $context): void
    {
        $realPath = $file->getRealPath();

        // Prevent invalid or unreadable file error
        if (!$realPath || !is_readable($realPath)) {
            throw new \RuntimeException(sprintf('File "%s" does not exist or is not readable.', $file->getClientOriginalName()));
        }

        $this->mediaUpdater->persistFileToMedia(
            new MediaFile(
                $realPath,
                $file->getMimeType(),
                $file->guessExtension(),
                $file->getSize()
            ),
            $this->fileNameProvider->provide(
                $fileName,
                $file->getExtension(),
                $mediaId,
                $context
            ),
            $mediaId,
            $context
        );
    }
    #[Route(path: '/product/{productId}/reviews', name: 'frontend.product.reviews', defaults: ['XmlHttpRequest' => true], methods: ['GET', 'POST'])]
    public function loadReviews(Request $request, SalesChannelContext $context, string $productId): Response
    {
        $this->checkReviewsActive($context);

        // Load product page (optional, if you want product info too)
        $page = $this->productPageLoader->load($request, $context);
        $product = $page->getProduct();

        // Load reviews properly using the loader
        $reviews = $this->productReviewLoader->load($request, $context, $productId);

        $this->hook(new ProductReviewsWidgetLoadedHook($reviews, $context));

        return $this->renderStorefront('@Storefront/storefront/component/review/review-form.html.twig', [
            'product' => $product,
            'reviews' => $reviews,
            'ratingSuccess' => $request->get('success'),

            // Configs for images/videos
            'allowImage' => $this->systemConfigService->get('ICTECHProductReviewWithRealUploadedImageVideo.config.allowImage', $context->getSalesChannel()->getId()),
            'allowVideo' => $this->systemConfigService->get('ICTECHProductReviewWithRealUploadedImageVideo.config.allowVideo', $context->getSalesChannel()->getId()),
            'maxUploadVideoSize' => $this->systemConfigService->get('ICTECHProductReviewWithRealUploadedImageVideo.config.videoSize', $context->getSalesChannel()->getId()),
            'maxUploadVideoExtensions' => $this->systemConfigService->get('ICTECHProductReviewWithRealUploadedImageVideo.config.videoExtension', $context->getSalesChannel()->getId()),
        ]);

    }

    /**
     * @throws ReviewNotActiveExeption
     */
    private function checkReviewsActive(SalesChannelContext $context): void
    {
        $showReview = $this->systemConfigService->get('core.listing.showReview', $context->getSalesChannel()->getId());

        if (!$showReview) {
            throw new ReviewNotActiveExeption();
        }
    }
}