<?php

declare(strict_types=1);

namespace ICTECHProductReviewWithRealUploadedImageVideo\Core\Content\Product\SalesChannel\Review;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Content\Product\Exception\ReviewNotActiveExeption;
use Shopware\Core\Content\Product\SalesChannel\Review\Event\ReviewFormEvent;
use Shopware\Core\Content\Product\SalesChannel\Review\AbstractProductReviewSaveRoute;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityExists;
use Shopware\Core\Framework\DataAbstractionLayer\Validation\EntityNotExists;
use Shopware\Core\Framework\Event\EventData\MailRecipientStruct;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Plugin\Exception\DecorationPatternException;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\NoContentResponse;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route(defaults: ['_routeScope' => ['store-api']])]
#[Package('inventory')]
class ICTECHProductReviewSaveRoute extends AbstractProductReviewSaveRoute
{
    public function __construct(
        private readonly EntityRepository $repository,
        private readonly DataValidator $validator,
        private readonly SystemConfigService $config,
        private readonly \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function getDecorated(): AbstractProductReviewSaveRoute
    {
        throw new DecorationPatternException(self::class);
    }

    #[Route(path: '/store-api/product/{productId}/review', name: 'store-api.product-review.save', methods: ['POST'], defaults: ['_loginRequired' => true])]
    public function save(string $productId, RequestDataBag $data, SalesChannelContext $context): NoContentResponse
    {
        $filesData = $this->saveImageVideoData($data);
        $this->checkReviewsActive($context);

        /** @var CustomerEntity $customer */
        $customer = $context->getCustomer();
        $this->fillCustomerDefaults($data, $customer);

        $data->set('customerId', $customer->getId());
        $data->set('productId', $productId);

        $this->validate($data, $context->getContext());

        $review = $this->buildReviewData(
            $productId,
            $customer->getId(),
            $context->getSalesChannel()->getId(),
            $context->getContext()->getLanguageId(),
            $data,
            $filesData
        );

        $this->repository->upsert([$review], $context->getContext());

        $mail = $this->config->get('core.basicInformation.email', $context->getSalesChannel()->getId());
        $mail = \is_string($mail) ? $mail : '';

        $event = new ReviewFormEvent(
            $context->getContext(),
            $context->getSalesChannel()->getId(),
            new MailRecipientStruct([$mail => $mail]),
            $data,
            $productId,
            $customer->getId()
        );

        $this->eventDispatcher->dispatch($event, ReviewFormEvent::EVENT_NAME);

        return new NoContentResponse();
    }

    private function saveImageVideoData(DataBag $data): array
    {
        $filesData['ICTECHImageVideoData'] = [];

        if (!empty($data->ICTECHImageVideoData)) {
            $filesData['ICTECHImageVideoData'] = $data->ICTECHImageVideoData;
        }

        return $filesData;
    }

    private function validate(DataBag $data, Context $context): void
    {
        $definition = new DataValidationDefinition('product.create_rating');

        $definition->add('name', new NotBlank());
        $definition->add('title', new NotBlank(), new Length(['min' => 5]));
        $definition->add('content', new NotBlank(), new Length(['min' => 40]));
        $definition->add('points', new GreaterThanOrEqual(1), new LessThanOrEqual(5));

        if ($data->get('id')) {
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('customerId', $data->get('customerId')));
            $criteria->addFilter(new EqualsFilter('id', $data->get('id')));

            $definition->add('id', new EntityExists([
                'entity' => 'product_review',
                'context' => $context,
                'criteria' => $criteria,
            ]));
        } else {
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('customerId', $data->get('customerId')));
            $criteria->addFilter(new EqualsFilter('productId', $data->get('productId')));

            $definition->add('customerId', new EntityNotExists([
                'entity' => 'product_review',
                'context' => $context,
                'criteria' => $criteria,
            ]));
        }

        $violations = $this->validator->getViolations($data->all(), $definition);

        if ($violations->count() > 0) {
            throw new ConstraintViolationException($violations, $data->all());
        }
    }

    /**
     * @throws ReviewNotActiveExeption
     */
    private function checkReviewsActive(SalesChannelContext $context): void
    {
        $showReview = $this->config->get('core.listing.showReview', $context->getSalesChannel()->getId());

        if (!$showReview) {
            throw new ReviewNotActiveExeption();
        }
    }

    private function fillCustomerDefaults(RequestDataBag $data, CustomerEntity $customer): void
    {
        if (!$data->has('name')) {
            $data->set('name', $customer->getFirstName());
        }

        if (!$data->has('lastName')) {
            $data->set('lastName', $customer->getLastName());
        }

        if (!$data->has('email')) {
            $data->set('email', $customer->getEmail());
        }
    }

    private function buildReviewData(
        string $productId,
        string $customerId,
        string $salesChannelId,
        string $languageId,
        RequestDataBag $data,
        array $filesData
    ): array {
        $review = [
            'productId' => $productId,
            'customerId' => $customerId,
            'salesChannelId' => $salesChannelId,
            'languageId' => $languageId,
            'externalUser' => $data->get('name'),
            'externalEmail' => $data->get('email'),
            'title' => $data->get('title'),
            'content' => $data->get('content'),
            'points' => $data->get('points'),
            'customFields' => $filesData,
            'status' => false,
        ];

        if ($data->get('id')) {
            $review['id'] = $data->get('id');
        }

        return $review;
    }
}
