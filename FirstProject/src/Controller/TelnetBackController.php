<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use FGhazaleh\MultiThreadManager\ThreadManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

class TelnetBackController extends AbstractController
{
    /**
     * @Route("/back", name="app_telnet_back")
     */
    public function index(ClientRepository $clientRepository): Response
    {   $abon=$clientRepository->getnumberabo();
        $client=$clientRepository->getnumberclient();
        $device=$clientRepository->getnumberdevice();
        $doamine=$clientRepository->getnumberdomaine();
        return $this->render('telnet_back/index.html.twig', [
            'controller_name' => 'TelnetBackController','abonnement'=>$abon,'client'=>$client,'device'=>$device,'domaine'=>$doamine
        ]);
    }
    /**
     * @Route("/genererDATA", name="genererDATA")
     */
    public function genererDATA()
    {
  $command = shell_exec(escapeshellcmd('python C:\wamp64\www\projet\python/consumer.py'));
  echo $command;

        return $this->redirectToRoute('app_telnet_back', [], Response::HTTP_SEE_OTHER);
    }

}
