<?php
declare(strict_types=1);

namespace BlogTask\Core\Controllers\Api;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
class   BlogController extends AbstractController
{
    private EntityRepository $blogRepository;
//    private EntityRepository $blogCategoryRepository;

    public function __construct(EntityRepository $blogRepository, EntityRepository $blogCategoryRepository)
    {
        $this->blogRepository = $blogRepository;
//        $this->blogCategoryRepository = $blogCategoryRepository;
    }

    #[Route(path: '/api/blogs', name: 'api.blog.listt', defaults: ['auth_required' => false], methods: ['GET'])]
    public function getAll(Context $context, Request $request): JsonResponse
    {
        $criteria = new Criteria();
        $active = $request->query->get('active');
        if ($active !== null) {
            $criteria->addFilter(new EqualsFilter('active', filter_var($active, FILTER_VALIDATE_BOOLEAN)));
        }
        $result = $this->blogRepository->search($criteria, $context);
        return new JsonResponse($result->getEntities());
    }

    #[Route(path: '/api/blogs/{id}', name: 'api.blog.detaill', defaults: ['auth_required' => false], methods: ['GET'])]
    public function getById(Context $context, string $id): JsonResponse
    {
        $criteria = new Criteria([$id]);
        $result = $this->blogRepository->search($criteria, $context);
        $blog = $result->get($id);
        return new JsonResponse($blog);
    }


}