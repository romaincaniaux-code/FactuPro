<?php

namespace App\Entity;

use App\Repository\LigneDocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// ! un même produit ne peut apparaître qu'une fois par document
#[ORM\Entity(repositoryClass: LigneDocumentRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_document_produit', columns: ['document_id', 'produit_id'])]
class LigneDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixUnitaireHT = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $tauxTVA = null;

    #[ORM\ManyToOne(inversedBy: 'lignes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Document $document = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    // ! facultative : une ligne peut ne pas avoir de remise
    #[ORM\ManyToOne]
    private ?Remise $remise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaireHT(): ?string
    {
        return $this->prixUnitaireHT;
    }

    public function setPrixUnitaireHT(string $prixUnitaireHT): static
    {
        $this->prixUnitaireHT = $prixUnitaireHT;

        return $this;
    }

    public function getTauxTVA(): ?string
    {
        return $this->tauxTVA;
    }

    public function setTauxTVA(string $tauxTVA): static
    {
        $this->tauxTVA = $tauxTVA;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getRemise(): ?Remise
    {
        return $this->remise;
    }

    public function setRemise(?Remise $remise): static
    {
        $this->remise = $remise;

        return $this;
    }
}