<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\WishList;
use App\Form\Type\WishListType;
use App\Repository\WishListRepository;
use App\Utils\FormUtils;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Serializer;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Route("api/wish-lists")
 */
class WishListController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     *
     *  @OA\Get(
     *     tags={"Wish List API"},
     *     @OA\Response(
     *         response=200,
     *         description="Returns all wish lists",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=WishList::class))
     *         )
     *     )
     * )
     */
    public function index(WishListRepository $wishListRepository, Serializer $serializer)
    {
        try {
            $withLists = $wishListRepository->findAll();

            return new JsonResponse($serializer->serialize($withLists, 'json'), 200, [], true);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Wish List API"},
     *      @OA\RequestBody(
     *          request="Wish List Form",
     *          description="Create new wish list",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/WishListForm")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Returns wish list",
     *          @OA\JsonContent(ref=@Model(type=WishList::class))
     *      )
     * )
     */
    public function create(
        Request $request,
        UserInterface $user,
        EntityManagerInterface $manager,
        Serializer $serializer,
        FormUtils $formUtils
    ) {
        try {
            if (!$user instanceof User) {
                throw new \RuntimeException('Invalid user');
            }

            $wishList = new WishList();
            $wishList->setUser($user);

            $form = $this->createForm(WishListType::class, null);
            $form->setData($wishList);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if (!$form->isValid()) {
                    if (!$form->isValid()) {
                        return $this->getValidationErrorResponse($form, $serializer, $formUtils);
                    }
                }

                $manager->persist($wishList);
                $manager->flush();
            }

            return new JsonResponse($serializer->serialize($wishList, 'json'), 200, [], true);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", methods={"GET"})
     *
     *  @OA\Get(
     *     tags={"Wish List API"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Wish List ID",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns wish list by id",
     *         @OA\JsonContent(ref=@Model(type=WishList::class))
     *     )
     * )
     */
    public function getWishList(WishList $wishList, Serializer $serializer)
    {
        try {
            return new JsonResponse($serializer->serialize($wishList, 'json'), 200, [], true);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     *
     * @OA\Put(
     *     tags={"Wish List API"},
     *      @OA\RequestBody(
     *          request="Wish List Form",
     *          description="Edit wish list",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/WishListForm")
     *      ),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Wish list ID",
     *          @OA\Schema(type="integer")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Returns wish list",
     *          @OA\JsonContent(ref=@Model(type=WishList::class))
     *      )
     * )
     */
    public function edit(
        Request $request,
        WishList $wishList,
        EntityManagerInterface $manager,
        Serializer $serializer,
        FormUtils $formUtils
    ) {
        try {
            $form = $this->createForm(WishListType::class, $wishList, ['method' => 'PUT']);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if (!$form->isValid()) {
                    return $this->getValidationErrorResponse($form, $serializer, $formUtils);
                }
                $wishList = $form->getData();
                $manager->flush();
            }

            return new JsonResponse($serializer->serialize($wishList, 'json'), 200, [], true);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     *
     * @OA\Delete(
     *     tags={"Wish List API"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Wish list ID",
     *          @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Delete wish list"
     *      )
     * )
     */
    public function delete(WishList $wishList, EntityManagerInterface $manager)
    {
        try {
            $manager->remove($wishList);
            $manager->flush();

            return $this->json(null, 200);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage(), 400]);
        }
    }

    private function getValidationErrorResponse(FormInterface $form, Serializer $serializer, FormUtils $formUtils)
    {
        return new JsonResponse(
            $serializer->serialize(
                [
                    'error' => 'There was a validation error',
                    'errors' => $formUtils->getErrorsFromForm($form),
                ],
                'json'
            ),
            400,
            [],
            true);
    }
}
