<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * REST convention
     * @Route("/opinions", name="opinions", methods={"POST"})
     */
    public function opinions(
        Request $request,
        SerializerInterface $serialiser,
        ValidatorInterface $validator
    ): JsonResponse {
        $content = $request->getContent();

        if ($json_decode($content) === null)
            return $this->json(
                ['information' => 'Invalid data format.'],
                Response::HTTP_UNAUTHORIZED
            );
        
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    /** 
     * @Route("/help", name="help", methods={"POST"})
     */
    public function help(
        Request $request,
        SerializerInterface $serialiser,
        ValidatorInterface $validator
    ): JsonResponse {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }
}
