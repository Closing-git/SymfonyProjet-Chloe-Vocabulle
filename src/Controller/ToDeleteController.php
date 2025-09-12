<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ToDeleteController extends AbstractController
{
    #[Route('/to/delete', name: 'app_to_delete')]
    public function index(): Response
    {
        return $this->render('to_delete/index.html.twig', [
            'controller_name' => 'ToDeleteController',
        ]);
    }
}
