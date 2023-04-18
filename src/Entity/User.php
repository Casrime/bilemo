<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'method' => 'GET',
            'normalization_context' => ['groups' => ['list']],
        ],
        'post' => [
            'method' => 'POST',
            'denormalization_context' => ['groups' => ['add']],
        ],
    ],
    itemOperations: [
        'get' => ['method' => 'GET'],
        'delete' => ['method' => 'DELETE'],
    ]
)]
#[ORM\Table(name: 'bilemo_user')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'Cet identifiant est déjà utilisé')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['list'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['list', 'add'])]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Length(min: 2, max: 30, minMessage: 'Merci de renseigner un minimum de {{ limit }} caractères', maxMessage: 'Merci de renseigner un maximum de {{ limit }} caractères')]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['list', 'add'])]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Length(min: 2, max: 30, minMessage: 'Merci de renseigner un minimum de {{ limit }} caractères', maxMessage: 'Merci de renseigner un maximum de {{ limit }} caractères')]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['list', 'add'])]
    #[Assert\NotBlank(message: 'Ce champ ne doit pas être vide')]
    #[Assert\Length(min: 2, max: 30, minMessage: 'Merci de renseigner un minimum de {{ limit }} caractères', maxMessage: 'Merci de renseigner un maximum de {{ limit }} caractères')]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdayDate = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'users')]
    #[Groups(['list', 'add'])]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getBirthdayDate(): ?\DateTimeInterface
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate(\DateTimeInterface $birthdayDate): self
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
