<?php

namespace App\Controller;

use App\Entity\Day;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class DayController extends AbstractController
{
    /**
     * @Route("/day/{id}", name="day", methods={"GET"}, requirements={"id"="\d+"})
     * @Entity("day", expr="repository.findOneByIdCustom(id)")
     */
    public function day(Day $day)
    {
        if ($day === null)
            throw $this->createNotFoundException('Day not found.');

        return $this->render('day/day.html.twig', [
            'day' => $day,
        ]);
    }
}
