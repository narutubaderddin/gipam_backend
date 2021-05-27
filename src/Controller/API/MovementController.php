<?php

namespace App\Controller\API;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MovementController
 * @package App\Controller\API
 * @Route("/movements")
 */
class MovementController extends AbstractFOSRestController
{
    /**
     * @Route("/", name="movement")
     */
    public function index(): Response
    {
        return $this->render('api/movement/index.html.twig', [
            'controller_name' => 'MovementController',
        ]);
    }
}
