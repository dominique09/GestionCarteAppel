<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Carte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $clawson;
    /**
     * @ORM\Column()
     */
    private $emplacement;
    /**
     * @ORM\Column(type="integer")
     */
    private $priorite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Appelant", inversedBy="cartes")
     */
    private $appelant;

    /**
     * @ORM\Column()
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cancelledAt;

    /**
     * @ORM\Column(nullable=true)
     */
    private $raisonCancelled;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CarteLog", mappedBy="carte", cascade={"persist"})
     */
    private $logs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assignation", mappedBy="carte", cascade={"persist"})
     */
    private $assignations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $volanteDemande;

    private $editingUserInitiales = "N/A";

    public function __construct()
    {
        $this->assignations = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->description = "N/A";
        $this->volanteDemande = false;
        $this->priorite = 3;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(){
        $this->updatedAt = new \DateTime();
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
    public function getClawson()
    {
        return $this->clawson;
    }

    /**
     * @param mixed $clawson
     */
    public function setClawson($clawson): void
    {
        $this->clawson = $clawson;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param mixed $closedAt
     */
    public function setClosedAt($closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    /**
     * @return mixed
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }

    /**
     * @param mixed $emplacement
     */
    public function setEmplacement($emplacement): void
    {
        $this->emplacement = $emplacement;
    }

    /**
     * @return mixed
     */
    public function getPriorite()
    {
        return $this->priorite;
    }

    /**
     * @param mixed $priorite
     */
    public function setPriorite($priorite): void
    {
        $this->priorite = $priorite;
    }

    /**
     * @return mixed
     */
    public function getAppelant()
    {
        return $this->appelant;
    }

    /**
     * @param mixed $appelant
     */
    public function setAppelant($appelant): void
    {
        $this->appelant = $appelant;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getEditingUserInitiales(): string
    {
        return $this->editingUserInitiales;
    }

    /**
     * @param string $editingUserInitiales
     */
    public function setEditingUserInitiales(string $editingUserInitiales): void
    {
        $this->editingUserInitiales = $editingUserInitiales;
    }

    /**
     * @return mixed
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param mixed $logs
     */
    public function setLogs($logs): void
    {
        $this->logs = $logs;
    }

    public function addLog(CarteLog $log){
        if($this->logs->contains($log))
            return;

        $this->logs[] = $log;
        $log->setCarte($this);
        $log->setLoggedBy($this->editingUserInitiales);
    }

    public function dateToString($date){
        if($date){
            return date_format($date, 'Y-m-d H:i:s');
        }

        return "null";
    }

    public function getStatusColor(){
        return "light";
    }

    /**
     * @return mixed
     */
    public function getVolanteDemande()
    {
        return $this->volanteDemande;
    }

    /**
     * @param mixed $volanteDemande
     */
    public function setVolanteDemande($volanteDemande): void
    {
        $this->volanteDemande = $volanteDemande;
    }

    /**
     * @return mixed
     */
    public function getAssignations()
    {
        return $this->assignations;
    }

    /**
     * @param mixed $assignations
     */
    public function setAssignations($assignations): void
    {
        $this->assignations = $assignations;
    }

    public function addAssignation(Assignation $assignation)
    {
        if($this->assignations->contains($assignation))
            return;

        $this->assignations[] = $assignation;
        $assignation->setCarte($this);

        $this->updatedAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getCancelledAt()
    {
        return $this->cancelledAt;
    }

    /**
     * @param mixed $cancelledAt
     */
    public function setCancelledAt($cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }

    public function isActive()
    {
        return !($this->closedAt || $this->cancelledAt);
    }

    public function getAssignationsEnCours()
    {
        $retour = new ArrayCollection();

        foreach ($this->assignations as $assignation){
            if($assignation->isEnCours())
                $retour[] = $assignation;
        }

        return $retour;
    }

    /**
     * @return mixed
     */
    public function getRaisonCancelled()
    {
        return $this->raisonCancelled;
    }

    /**
     * @param mixed $raisonCancelled
     */
    public function setRaisonCancelled($raisonCancelled): void
    {
        $this->raisonCancelled = $raisonCancelled;
    }
}
