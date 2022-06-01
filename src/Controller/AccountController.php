<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used for (bank) account management.
 * It currently retrieves all the account in the database as the api is still in its early stages of development.
 */
#[Route('/accounts', name: 'api_accounts_')]
class AccountController extends AbstractController
{
    /**
     * Get Accounts
     * GET /accounts
     * Returns a list of account (all the accounts stored in the database for now).
     */
    #[Route('', name: 'browse', methods: ['GET'])]
    public function browse(AccountRepository $accountRepository): JsonResponse
    {
        $accounts = $accountRepository->findAll();

        return $this->json($accounts, Response::HTTP_OK);
    }

    /**
     * Get Account
     * GET /accounts/{id}
     * Return the Account object for the fiven id. It uses parameter conversion to find the account associated with the specified id.
     */
    #[Route('/{id}',name: 'read', methods: ['GET'])]
    public function read(Account $account): JsonResponse
    {
        return $this->json($account, Response::HTTP_OK);
    }

    /**
     * Add Account
     * POST /accounts
     * add a new Account object in the database.
     */
    #[Route('', name: 'add', methods: ['POST'])]
    public function add(AccountRepository $accountRepository, Request $request): JsonResponse
    {
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            /** I call the add method of the repository, natively present since symfony 5.4/6.0 from memory,
             * instead of calling doctrine and persisting and flushing the entity directly here. */
            $accountRepository->add($account, true);
            
            return $this->json($account, Response::HTTP_CREATED);
        }

        $errorsString = (string) $form->getErrors(true);

        return $this->json($errorsString, Response::HTTP_BAD_REQUEST);
    }
}