<?php

namespace App\Controller\Back;

use App\Repository\NftRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        UserRepository $userRepository,
        NftRepository $nftRepository,
        TransactionRepository $transactionRepository

    ): Response
    {
        return $this->render('back/pages/dashboard.html.twig', [
            'users'=> $userRepository->findBy([], ['createdAt' => 'DESC'], 4),
            'nfts'=> $nftRepository->findBy([], ['dateDrop' => 'DESC'], 4),
            'transactions'=> $transactionRepository->findBy([], ['dateAcquisition' => 'DESC'], 4),
        ]);
    }
}
