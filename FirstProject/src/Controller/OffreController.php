<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\DeviceRepository;
use App\Repository\DomaineApplicationRepository;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offre")
 */
class OffreController extends AbstractController
{
    /**
     * @Route("/", name="app_offre_index", methods={"GET"})
     */
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }
    /**
     * @Route("deleteoffre/{id}", name="deleteoffre")
     */
    public function deleteoffre(OffreRepository $Repository,$id): Response
    {
        $d = $Repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($d);
        $em->flush();
        return $this->redirectToRoute("app_offre_index");
    }
    /**
     * @Route("/new", name="app_offre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OffreRepository $offreRepository): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }
    /**
     * @Route("showoffrepardomaine/{id}", name="showoffrepardomaine", methods={"GET"})
     */
    public function showoffrepardomaine(OffreRepository $offreRepository,$id,DomaineApplicationRepository $domaineApplicationRepository): Response
    {$offres=$offreRepository->offrepardomaine($id);
        $d=$domaineApplicationRepository->find($id);
        return $this->render('offre/_form.html.twig', [
            'offres' => $offres,'user'=>$this->getUser(),'domaine'=>$domaineApplicationRepository->findAll(),'dom'=>$d
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_offre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreRepository->add($offre, true);

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_offre_delete", methods={"POST"})
     */
    public function delete(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $offreRepository->remove($offre, true);
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}
