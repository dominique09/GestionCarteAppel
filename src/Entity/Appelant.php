<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppelantRepository")
 */
class Appelant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Carte", mappedBy="appelant")
     */
    private $cartes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

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
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $isActive
     */
    public function setActive($isActive): void
    {
        $this->active = $isActive;
    }

    /**
     * @return mixed
     */
    public function getCartes()
    {
        return $this->cartes;
    }

    /**
     * @param mixed $cartes
     */
    public function setCartes($cartes): void
    {
        $this->cartes = $cartes;
    }
}
