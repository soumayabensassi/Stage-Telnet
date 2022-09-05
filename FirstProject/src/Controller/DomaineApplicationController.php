<?php

namespace App\Controller;

use App\Entity\DomaineApplication;
use App\Form\DomaineApplicationType;
use App\Repository\DeviceRepository;
use App\Repository\DomaineApplicationRepository;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/domaine_application")
 */
class DomaineApplicationController extends AbstractController
{

    /**
     * @Route("/domaine_show", name="app_domaine_show", methods={"GET"})
     */
    public function show1(DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        return $this->render('domaine_application/show.html.twig', [
            'domaine' => $domaineApplicationRepository->findAll(),'user'=>$this->getUser()
        ]);
    }
    /**
 * @Route("/new", name="app_domaine_application_new", methods={"GET", "POST"})
 */
    public function new(Request $request, DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        $domaineApplication = new DomaineApplication();
        $form = $this->createForm(DomaineApplicationType::class, $domaineApplication);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $domaineApplicationRepository->add($domaineApplication, true);

            return $this->redirectToRoute('app_domaine_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domaine_application/new.html.twig', [
            'domaine_application' => $domaineApplication,
            'form' => $form,'user'=>$this->getUser(),
        ]);
    }

    /**
     * @Route("/", name="app_domaine_application_index", methods={"GET"})
     */
    public function index(DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        return $this->render('domaine_application/index.html.twig', [
            'domaine_applications' => $domaineApplicationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}", name="delete")
     */
    public function delete1(DomaineApplicationRepository $domaineApplicationRepository,$id): Response
    {
        $d = $domaineApplicationRepository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($d);
        $em->flush();
        return $this->redirectToRoute("app_domaine_application_index");
    }

    /**
     * @Route("/{id}", name="app_domaine_application_show", methods={"GET"})
     */
    public function show(DomaineApplication $domaineApplication): Response
    {
        return $this->render('domaine_application/show.html.twig', [
            'domaine_application' => $domaineApplication,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_domaine_application_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DomaineApplication $domaineApplication, DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        $form = $this->createForm(DomaineApplicationType::class, $domaineApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $domaineApplicationRepository->add($domaineApplication, true);

            return $this->redirectToRoute('app_domaine_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domaine_application/edit.html.twig', [
            'domaine_application' => $domaineApplication,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_domaine_application_delete", methods={"POST"})
     */
    public function delete(Request $request, DomaineApplication $domaineApplication, DomaineApplicationRepository $domaineApplicationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaineApplication->getId(), $request->request->get('_token'))) {
            $domaineApplicationRepository->remove($domaineApplication, true);
        }

        return $this->redirectToRoute('app_domaine_application_index', [], Response::HTTP_SEE_OTHER);
    }
}
