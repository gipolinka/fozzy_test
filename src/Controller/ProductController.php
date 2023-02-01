<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/products")
 */
class ProductController extends AbstractFOSRestController
{
    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(
        private ProductRepository $productRepository
    )
    {
    }

    /**
     * @Route("/", "name=product_services_items", methods={"GET"})
     * @OA\Get (description="Список существующих тарифов продукта")
     * @OA\Response(response="200", description="successful operation",
     *     @OA\JsonContent(@OA\Schema ( @OA\Property(property="item", ref=@Model(type=Product::class))
     * )))
     * @return View
     */
    public function items(): View
    {
        $items = $this->productRepository->findAll();

        return $this->view(['items' => $items]);
    }

}