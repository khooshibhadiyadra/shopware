<?php

declare(strict_types=1);

namespace BlogTask\Core\Controllers\Storefront;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class ExampleController extends StorefrontController
{
    private EntityRepository $blogRepository;
    public function __construct(EntityRepository $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }
    #[Route(
        path: '/hello-world',
        name: 'storefront.example.show',
        methods: ['GET']
    )]
    public function showExample(Context $context): Response
    {
        $blogs = $this->blogRepository->search(new Criteria(), $context)->getEntities();

        return $this->renderStorefront('@BlogTask/storefront/page/blog.html.twig', [
            'example' => 'Hello,shopware6!',
            'blogs' => $blogs,
        ]);
    }

}