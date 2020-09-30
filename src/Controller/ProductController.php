<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Route("api/products")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     *
     * @OA\Get(
     *     tags={"Product API"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns all products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Product::class))
     *         )
     *     )
     * )
     */
    public function index(ProductRepository $productRepository, Serializer $serializer)
    {
        try {
            $products = $productRepository->findAll();

            return new JsonResponse($serializer->serialize($products, 'json'), 200, [], true);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", methods={"GET"})
     *
     * @OA\Get(
     *     tags={"Product API"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Product ID",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns product by id",
     *         @OA\JsonContent(ref=@Model(type=Product::class))
     *     )
     * )
     */
    public function getWishList(Product $product, Serializer $serializer)
    {
        try {
            return new JsonResponse($serializer->serialize($product, 'json'), 200, [], true);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
