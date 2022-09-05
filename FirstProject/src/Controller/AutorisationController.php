<?php

namespace App\Controller;

use App\Entity\Autorisation;
use App\Form\AutorisationType;
use App\Repository\AutorisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/autorisation")
 */
class AutorisationController extends AbstractController
{
    /**
     * @Route("/", name="app_autorisation_index", methods={"GET"})
     */
    public function index(AutorisationRepository $autorisationRepository): Response
    {
        return $this->render('autorisation/index.html.twig', [
            'autorisations' => $autorisationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/autorisebilanhydrique/{id}", name="autorisebilanhydrique", methods={"GET", "POST"})
     */
    public function autorisebilanhydrique(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setBilanhydrique(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

            return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/interdirebilanhydrique/{id}", name="interdirebilanhydrique", methods={"GET", "POST"})
     */
    public function interdirebilanhydrique(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setBilanhydrique(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/autorisetemperatureAgrigole/{id}", name="autorisetemperatureAgrigole", methods={"GET", "POST"})
     */
    public function autorisetemperatureAgrigole(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setTemperatureAgrigole(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/interdiretemperatureAgrigole/{id}", name="interdiretemperatureAgrigole", methods={"GET", "POST"})
     */
    public function interdiretemperatureAgrigole(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setTemperatureAgrigole(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }



    /**
     * @Route("/autorisetemperatureSante/{id}", name="autorisetemperatureSante", methods={"GET", "POST"})
     */
    public function autorisetemperatureSante(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setTemperatureSante(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/interdiretemperatureSante/{id}", name="interdiretemperatureSante", methods={"GET", "POST"})
     */
    public function interdiretemperatureSante(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setTemperatureSante(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }


    /**
     * @Route("/autoriseblood/{id}", name="autoriseblood", methods={"GET", "POST"})
     */
    public function autoriseblood(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setBlood(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/interdireblood/{id}", name="interdireblood", methods={"GET", "POST"})
     */
    public function interdireblood(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setBlood(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/autoriseheartbeat/{id}", name="autoriseheartbeat", methods={"GET", "POST"})
     */
    public function autoriseheartbeat(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setHeartbeat(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/interdireheartbeat{id}", name="interdireheartbeat", methods={"GET", "POST"})
     */
    public function interdireheartbeat(Request $request,$id, AutorisationRepository $autorisationRepository): Response
    {
        $autorisation = $autorisationRepository->find($id);
        $autorisation->setHeartbeat(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($autorisation);
        $em->flush();

        return $this->redirectToRoute('app_autorisation_index', [], Response::HTTP_SEE_OTHER);

    }

}
