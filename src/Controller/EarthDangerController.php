<?php

namespace App\Controller;

use App\Gateway\NasaApiGateway;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EarthDangerController extends AbstractController
{
    /**
     * @Route("/earth-danger", name="earth_danger")
     */
    public function index(NasaApiGateway $nasaApiGateway)
    {
        $isEarthInDanger = $nasaApiGateway->isEarthInDanger();

        return $this->render('earth_danger/index.html.twig', [
            'isEarthInDanger' => $isEarthInDanger,
        ]);
    }
}
