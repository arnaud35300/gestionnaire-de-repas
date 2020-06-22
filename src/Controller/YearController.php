<?php

namespace App\Controller;

use App\Entity\Year;
use App\Repository\MonthRepository;
use App\Repository\YearRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class YearController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar", methods={"GET"})
     */
    public function calendar(YearRepository $yearRepository)
    {
        $years = $yearRepository->findAllOrderByDesc();

        return $this->render('year/calendar.html.twig', [
            'years' => $years
        ]);
    }

    /**
     * @Route("/year/{name}", name="year", methods={"GET"}, requirements={"name"="\d+"})
     */
    public function year(
        Year $year = null,
        MonthRepository $monthRepository
    ) {
        if ($year === null)
            throw $this->createNotFoundException('Year not found.');
        
        $months = $monthRepository->findAll();

        return $this->render('year/year.html.twig', [
            'year' => $year,
            'months' => $months
        ]);
    }
}
