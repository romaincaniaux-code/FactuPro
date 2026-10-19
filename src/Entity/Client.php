<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

// ! refuse un email déjà utilisé par un autre client (message au lieu d'une erreur SQL)
#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé par un autre client.')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // ! lettres, accents, espace, apostrophe et tiret uniquement
    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(max: 100, maxMessage: 'Le nom ne doit pas dépasser 100 caractères.')]
    #[Assert\Regex(pattern: "/^[A-Za-zÀ-ÿ' -]+$/u", message: 'Le nom ne doit contenir que des lettres.')]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire.')]
    #[Assert\Length(max: 100, maxMessage: 'Le prénom ne doit pas dépasser 100 caractères.')]
    #[Assert\Regex(pattern: "/^[A-Za-zÀ-ÿ' -]+$/u", message: 'Le prénom ne doit contenir que des lettres.')]
    private ?string $prenom = null;

    // ! le format de l'email est contrôlé par Assert\Email, sans regex
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "Cette adresse email n'est pas valide.")]
    #[Assert\Length(max: 180, maxMessage: "L'email ne doit pas dépasser 180 caractères.")]
    private ?string $email = null;

    // ! facultatif : la regex ne s'applique que si le champ est rempli
    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Regex(pattern: '/^0[1-9]([ .-]?[0-9]{2}){4}$/', message: 'Numéro invalide (exemple : 02 35 12 34 56).')]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire.")]
    #[Assert\Length(max: 255, maxMessage: "L'adresse ne doit pas dépasser 255 caractères.")]
    private ?string $adresse = null;

    // ! exactement 5 chiffres
    #[ORM\Column(length: 5)]
    #[Assert\NotBlank(message: 'Le code postal est obligatoire.')]
    #[Assert\Regex(pattern: '/^[0-9]{5}$/', message: 'Le code postal doit contenir 5 chiffres.')]
    private ?string $codePostal = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'La ville est obligatoire.')]
    #[Assert\Length(max: 100, maxMessage: 'La ville ne doit pas dépasser 100 caractères.')]
    #[Assert\Regex(pattern: "/^[A-Za-zÀ-ÿ' -]+$/u", message: 'La ville ne doit contenir que des lettres.')]
    private ?string $ville = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
}