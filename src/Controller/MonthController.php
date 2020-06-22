<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MonthController extends AbstractController
{
    /**
     * @Route("/month", name="month")
     */
    public function index()
    {
        return $this->render('month/index.html.twig', [
            'controller_name' => 'MonthController',
        ]);
    }
}
