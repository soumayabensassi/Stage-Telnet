<?php

namespace App\Controller;

use App\DataPersister\UserDataPersister;
use App\Entity\Abonnement;
use App\Entity\Client;
use App\Entity\Payement;
use App\Form\ClientType;
use App\Form\PayementType;
use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\DeviceRepository;
use App\Repository\DomaineApplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Clone_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use function Sodium\compare;


/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="app_client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(), "error" => "",'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }

    /**
     * @Route("/profil", name="profil")
     */
    public function profil( AbonnementRepository $abonnementRepository,ClientRepository $clientRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        $user = $this->getUser();
        $dev=$abonnementRepository->abonnparclient($this->getUser()->getUsername());
        $notif=$abonnementRepository->notifclient($this->getUser()->getUsername());

        if(intval($notif)==0)
        { $em = $this->getDoctrine()->getManager();
            $c=$clientRepository->find($this->getUser()->getUsername());
            $c->setIsVerified(false);
            $em->persist($c);
            $em->flush();
        }
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('client/_form.html.twig', [
            'user' => $user,'abo'=>$dev,'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    /**
     * @Route("/payement/{id}", name="payement")
     */
    public function payement(Request $request,$id,DomaineApplicationRepository $domaineApplicationRepository,ClientRepository $clientRepository,EntityManagerInterface $a,UserPasswordEncoderInterface $b, AbonnementRepository $abonnementRepository,DeviceRepository $deviceRepository): Response
    {
        $abonnement=$abonnementRepository->find($id);

        $p=new Payement();
        $form = $this->createForm(PayementType::class,$p);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y', date('m/d/Y'));


            $c=$clientRepository->find($abonnement->getUser()[0]->getUsername());

            $em = $this->getDoctrine()->getManager();
            $i=0;
            $abonnement->setIsVerified(1);
            $abonnement->setEtat(1);
            $abonnement->setEnatentte(0);
            if($abonnement->getNom()=='Personnalisé'){
                $date=new \dateTime(date('Y-m-d', strtotime($dateTime->format('Y-m-d '). ' + '.$abonnement->getDuree().' months')));

            }
                else{
           $date=new \dateTime(date('Y-m-d', strtotime($dateTime->format('Y-m-d '). ' + '.$abonnement->getDuree().' months')));
                } $abonnement->setDateexp($date);
            /*while($abonnement->getNbrDevice()!=$i)
            {   $dev=$deviceRepository->find(intval($deviceRepository->maxdevice()));
                if($dev !=NULL)
                {$dev->setAbonnements($abonnement);

                    $i++;
                    $em->persist($dev);
                    $em->flush();}
            }*/

            $em->persist($abonnement);
            $em->persist($c);
            $em->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('client/payement.html.twig', [

            'form' => $form,"error" => "",'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll(),
            'abonnement'=>$abonnement,
        ]);
    }
    /**
     * @Route("/renouvler/{id}", name="renouvler")
     */
    public function renouvler(Request $request,$id,DomaineApplicationRepository $domaineApplicationRepository,ClientRepository $clientRepository,EntityManagerInterface $a,UserPasswordEncoderInterface $b, AbonnementRepository $abonnementRepository,DeviceRepository $deviceRepository): Response
    {   $abonnement=$abonnementRepository->find($id);

        $p=new Payement();
        $form = $this->createForm(PayementType::class,$p);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set('Europe/Paris');
            $dateTime = date_create_immutable_from_format('m/d/Y', date('m/d/Y'));

            $em = $this->getDoctrine()->getManager();
            $abonnement->setEtat(true);

            $date=new \dateTime(date('Y-m-d', strtotime($dateTime->format('Y-m-d '). ' + '.$abonnement->getOffre()->getDuree().' months')));
            $abonnement->setDateexp($date);

            $em->persist($abonnement);
            $em->flush();
            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('client/payement.html.twig', [

            'form' => $form,"error" => "",'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll(),'abonnement'=>$abonnement
        ]);
    }
    /**
     * @Route("/new", name="app_client_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DomaineApplicationRepository $domaineApplicationRepository,ClientRepository $clientRepository,EntityManagerInterface $a,UserPasswordEncoderInterface $b): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["verifpassword"]->getData() != $form["password"]->getData()) {
                return $this->render('client/new.html.twig', [
                    "form" => $form->createView(),
                    "error" => "Les mots de passe ne correspondent pas!",'user' => $this->getUser()

                ]);
            } else {
                $client->setRoles(['ROLE_USER']);
                $y = new UserDataPersister($a, $b);

                $y->persist($client);

                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,"error" => "",'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="app_client_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,"error" => "",'user' => $this->getUser()
        ]);
    }
    /**
     * @Route("/voirdeviceparabonnement/{id}", name="voirdeviceparabonnement")
     */
    public function voirdeviceparabonnement(DeviceRepository $deviceRepository,$id,DomaineApplicationRepository $domaineApplicationRepository,AbonnementRepository $abonnementRepository): Response
    {$ab=$abonnementRepository->find($id);
        $dev=$deviceRepository->Devparabo($id);
        return $this->render('client/showdetails.html.twig', [
            'devices' => $dev,'user' => $this->getUser(),'abonnements'=>$ab,'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    /**
     * @Route("/showabonnementparclient/{cat}/{id}", name="showabonnementparclient")
     */
    public function showabonnementparclient(AbonnementRepository $abonnementRepository,$id,$cat,DeviceRepository $deviceRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    { $em = $this->getDoctrine()->getManager();


       $idd=$abonnementRepository->idabonnementparclient($id);
        date_default_timezone_set('Europe/Paris');
        $dateTime = date_create_immutable_from_format('m/d/Y', date('m/d/Y'));
        $n=$abonnementRepository->nbrabonnementparclient($id);
        $date2=$dateTime->format('Y-m-d');

if(intval($n)!=1) {
    for ($i = 0; $i < intval($n); $i++) {
        $abonn = $abonnementRepository->find($idd[$i]);
        $date1 = $abonn->getDateexp()->format('Y-m-d');

        if ($date2 > $date1) {
            $abonn->setEtat(false);
            $em->persist($abonn);
            $em->flush();
        } elseif ($date1 > $date2) {
            $abonn->setEtat(true);

            $em->persist($abonn);
            $em->flush();
        }

    }
}
        $a=$abonnementRepository->getabonnementPERSONALISEbyuserandbydomaine($id,$cat);

        $a1=$abonnementRepository->getabonnementbyuserandbydomaine($id,$cat);
        $collection3 = new ArrayCollection(
            array_merge($a,$a1)
        );

        //$ab=$abonnementRepository->abonnementparclient($id);
        return $this->render('client/show.html.twig', [
            'abonnements' => $collection3 ,'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll(),'nomdomaine'=>$cat
        ]);
    }
    /**
     * @Route("/getAbonparclient/{id}", name="getAbonparclient")
     */
   /* public function getAbonparclient(AbonnementRepository $Repository,$id,ClientRepository $clientRepository): Response
    {
        $dev=$Repository->abonnparclient($id);
        dd($dev);
        return $this->render('client/show.html.twig', [
            'devices' => $dev,'user' => $this->getUser()
        ]);
    }
*/
    /**
     * @Route("/{id}/edit", name="app_client_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Client $client,DomaineApplicationRepository $domaineApplicationRepository,ClientRepository $clientRepository,EntityManagerInterface $a,UserPasswordEncoderInterface $b): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $y = new UserDataPersister($a, $b);

            $y->persist($client);

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,"error" => "",'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="app_client_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

}
