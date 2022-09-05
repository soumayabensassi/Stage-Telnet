<?php

namespace App\Controller;

use App\Entity\Autorisation;
use App\Entity\Device;
use App\Form\AjoutDeviceByUserType;
use App\Form\DeviceType;
use App\Repository\AbonnementRepository;
use App\Repository\AutorisationRepository;
use App\Repository\DeviceRepository;
use App\Repository\DomaineApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @Route("/device")
 */
class DeviceController extends AbstractController
{
    //fonction qui permet au user de choisir un device parmis les devices déja existante dans la base de Telent

    /**
     * @Route("choisirDEV/{id}/{abo}", name="choisirDEV")
     */
    public function choisirDEV(Request $request,$id,$abo,AbonnementRepository $abonnementRepository,DeviceRepository $deviceRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {$ab=$abonnementRepository->find($abo);
        $x=$deviceRepository->getNBRDeviceParAbonnement($abo);
        $verif=true;
      
        if($ab->getNom()=='Personnalisé')
        {
            if($x>=$ab->getNbrDevice())
            {
                $verif=false;
            }
        }
        else
        {
            if($x>=$ab->getOffre()->getNbrDevice())
            {
                $verif=false;
            }
        }
        if($verif==true) {
            $devices = $deviceRepository->getDeviceParDomaine($id);

            return $this->renderForm('device/choisirDEV.html.twig', [
                'device' => $devices,
                'user' => $this->getUser(), 'domaine' => $domaineApplicationRepository->findAll(), 'abonnement' => $ab
            ]);
        }
        else
        {

            $this->addFlash('notice', 'impossible d ajouter un device nombre des devices acheté est atteint ');


            return $this->redirectToRoute('voirdeviceparabonnement', ['id' => $ab->getId()], Response::HTTP_SEE_OTHER);

        }
    }
    //fonction qui permet d'ajouter un device dans un abonnement

    /**
     * @Route("addDEVICE/{id}/{abo}", name="addDEVICE")
     */
    public function addDEVICE(Request $request,$id,$abo,AbonnementRepository $abonnementRepository,DeviceRepository $deviceRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {   $em = $this->getDoctrine()->getManager();
        $x=$abonnementRepository->find($abo);
        $devices= $deviceRepository->find($id);
        $devices->setAbonnements($x);
        $em->persist($devices);
        $em->flush();
        return $this->redirectToRoute('voirdeviceparabonnement', ['id'=>$abo], Response::HTTP_SEE_OTHER);


    }
//fonction qui permet au user d'ajouter sont propre device

    /**
     * @Route("ajoutDeviceParClient/{id}", name="ajoutDeviceParClient")
     */
    public function ajoutDeviceParClient(Request $request,$id,DeviceRepository $deviceRepository,AbonnementRepository $abonnementRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {$x=$deviceRepository->getNBRDeviceParAbonnement($id);

        $abonnement=$abonnementRepository->find($id);

        $verif=true;
        if($abonnement->getNom()=='Personnalisé')
        {
            if($x>=$abonnement->getNbrDevice())
            {
                $verif=false;
            }
        }
        else
        {
            if($x>=$abonnement->getOffre()->getNbrDevice())
            {
                $verif=false;
            }
        }
        $device = new Device();
        $form = $this->createForm(AjoutDeviceByUserType::class, $device);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $autorisation=new Autorisation();
        $abonnement=$abonnementRepository->find($id);
        if($verif==true) {
            if ($form->isSubmitted() && $form->isValid()) {
                $device->setAbonnements($abonnement);
                if ($abonnement->getNom() == 'Personnalisé') {
                    $device->setDomaineApplication($abonnement->getDomaineApplication());
                } else {
                    $device->setDomaineApplication($abonnement->getOffre()->getDomaineApplication());
                }

                $autorisation->setHeartbeat(false);
                $autorisation->setBlood(false);
                $autorisation->setTemperatureSante(false);
                $autorisation->setTemperatureAgrigole(false);
                $autorisation->setBilanhydrique(false);
                $autorisation->setDevice($device);
                $em->persist($autorisation);
                $em->persist($device);
                $em->flush();
                return $this->redirectToRoute('voirdeviceparabonnement', ['id' => $abonnement->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('device/addDeviceParClient.html.twig', [
                'device' => $device,
                'form' => $form, 'user' => $this->getUser(), 'domaine' => $domaineApplicationRepository->findAll()
            ]);
        }
        else
        {
            $this->addFlash('notice', 'impossible d ajouter un device nombre des devices acheté est atteint ');


            return $this->redirectToRoute('voirdeviceparabonnement', ['id' => $abonnement->getId()], Response::HTTP_SEE_OTHER);

        }
    }
    /**
     * @Route("/", name="app_device_index", methods={"GET"})
     */
    public function index(DeviceRepository $deviceRepository): Response
    {$nb=intval($deviceRepository->nboredevicedisponible());
        return $this->render('device/index.html.twig', [
            'devices' => $deviceRepository->findAll(),'nb'=>$nb
        ]);
    }
    /**
     * @Route("chart/{id}/{abo}", name="chart")
     */
    public function chart(Request $request,ChartBuilderInterface $chartBuilder,$id,$abo,AutorisationRepository $autorisationRepository,AbonnementRepository $abonnementRepository,DeviceRepository $deviceRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        $idAutorisation=$deviceRepository->getIdAutorisation($id);
        extract($idAutorisation);
        $autorisation=$autorisationRepository->find($auto);

        if (null != $request->get('datedebut') && null != $request->get('datefin')) {
            $nombre=$deviceRepository->getNBRDataByDeviceByDate($id,$request->get('datedebut'),$request->get('datefin'));
            $d = $deviceRepository->getDataByDevicebyDate($id,$request->get('datedebut'),$request->get('datefin'));

        }
        else
        {
        $nombre=10;
        $d = $deviceRepository->getDataByDevice($id);
        }

        $abonnement=$abonnementRepository->find($abo);
        $dev=$deviceRepository->find($id);
        $timer=[];
        $temperature=[];
        $donnee=[];
        $Bilan=[];

       for ($i = 0; $i <$nombre ; $i++) {

            extract($d[$i]);


            for ($j = 0; $j <5 ; $j++)
            {

                $result = substr($data, 0, 4);

                $data = substr($data,4, strlen($data) - strlen($result));
                $donnee[$j]=$result;


            }
            $donnee[]=$data;
           $date1 = $timestamp->format('D-d-M-Y H:i:s.u');
            $timerT[]=$date1;
            $temperature[] = hexdec($donnee[1]);

           if (isset($_POST['bilan'])) {
               switch($_POST['bilan']) {
                   case "bilanpositif":
                       {
                           $t=hexdec($donnee[2])-hexdec($donnee[3])-hexdec($donnee[4])-hexdec($donnee[5]);
                           if($t>0) {
                               $Bilan[] =$t;
                               $date = $timestamp->format('D-d-M-Y H:i:s.u');
                               $timer[] = $date;
                           }
                       }
                       break;

                   case "bilannegatif":
                       {
                           $t=hexdec($donnee[2])-hexdec($donnee[3])-hexdec($donnee[4])-hexdec($donnee[5]);
                           if($t<0) {
                               $Bilan[] =$t;
                               $date = $timestamp->format('D-d-M-Y H:i:s.u');
                               $timer[] = $date;
                           }
                       }
                       break;

               }
           }
           else
           { $Bilan[]= hexdec($donnee[2])-hexdec($donnee[3])-hexdec($donnee[4])-hexdec($donnee[5]);
               $date = $timestamp->format('D-d-M-Y H:i:s.u');
               $timer[] = $date;
           }



        }
        $positif=0;
        $negatif=0;

        for ($i=0;$i<count($Bilan);$i++)
        {
            if($Bilan[$i]>0)
            {
                $positif++;
            }
            if($Bilan[$i]<0)
            {
                $negatif++;
            }
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $timerT,
            'datasets' => [
                [
                    'label' => 'Température (°C)',
                    'backgroundColor' => 'rgb(0, 73, 153)',
                    'borderColor' => 'rgb(0, 73, 153)',
                    'data' => $temperature,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 50,
                ],
            ],
        ]);
        if($positif>$negatif)
        {
            $label="Moyennement le bilan hydrique est positif ";
            $color='rgb(0, 73, 153)';
        }
        else
        {
            $label="Moyennement le bilan hydrique est négatif";
            $color='rgb(255, 0, 0)';
        }

        $chartBH = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartBH->setData([
            'labels' => $timer,
            'datasets' => [
                [
                    'label' => $label,
                    'backgroundColor' => $color,
                    'borderColor' => $color ,
                    'data' => $Bilan,
                ],
            ],
        ]);

        $chartBH->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('device/chart.html.twig', [
            'chart' => $chart,'autorisation'=>$autorisation,'chartBH' =>$chartBH,'abonnements'=>$abonnement,'user'=>$this->getUser(),'device'=>$dev,'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    /**
     * @Route("chartSante/{id}/{abo}", name="chartSante")
     */
    public function chartSante(Request $request,ChartBuilderInterface $chartBuilder,$id,$abo,AutorisationRepository $autorisationRepository,AbonnementRepository $abonnementRepository,DeviceRepository $deviceRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        $idAutorisation=$deviceRepository->getIdAutorisation($id);
        extract($idAutorisation);
        $autorisation=$autorisationRepository->find($auto);

        if (null != $request->get('datedebut') && null != $request->get('datefin')) {
            $nombre=$deviceRepository->getNBRDataByDeviceByDate($id,$request->get('datedebut'),$request->get('datefin'));
            $d = $deviceRepository->getDataByDevicebyDate($id,$request->get('datedebut'),$request->get('datefin'));

        }
        else
        {   $te=$deviceRepository->getNBRDataByDevice($id);
            if($te>10)
            {
                $nombre=$te/2;
            }
            if($te>20)
            {
                $nombre=$te/4;
            }
            else
            {
                $nombre=$te;
            }

            $d = $deviceRepository->getDataByDevice($id);
        }

        $abonnement=$abonnementRepository->find($abo);
        $dev=$deviceRepository->find($id);
        $timer=[];
        $temperature=[];
        $donnee=[];
        $heartBeat=[];
        $blood=[];
        for ($i = 0; $i <$nombre ; $i++) {

            extract($d[$i]);


            for ($j = 0; $j <3 ; $j++)
            {

                $result = substr($data, 0, 4);

                $data = substr($data,4, strlen($data) - strlen($result));
                $donnee[$j]=$result;


            }
            $donnee[$j]=$data;

            $temperature[] = hexdec($donnee[1]);
            $blood[] = hexdec($donnee[2]);

            $heartBeat[]=hexdec($donnee[3]);
            $date = $timestamp->format('D-d-M-Y H:i:s.u');
            $timer[] = $date;

        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $timer,
            'datasets' => [
                [
                    'label' => 'Température (°C) ',
                    'backgroundColor' => 'rgb(0, 73, 153)',
                    'borderColor' => 'rgb(0, 73, 153)',
                    'data' => $temperature,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 50,
                ],
            ],
        ]);

        $chartBlood = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartBlood->setData([
            'labels' => $timer,
            'datasets' => [
                [
                    'label' => 'la pression artérielle (cmHg)',
                    'backgroundColor' => 'rgb(0, 73, 153)',
                    'borderColor' =>'rgb(0, 73, 153)' ,
                    'data' => $blood,
                ],
            ],
        ]);

        $chartBlood->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        $chartHeartBeat = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chartHeartBeat->setData([
            'labels' => $timer,
            'datasets' => [
                [
                    'label' => 'Pulsations (Battements)',
                    'backgroundColor' => 'rgb(0, 73, 153)',
                    'borderColor' => 'rgb(0, 73, 153)' ,
                    'data' => $heartBeat,
                ],
            ],
        ]);

        $chartHeartBeat->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        return $this->render('device/chartSante.html.twig', [
            'chart' => $chart,'autorisation'=>$autorisation,'chartblood' =>$chartBlood,'chartHeartBeat'=>$chartHeartBeat,'abonnements'=>$abonnement,'user'=>$this->getUser(),'device'=>$dev,'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    /**
     * @Route("/dev", name="dev")
     */
    public function dev( DeviceRepository $deviceRepository)
    {
        dd($deviceRepository->maxdevice());
    }
    /**
     * @Route("/new", name="app_device_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DeviceRepository $deviceRepository): Response
    {
        $device = new Device();
        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);
$autorisation=new Autorisation();
        if ($form->isSubmitted() && $form->isValid()) {

            $deviceRepository->add($device, true);
            $autorisation->setHeartbeat(false);
            $autorisation->setBlood(false);
            $autorisation->setTemperatureSante(false);
            $autorisation->setTemperatureAgrigole(false);
            $autorisation->setBilanhydrique(false);
            $autorisation->setDevice($device);
            $em = $this->getDoctrine()->getManager();
            $em->persist($autorisation);
            $em->flush();

return $this->redirectToRoute('app_device_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('device/new.html.twig', [
            'device' => $device,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="delete")
     */
    public function delete1(DeviceRepository $deviceRepository,$id): Response
    {
        $d = $deviceRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($d);
        $em->flush();
        return $this->redirectToRoute("app_device_index");
    }
    /**
     * @Route("/{id}", name="app_device_show", methods={"GET"})
     */
    public function show(Device $device): Response
    {
        return $this->render('device/show.html.twig', [
            'device' => $device,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_device_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Device $device, DeviceRepository $deviceRepository): Response
    {
        $form = $this->createForm(DeviceType::class, $device);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deviceRepository->add($device, true);

            return $this->redirectToRoute('app_device_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('device/edit.html.twig', [
            'device' => $device,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_device_delete", methods={"POST"})
     */
    public function delete(Request $request, Device $device, DeviceRepository $deviceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$device->getId(), $request->request->get('_token'))) {
            $deviceRepository->remove($device, true);
        }

        return $this->redirectToRoute('app_device_index', [], Response::HTTP_SEE_OTHER);
    }

}
