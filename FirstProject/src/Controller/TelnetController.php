<?php

namespace App\Controller;

use App\Repository\DomaineApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TelnetController extends AbstractController
{
    /**
     * @Route("/", name="app_telnet")
     */
    public function index(DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        return $this->render('telnet/index.html.twig', [
            'controller_name' => 'TelnetController','user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    /**
     * @Route("/about", name="about_telnet")
     */
    public function index1(DomaineApplicationRepository $domaineApplicationRepository): Response
    {$x=$domaineApplicationRepository->findAll();
        return $this->render('telnet/about.html.twig', [
            'controller_name' => 'TelnetController','user' => $this->getUser(),'domaine'=>$x
        ]);
    }
}
