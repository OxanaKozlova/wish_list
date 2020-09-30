<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use OpenApi\Annotations as OA;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="login", methods={"POST"})
     *
     * @OA\Post(
     *     tags={"Security API"},
     *      @OA\RequestBody(
     *          request="Login Form",
     *          description="Login",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginForm")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Returns token",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="token",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}
