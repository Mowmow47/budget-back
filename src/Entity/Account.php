<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["account"])]
    private $id;

    #[ORM\Column(type: 'string', length: 30)]
    #[Groups(["account"])]
    private $name;
    
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["account"])]
    private $accountNumber;
    
    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    #[Groups(["account"])]
    private $bankName;
    
    #[ORM\Column(type: 'float', nullable: true)]
    private $interestRate;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Transaction::class, orphanRemoval: true)]
    #[Groups(["account"])]
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }
    
    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;
        
        return $this;
    }
    
    public function getBankName(): ?string
    {
        return $this->bankName;
    }
    
    public function setBankName(?string $bankName): self
    {
        $this->bankName = $bankName;
        
        return $this;
    }

    public function getInterestRate(): ?float
    {
        return $this->interestRate;
    }
    
    public function setInterestRate(?float $interestRate): self
    {
        $this->interestRate = $interestRate;
    
        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setAccount($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getAccount() === $this) {
                $transaction->setAccount(null);
            }
        }

        return $this;
    }
}
