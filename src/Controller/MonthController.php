<?php

namespace App\Controller;

use App\Entity\Year;
use App\Entity\Month;
use App\Repository\DayRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class MonthController extends AbstractController
{
    /**
     * @Route("year/{name}/month/{number}", name="month", methods={"GET"}, requirements={"year"="\d+"})
     * 
     * ? year annotations is automatically detected, it's optional
     * @Entity("year", expr="repository.findOneByName(name)")
     * @Entity("month", expr="repository.findOneByNumber(number)")
     */
    public function month(
        Year $year = null,
        Month $month = null,
        DayRepository $dayRepository)
    {
        if ($year === null || $month === null)
            throw $this->createNotFoundException('Year and month not founded.');

        $days = $dayRepository->findAllByYearAndMonth($year, $month);

        return $this->render('month/month.html.twig', [
            'year'  => $year,
            'month' => $month,
            'days'  => $days
        ]);
    }
}
