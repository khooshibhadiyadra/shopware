<?php
declare(strict_types=1);

namespace ICTECHProductReviewWithRealUploadedImageVideo\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Core\Framework\Log\Package;

#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('storefront')]
class productReviewController extends AbstractController
{
    #[Route(path: '/api/productReview/maxFileUpload', name: 'api.action.productreview.maxfileupload', methods: ['GET'])]
    public function getMaxUploadFileSize(Request $request): JsonResponse
    {
        $iniSize = ini_get('upload_max_filesize');
        $bytes = $this->convertToBytes($iniSize);

        return new JsonResponse([
            'maxUploadFileSize' => $bytes
        ]);
    }

    private function convertToBytes(string $size): int
    {
        $size = trim($size);
        $last = strtolower($size[strlen($size)-1]);
        $value = (int) $size;

        switch ($last) {
            case 'g':
                return $value * 1024 * 1024 * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'k':
                return $value * 1024;
            default:
                return (int) $size;
        }
    }

}
