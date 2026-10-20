<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    // ! liste de tous les produits
    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    // ! ajout d'un produit
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    // ! détail d'un produit
    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(int $id, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);

        if ($produit === null) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    // ! modification d'un produit
    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        $produit = $produitRepository->find($id);

        if ($produit === null) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    // ! suppression d'un produit (formulaire POST protégé par un jeton CSRF)
    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        $produit = $produitRepository->find($id);

        if ($produit === null) {
            throw $this->createNotFoundException('Produit introuvable');
        }

        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $token)) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index');
    }
}