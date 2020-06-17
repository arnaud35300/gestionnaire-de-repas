<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        if (json_decode($content) === null)
            return $this->json(
                ['information' => 'Invalid data format.'],
                Response::HTTP_UNAUTHORIZED
            );
        
        $contactMessage = $serialiser->deserialize(
            $content, 
            ContactMessage::class,
            'json',
            ['groups' => 'message']
        );

        $contactMessage
            ->setUser($this->getUser())
            ->setCreatedAt(new \DateTime)
            ->setType('opinions');

        $violations = $validator->validate($contactMessage);

        if ($violations->count() > 0)
            return $this->json(
                $violations,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        $manager = $this->getDoctrine()->getManager();

        $manager->persist($contactMessage);
        $manager->flush();

        return $this->json(
            [
                'information' => 'Message is send.'
            ],
            Response::HTTP_CREATED            
        );
    }

    /** 
     * @Route("/help", name="help", methods={"POST"})
     */
    public function help(
        Request $request,
        SerializerInterface $serialiser,
        ValidatorInterface $validator
    ): JsonResponse {
        $content = $request->getContent();

        if (json_decode($content) === null)
            return $this->json(
                ['information' => 'Invalid data format.'],
                Response::HTTP_UNAUTHORIZED
            );
        
        $contactMessage = $serialiser->deserialize(
            $content, 
            ContactMessage::class,
            'json',
            ['groups' => 'message']
        );

        $contactMessage
            ->setUser($this->getUser())
            ->setCreatedAt(new \DateTime)
            ->setType('help');

        $violations = $validator->validate($contactMessage);

        if ($violations->count() > 0)
            return $this->json(
                $violations,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );

        $manager = $this->getDoctrine()->getManager();

        $manager->persist($contactMessage);
        $manager->flush();

        return $this->json(
            [
                'information' => 'Message is send.'
            ],
            Response::HTTP_CREATED            
        );
    }
}
