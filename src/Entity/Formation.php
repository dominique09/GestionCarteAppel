<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormationRepository")
 * @UniqueEntity(fields={"nom"}, message="Le nom est déjà utilisé.")
 * @UniqueEntity(fields={"abreviation"}, message="Cet abréviation est déjà utilisé.")
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Le nom ne peut pas être vide.")
     * @Assert\Length(
     *      min = 1,
     *      max = 30,
     *      minMessage = "Le nom doit avoir un minimum de {{ limit }} caractères.",
     *      maxMessage = "Le nom doit avoir un maximum de {{ limit }} caractères."
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=6, unique=true)
     * @Assert\NotBlank(message="L'abréviation ne peut pas être vide.")
     * @Assert\Length(
     *      min = 2,
     *      max = 6,
     *      minMessage = "L'abréviation doit avoir un minimum de {{ limit }} caractères.",
     *      maxMessage = "L'abréviation doit avoir un maximum de {{ limit }} caractères."
     * )
     */
    private $abreviation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervenant", mappedBy="formation")
     */
    private $intervenants;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Vous devez choisir une couleur.")
     */
    private $color;

    public function __construct()
    {
        $this->color = "secondary";
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getAbreviation()
    {
        return $this->abreviation;
    }

    /**
     * @param mixed $abreviation
     */
    public function setAbreviation($abreviation): void
    {
        $this->abreviation = $abreviation;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIntervenants()
    {
        return $this->intervenants;
    }

    /**
     * @param mixed $intervenants
     */
    public function setIntervenants($intervenants): void
    {
        $this->intervenants = $intervenants;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }
}
