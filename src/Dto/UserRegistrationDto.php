<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

// ! objet qui contient seulement les données du formulaire d'inscription
class UserRegistrationDto
{
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "Cette adresse email n'est pas valide.")]
    public ?string $email = null;

    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire.')]
    #[Assert\Length(min: 8, minMessage: 'Le mot de passe doit contenir au moins 8 caractères.')]
    public ?string $plainPassword = null;
}