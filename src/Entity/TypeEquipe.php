<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeEquipeRepository")
 */
class TypeEquipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotBlank(message="Le nom doit être renseigné.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Vous devez choisir une couleur.")
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Equipe", mappedBy="typeEquipe")
     */
    private $equipes;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
        $this->color = "secondary";
    }

    /**
     * @return mixed
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    /**
     * @param mixed $equipes
     */
    public function setEquipes($equipes): void
    {
        $this->equipes = $equipes;
    }

    public function addEquipe(Equipe $equipe){
        if($this->equipes->contains($equipe)){
            return;
        }

        $this->equipes[] = $equipe;
        $equipe->setTypeEquipe($this);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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
    /*
        public function removeEquipe(Equipe $equipe){
            $this->equipes->remove($equipe);
            $equipe->setTypeEquipe(null);
        }
    */
}
