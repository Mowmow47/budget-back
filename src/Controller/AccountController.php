<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used for (bank) account management.
 * It currently retrieves all the account in the database as the api is still in its early stages of development.
 */
#[Route('/accounts', name: 'app_accounts_')]
class AccountController extends AbstractController
{
    /**
     * Get Accounts
     * GET /accounts
     * Returns a list of accounts (all the accounts stored in the database for now).
     */
    #[Route('', name: 'browse')]
    public function browse(AccountRepository $accountRepository): Response
    {
        $accounts = $accountRepository->findAccountsWithTransactions();

        return $this->render('account/browse.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Read Account
     * GET /accounts/{id}
     * Returns the Account object for the given id. It uses parameter conversion to find the account associated with the specified id.
     */
    #[Route('/{id}', name: 'read', requirements: ['id' => '\d+'])]
    public function read(Account $account): Response
    {
        return $this->render('account/browse.html.twig', [
            'account' => $account,
        ]);
    }

    /**
     * Add Account
     * POST /accounts
     * Add a new Account object in the database.
     */
    #[Route('', name: 'add')]
    public function add(AccountRepository $accountRepository, Request $request): Response
    {
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            /**
             * I call the add method of the repository, natively present since symfony 5.4/6.0 from memory,
             * instead of calling doctrine and persisting and flushing the entity directly here.
             */
            $accountRepository->add($account, true);
            
            return $this->redirectToRoute('app_accounts_browse');
        }

        return $this->renderForm('account/add.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    /**
     * Edit Account
     * PATCH /accounts/{id}
     * Edit the Account object for the given id.
     */
    #[Route('/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Account $account, ManagerRegistry $doctrine, Request $request): Response
    {
        $form = $this->createForm(AccountType::class, $account);

        $json = $request->getContent();
        $jsonArray = json_decode($json, true);

        $form->submit($jsonArray);

        if($form->isValid()) {

            $em = $doctrine->getManager();
            $em->flush();
            
            return $this->redirectToRoute('app_accounts_read', [
                'id' => $account->getId()
            ]);
        }

        return $this->renderForm('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    /**
     * Delete Account
     * DELETE /accounts/{id}
     * Delete the Account object for the given id.
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Account $account, AccountRepository $accountRepository)
    {
        $accountRepository->remove($account, true);

        return $this->redirectToRoute('app_accounts_browse');
    }
}