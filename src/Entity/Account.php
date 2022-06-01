<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 30)]
    private $name;
    
    #[ORM\Column(type: 'string', length: 255)]
    private $accountNumber;
    
    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    private $bankName;
    
    #[ORM\Column(type: 'float', nullable: true)]
    private $interestRate;
    
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
}
