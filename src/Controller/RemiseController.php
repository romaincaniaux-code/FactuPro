<?php

namespace App\Controller;

use App\Entity\Remise;
use App\Form\RemiseType;
use App\Repository\RemiseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/remise')]
class RemiseController extends AbstractController
{
    // ! liste de toutes les remises
    #[Route(name: 'app_remise_index', methods: ['GET'])]
    public function index(RemiseRepository $remiseRepository): Response
    {
        $remises = $remiseRepository->findAll();

        return $this->render('remise/index.html.twig', [
            'remises' => $remises,
        ]);
    }

    // ! ajout d'une remise
    #[Route('/new', name: 'app_remise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $remise = new Remise();
        $form = $this->createForm(RemiseType::class, $remise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($remise);
            $entityManager->flush();

            return $this->redirectToRoute('app_remise_index');
        }

        return $this->render('remise/new.html.twig', [
            'remise' => $remise,
            'form' => $form->createView(),
        ]);
    }

    // ! détail d'une remise
    #[Route('/{id}', name: 'app_remise_show', methods: ['GET'])]
    public function show(int $id, RemiseRepository $remiseRepository): Response
    {
        $remise = $remiseRepository->find($id);

        if ($remise === null) {
            throw $this->createNotFoundException('Remise introuvable');
        }

        return $this->render('remise/show.html.twig', [
            'remise' => $remise,
        ]);
    }

    // ! modification d'une remise
    #[Route('/{id}/edit', name: 'app_remise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, RemiseRepository $remiseRepository, EntityManagerInterface $entityManager): Response
    {
        $remise = $remiseRepository->find($id);

        if ($remise === null) {
            throw $this->createNotFoundException('Remise introuvable');
        }

        $form = $this->createForm(RemiseType::class, $remise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_remise_index');
        }

        return $this->render('remise/edit.html.twig', [
            'remise' => $remise,
            'form' => $form->createView(),
        ]);
    }

    // ! suppression d'une remise (formulaire POST protégé par un jeton CSRF)
    #[Route('/{id}', name: 'app_remise_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, RemiseRepository $remiseRepository, EntityManagerInterface $entityManager): Response
    {
        $remise = $remiseRepository->find($id);

        if ($remise === null) {
            throw $this->createNotFoundException('Remise introuvable');
        }

        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $remise->getId(), $token)) {
            $entityManager->remove($remise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_remise_index');
    }
}