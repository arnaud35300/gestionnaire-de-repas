<?php

namespace App\Controller;

use App\Entity\Year;
use App\Entity\Month;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class MonthController extends AbstractController
{
    /**
     * @Route("year/{name}/month/{number}", name="month", methods={"GET"}, requirements={"year"="\d+"})
     * @Entity("month", expr="repository.findOneByNumber(number)")
     */
    public function month(Year $year = null, Month $month = null)
    {
        if ($year = null || $month === null)
            $this->createNotFoundException('Year and month not founded.');
        
        return $this->render('month/month.html.twig', [
            'year'  => $year,
            'month' => $month
        ]);
    }
}
