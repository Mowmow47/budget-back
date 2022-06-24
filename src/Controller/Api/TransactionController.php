<?php

namespace App\Controller\Api;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used for transactions management.
 */
#[Route('/api/transactions', name: 'api_transactions_')]
class TransactionController extends AbstractController
{
    /**
     * Add Transaction
     * POST /transactions
     * Add a new Transaction object in the database.
     */
    #[Route('', name: 'add', methods: ['POST'])]
    public function add(Request $request, TransactionRepository $transactionRepository): JsonResponse
    {
        $transaction = new Transaction();

        $form = $this->createForm(TransactionType::class, $transaction);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            $transactionRepository->add($transaction, true);
            
            return $this->json($transaction, Response::HTTP_CREATED, [], [
                'groups' => ['transaction']
            ]);
        }

        $errorsString = (string) $form->getErrors(true);

        return $this->json($errorsString, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Edit Transaction
     * PATCH /transactions/{id}
     * Edit the Transaction object for the given id.
     */
    #[Route('/{id}', name: 'edit', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function edit(ManagerRegistry $doctrine, Request $request, Transaction $transaction): JsonResponse
    {
        $form = $this->createForm(TransactionType::class, $transaction);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            $em = $doctrine->getManager();
            $em->flush();
            
            return $this->json($transaction, Response::HTTP_OK, [], [
                'groups' => ['transaction']
            ]);
        }

        $errorsString = (string) $form->getErrors(true);

        return $this->json($errorsString, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete Transaction
     * DELETE /transactions/{id}
     * Delete the Transaction object for the given id.
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Transaction $transaction, TransactionRepository $transactionRepository)
    {
        $transactionRepository->remove($transaction, true);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
