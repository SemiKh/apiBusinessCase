<?php

namespace App\Controller\Back;

use App\Entity\SousCategorie;
use App\Form\SousCategorieType;
use App\Repository\SousCategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sousCategorie', name: 'app_admin_sous_categorie_')]
class SousCategorieController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(SousCategorieRepository $sousCategorieRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $sous_categories = $paginator->paginate(
            $sousCategorieRepository->findAll(),
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('back/pages/sous_categorie/index.html.twig', [
            'sous_categories' => $sous_categories,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, SousCategorieRepository $sousCategorieRepository): Response
    {
        $sousCategorie = new SousCategorie();
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sousCategorieRepository->save($sousCategorie, true);

            return $this->redirectToRoute('app_admin_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/sous_categorie/new.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(SousCategorie $sousCategorie): Response
    {
        return $this->render('back/pages/sous_categorie/show.html.twig', [
            'sous_categorie' => $sousCategorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SousCategorie $sousCategorie, SousCategorieRepository $sousCategorieRepository): Response
    {
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sousCategorieRepository->save($sousCategorie, true);

            return $this->redirectToRoute('app_admin_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/sous_categorie/edit.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, SousCategorie $sousCategorie, SousCategorieRepository $sousCategorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sousCategorie->getId(), $request->request->get('_token'))) {
            $sousCategorieRepository->remove($sousCategorie, true);
        }

        return $this->redirectToRoute('app_admin_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
