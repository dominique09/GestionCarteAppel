<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntervenantRepository")
 */
class Intervenant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide.")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(message="Le nom de famille ne peut pas être vide.")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=4, unique=true)
     */
    private $initiales;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Division", inversedBy="intervenants")
     * @Assert\NotBlank(message="La division est obligatoire")
     */
    private $division;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Formation", inversedBy="intervenants")
     * @Assert\NotBlank(message="La formation est obligatoire")
     */
    private $formation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Equipe", mappedBy="intervenants", cascade={"persist"}, fetch="LAZY")
     *
     */
    private $equipes;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->isActive = true;
        $this->equipes = new ArrayCollection();
    }

    /**
     *
     */
    public function isActive(){
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param mixed $formation
     */
    public function setFormation($formation): void
    {
        $this->formation = $formation;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
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
    public function getInitiales()
    {
        return $this->initiales;
    }

    /**
     * @param mixed $initiales
     */
    public function setInitiales($initiales): void
    {
        $this->initiales = $initiales;
    }

    /**
     * @return mixed
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * @param mixed $division
     */
    public function setDivision($division): void
    {
        $this->division = $division;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
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
        if($this->equipes->contains($equipe)) {
            return;
        }

        $this->equipes[] = $equipe;
    }

    public function removeEquipe(Equipe $equipe){
        $this->equipes->remove($equipe);
    }

    public function isAssigned(): bool
    {
        return $this->equipes->exists(
            function($key,Equipe $equipe){
                return $equipe->isActive();
        });
    }
}
