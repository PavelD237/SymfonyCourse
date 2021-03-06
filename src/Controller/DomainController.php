<?php

namespace App\Controller;

use App\Entity\Domain;
use App\Form\DomainType;
use App\Repository\DomainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/domain")
 */
class DomainController extends AbstractController
{
    /**
     * @Route("/", name="domain_index", methods={"GET"})
     */
    public function index(DomainRepository $domainRepository): Response
    {
        return $this->render('domain/index.html.twig', [
            'domains' => $domainRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="domain_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $domain = new Domain();
        $form = $this->createForm(DomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($domain);
            $entityManager->flush();

            return $this->redirectToRoute('domain_index');
        }

        return $this->render('domain/new.html.twig', [
            'domain' => $domain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="domain_show", methods={"GET"})
     */
    public function show(Domain $domain): Response
    {
        return $this->render('domain/show.html.twig', [
            'domain' => $domain,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="domain_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Domain $domain): Response
    {
        $form = $this->createForm(DomainType::class, $domain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('domain_index');
        }

        return $this->render('domain/edit.html.twig', [
            'domain' => $domain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="domain_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Domain $domain): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domain->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($domain);
            $entityManager->flush();
        }

        return $this->redirectToRoute('domain_index');
    }
}
