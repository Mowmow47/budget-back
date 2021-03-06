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
     * Returns a list of accounts (all the accounts stored in the database for now).
     */
    #[Route('', name: 'browse')]
    public function browse(AccountRepository $accountRepository): Response
    {
        $accounts = $accountRepository->findAll();

        return $this->render('account/browse.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Read Account
     * Returns the Account object for the given id with all the related transactions.
     */
    #[Route('/{id}', name: 'read', requirements: ['id' => '\d+'])]
    public function read(Account $account, AccountRepository $accountRepository): Response
    {
        $accountId = $account->getId();
        $account = $accountRepository->findAccountsWithTransactions($accountId);

        return $this->render('account/read.html.twig', [
            'account' => $account,
        ]);
    }

    /**
     * Add Account
     * Add a new Account object in the database.
     */
    #[Route('/add', name: 'add')]
    public function add(AccountRepository $accountRepository, Request $request): Response
    {
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            if($form->isValid()) {
                /**
                 * I call the add method of the repository, natively present since symfony 5.4/6.0 from memory,
                 * instead of calling doctrine and persisting and flushing the entity directly here.
                 */
                $accountRepository->add($account, true);
                
                return $this->redirectToRoute('app_accounts_browse');
            }
        }

        return $this->renderForm('account/add.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    /**
     * Edit Account
     * Edit the Account object for the given id.
     */
    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Account $account, ManagerRegistry $doctrine, Request $request): Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
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
     * Delete the Account object for the given id.
     */
    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Account $account, AccountRepository $accountRepository)
    {
        $accountRepository->remove($account, true);

        return $this->redirectToRoute('app_accounts_browse');
    }
}