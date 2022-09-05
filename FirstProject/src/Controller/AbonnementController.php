<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Client;
use App\Entity\Device;
use App\Entity\Offre;
use App\Form\AbonnementType;
use App\Form\EmailUserType;
use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\DeviceRepository;
use App\Repository\DomaineApplicationRepository;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
/**
 * @Route("/abonnement")
 */
class AbonnementController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }


    //la fonction qui permet d'afficher les details de l'abonnemment et de changer la durée
    /**
     * @Route("chnagerDateAbonnement/{id}", name="chnagerDateAbonnement")
     */
    public function chnagerDureeAbonnement($id,DomaineApplicationRepository $domaineApplicationRepository,AbonnementRepository $abonnementRepository,MailerInterface $mailer,Request $request,ClientRepository $clientRepository): Response
    {                $abonnement=$abonnementRepository->find($id);
            if (null != $request->get('duree')) {
            $abonnement->setDuree(intval($request->get('duree')));
                $em = $this->getDoctrine()->getManager();
                $em->persist($abonnement);

                $em->flush();
         return $this->redirectToRoute('payement', ['id'=>$abonnement->getId()], Response::HTTP_SEE_OTHER);

            }
        return $this->render('abonnement/chnagerDateAbonnement.html.twig', [
            'abonnement' => $abonnement,'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    //fonction qui permet  au  chef de l'abonnement d'ajouer un userr
    /**
 * @Route("/addUser/{id}", name="addUser")
 */
    public function addUser($id,DomaineApplicationRepository $domaineApplicationRepository,AbonnementRepository $abonnementRepository,MailerInterface $mailer,Request $request,ClientRepository $clientRepository): Response
    {                $abonnement=$abonnementRepository->find($id);
        $x=$abonnementRepository->nombreuserbyabonnement($id);
        $verif=true;

        if ( $abonnement->getNom()=='Personnalisé')
        {
            if($x >= $abonnement->getNbracces())
            {
                $verif=false;
            }
        }
        else
        {
            if($x>=$abonnement->getOffre()->getNbrAcces())
            {
                $verif=false;
            }
        }
        if ($verif) {
            if (null != $request->get('email')) {

                $idclient = $clientRepository->returnclientbyemail($request->get('email'));
                if ($idclient != null) {
                    $array = array_values($idclient[0]);

                    $client = $clientRepository->find($array[0]);

                    $abonnement->addUser($client);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($abonnement);

                    $em->flush();
                    $email = (new TemplatedEmail())
                        ->from("soumaya.bensassi@esprit.tn")
                        ->to($request->get('email'))
                        ->subject('Voir Les abonnement!!!')
                        ->htmlTemplate('abonnement/emailnotif.html.twig')
                        ->context([
                            'user' => $this->getUser(),'abonnements'=>$abonnement


                        ]);
                    $mailer->send($email);
                    $this->addFlash('notice', 'L ajout a étè effectué avec succès un mail a été envoyé à votre ami!!');

                } else {

                    $email = (new TemplatedEmail())
                        ->from("soumaya.bensassi@esprit.tn")
                        ->to($request->get('email'))
                        ->subject('Créer votre compte!!!')
                        ->htmlTemplate('abonnement/emaildecréation.html.twig')
                        ->context([
                            'user' => $this->getUser(),


                        ]);
                    $mailer->send($email);
                    $this->addFlash('notice', 'votre ami n a pas un compte dans notre application un mail a étè envoyé  !!');
                    //msg d'erreur flash notif (user ma3andouch compte)
                    return $this->redirectToRoute('app_telnet', [], Response::HTTP_SEE_OTHER);
                }
                return $this->redirectToRoute('app_telnet', [], Response::HTTP_SEE_OTHER);

            }
        }
            else{

                $this->addFlash('notice', 'Nombre d accès est insuffisant !!');
            //msg d'erreur flash notif (nombre d'acces < user qui appartient deja au abonnement)
                return $this->redirectToRoute('app_telnet', [], Response::HTTP_SEE_OTHER);

            }


        return $this->render('abonnement/add.html.twig', [
            'user'=>$this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    /**
     * @Route("/abonnementaccepter", name="app_abonnementaccepter_index")
     */
    public function indexaccepter(AbonnementRepository $abonnementRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }
    /**
     * @Route("/", name="app_abonnement_index", methods={"GET"})
     */
    public function index(AbonnementRepository $abonnementRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),'user' => $this->getUser(),'error' => "",'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_abonnement_new", methods={"GET", "POST"})
     */
    public function new(Request $request,$id, AbonnementRepository $abonnementRepository,ClientRepository $clientRepository,DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        $abonnement = new Abonnement();
        //$user=$this->getUser();
$d=$domaineApplicationRepository->find($id);
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $abonnement->setEtat(false);
            $abonnement->setIsVerified(false);
            $abonnement->addUser($this->getUser());
            $abonnement->setNom('Personnalisé');
            $abonnement->setEnatentte(0);
            $abonnement->setChef($this->getUser()->getUserIdentifier());
            $abonnement->setDomaineApplication($d);
            $abonnementRepository->add($abonnement, true);

            return $this->redirectToRoute('app_telnet', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,'user' => $this->getUser(),'domaine'=>$domaineApplicationRepository->findAll()
        ]);
    }

    /**
     * @Route("/passerunecommande/{id}", name="passerunecommande", methods={"GET", "POST"})
     */
    public function passerunecommande($id,OffreRepository $offreRepository,ClientRepository $clientRepository):Response
    {
        $em = $this->getDoctrine()->getManager();
       $o =$offreRepository->find($id);
        $abonnement = new Abonnement();
        $abonnement->setEtat(false);
        $abonnement->setIsVerified(false);
        $abonnement->setChef($this->getUser()->getUserIdentifier());

        $abonnement->addUser($this->getUser());
        $abonnement->setOffre($o);

        $abonnement->setEnatentte(0);

        $em->persist($abonnement);

        $em->flush();
        return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/{id}", name="app_abonnement_show", methods={"GET"})
     */
    public function show(Abonnement $abonnement): Response
    {
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}/accept", name="app_abonnement_accept")
     */
    public function accept(MailerInterface $mailer,DomaineApplicationRepository $domaineApplicationRepository,DeviceRepository $deviceRepository ,AbonnementRepository $abonnementRepository,$id,ClientRepository $clientRepository): Response
    {

        $em = $this->getDoctrine()->getManager();
        $abonnement=$abonnementRepository->find($id);

            $email = (new TemplatedEmail())
                ->from(new Address('gclaimpidev@gmail.com', 'Security'))
                ->to($abonnement->getUser()[0]->getEmail())
                ->subject('Abonnement accpeté')
                ->htmlTemplate('abonnement/email.html.twig')
                ->context([
                    'user'=>$abonnement->getUser(),'abonnement'=>$abonnement


                ])

            ;
            $mailer->send($email);


            $abonnement->setEnatentte(1);

        $c=$clientRepository->find($abonnement->getUser()[0]->getUsername());
        $c->setIsVerified(1);
        $em->persist($c);
        $em->persist($abonnement);
        $em->flush();


        return $this->redirectToRoute("app_abonnement_index");

    }
    /**
     * @Route("/accepted", name="accepted")
     */
    public function acceptedRequest ($request, AbonnementRepository $abonnementRepository,DeviceRepository $deviceRepository,$id): Response
    {  $abonnement=$abonnementRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $i=0;
        $abonnement->setIsVerified(1);
        $abonnement->setEtat(1);


        while($abonnement->getNbrDevice()!=$i)
        {   $dev=$deviceRepository->find(intval($deviceRepository->maxdevice()));
            $dev->setAbonnements($abonnement);
            $i++;
            $em->persist($dev);
            $em->flush();
        }

        $em->persist($abonnement);
        $em->flush();
        return $this->redirectToRoute("app_abonnement_index");
    }
    /**
     * @Route("/{id}/enPause", name="app_abonnement_enPause")
     */
    public function enPause(Request $request, AbonnementRepository $abonnementRepository,$id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $abonnement=$abonnementRepository->find($id);
        $abonnement->setEtat(0);
        $em->persist($abonnement);
        $em->flush();
        return $this->redirectToRoute("app_abonnementaccepter_index");

    }
    /**
     * @Route("/{id}/enCours", name="app_abonnement_enCours")
     */
    public function enCours(Request $request, AbonnementRepository $abonnementRepository,$id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $abonnement=$abonnementRepository->find($id);
        $abonnement->setEtat(1);
        $em->persist($abonnement);
        $em->flush();
        return $this->redirectToRoute("app_abonnementaccepter_index");

    }
    /**
     * @Route("/{id}/delete", name="app_abonnement_refuse")
     */
    public function refuse(Request $request, AbonnementRepository $abonnementRepository,$id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $abonnement=$abonnementRepository->find($id);

        $em->remove($abonnement);
        $em->flush();
        return $this->redirectToRoute("app_abonnement_index");

    }
    /**
     * @Route("/{id}/edit", name="app_abonnement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {
        $form = $this->createForm(AbonnementType::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abonnementRepository->add($abonnement, true);

            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'form' => $form,'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}", name="app_abonnement_delete", methods={"POST"})
     */
    public function delete(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {
            $abonnementRepository->remove($abonnement, true);
        }

        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }
}
