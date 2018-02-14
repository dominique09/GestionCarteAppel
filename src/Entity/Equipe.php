<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(length=5)
     * @Assert\NotBlank(message="L'identifiant de l'équipe est obligatoire.")
     */
    private $identifiant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeEquipe")
     * @Assert\NotBlank(message="Le type d'équipe doit être renseigné.")
     */
    private $typeEquipe;

    /**
     * @ORM\Column()
     */
    private $endroitPresent;

    /**
     * @ORM\Column(nullable=true)
     */
    private $endroitDestination;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $debutDispoAppels;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tempsIndispo;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $raisonIndispo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $retourVersCo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finDispoAppels;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dissolvedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Intervenant", inversedBy="equipes", cascade={"persist"})
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EquipeLog", mappedBy="equipe", cascade={"persist"})
     */
    private $logs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Assignation", mappedBy="equipe", cascade={"persist"})
     */
    private $assignations;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    private $editingUserInitiales;

    public function __construct()
    {
        $this->intervenants = new ArrayCollection();
        $this->assignations = new ArrayCollection();
        $this->logs = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new DateTime();
        $this->endroitPresent = "CO";

        $this->editingUserInitiales = "N/A";

        $this->addLog(
            new EquipeLog(
                "Création de l'équipe.")
        );
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

        $liste = "";
        foreach ($intervenants as $int){
            $liste .= " - ". $int->getFirstName() ." ". $int->getLastName() . " (".$int->getInitiales().")\r\n";
        }

        $this->addLog(
            new EquipeLog(
                "Modification des intervenants : $liste")
        );
    }

    /**
     * @param Intervenant $intervenant
     */
    public function addIntervenant(Intervenant $intervenant){
        if($this->intervenants->contains($intervenant)){
            return;
        }

        $this->intervenants[] = $intervenant;
        $intervenant->addEquipe($this);

        $this->addLog(
            new EquipeLog(
                "Ajout de l'intervenant : \r\n -- {$intervenant->getFirstname()} {$intervenant->getLastname()} - {$intervenant->getInitiales()}")
        );
    }

    /**
     * @param Intervenant $intervenant
     */
    public function removeIntervenant(Intervenant $intervenant){
        $this->intervenants->remove($intervenant);
        $intervenant->removeEquipe($this);

        $this->addLog(
            new EquipeLog(
                "Retrait de l'intervenant : \r\n -- {$intervenant->getFirstname()} {$intervenant->getLastname()} - {$intervenant->getInitiales()}")
        );
    }

    /**
     * @return mixed
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * @param mixed $identifiant
     */
    public function setIdentifiant($identifiant): void
    {
        $this->identifiant = $identifiant;

        $this->addLog(
            new EquipeLog(
                "Identifiant : {$identifiant}")
        );
    }

    /**
     * @return mixed
     */
    public function getTypeEquipe()
    {
        return $this->typeEquipe;
    }

    /**
     * @param mixed $typeEquipe
     */
    public function setTypeEquipe($typeEquipe): void
    {
        $this->typeEquipe = $typeEquipe;

        $this->addLog(
            new EquipeLog(
                "Type d'équipe : {$typeEquipe->getName()}")
        );
    }

    /**
     * @return mixed
     */
    public function getEndroitPresent()
    {
        return $this->endroitPresent;
    }

    /**
     * @param mixed $endroitPresent
     */
    public function setEndroitPresent($endroitPresent): void
    {
        $this->endroitPresent = $endroitPresent;

        $this->addLog(
            new EquipeLog(
                "Endroit Présent : $endroitPresent")
        );
    }

    /**
     * @return mixed
     */
    public function getEndroitDestination()
    {
        return $this->endroitDestination;
    }

    /**
     * @param mixed $endroitDestination
     */
    public function setEndroitDestination($endroitDestination): void
    {
        $this->endroitDestination = $endroitDestination;

        $this->addLog(
            new EquipeLog(
                "Endroit Destination : $endroitDestination")
        );
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
     * @return mixed
     */
    public function getDissolvedAt()
    {
        return $this->dissolvedAt;
    }

    /**
     * @param mixed $dissolvedAt
     */
    public function setDissolvedAt($dissolvedAt): void
    {
        $this->dissolvedAt = $dissolvedAt;

        $this->addLog(
            new EquipeLog(
                "Équipe Dissoute : ".$this->dateToString($dissolvedAt))
        );
    }

    public function isActive(){
        return ($this->createdAt &&
            !$this->dissolvedAt);
    }

    /**
     * @return mixed
     */
    public function getDebutDispoAppels()
    {
        return $this->debutDispoAppels;
    }

    /**
     * @param mixed $debutDispoAppels
     */
    public function setDebutDispoAppels($debutDispoAppels): void
    {
        $this->debutDispoAppels = $debutDispoAppels;

        $this->addLog(
            new EquipeLog(
                "Début Disponibilité (10-86) : ".$this->dateToString($debutDispoAppels))
        );
    }

    /**
     * @return mixed
     */
    public function getRetourVersCo()
    {
        return $this->retourVersCo;
    }

    /**
     * @param mixed $retourVersCo
     */
    public function setRetourVersCo($retourVersCo): void
    {
        $this->retourVersCo = $retourVersCo;

        $this->addLog(
            new EquipeLog(
                "Retour vers CO (10-87) : ".$this->dateToString($retourVersCo))
        );
    }

    /**
     * @return mixed
     */
    public function getFinDispoAppels()
    {
        return $this->finDispoAppels;
    }

    /**
     * @param mixed $finDispoAppels
     */
    public function setFinDispoAppels($finDispoAppels): void
    {
        $this->finDispoAppels = $finDispoAppels;


        $this->addLog(
            new EquipeLog(
                "Fin disponibilité (10-89) : ".$this->dateToString($finDispoAppels))
        );
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

    public function addLog(EquipeLog $log){
        if($this->logs->contains($log))
            return;

        $this->logs[] = $log;
        $log->setEquipe($this);
        $log->setLoggedBy($this->editingUserInitiales);
    }

    public function dateToString($date){
        if($date){
            return date_format($date, 'Y-m-d H:i:s');
        }

        return "null";
    }

    /**
     * @return string
     */
    public function getEditingUserInitiales(): string
    {
        return ($this->editingUserInitiales)?$this->editingUserInitiales:"N/A";
    }

    /**
     * @param string $editingUserInitiales
     */
    public function setEditingUserInitiales(string $editingUserInitiales): void
    {
        $this->editingUserInitiales = $editingUserInitiales;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPostUpdate(){
        $this->updatedAt = new DateTime();
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getTempsIndispo()
    {
        return $this->tempsIndispo;
    }

    /**
     * @param mixed $tempsIndispo
     */
    public function setTempsIndispo($tempsIndispo): void
    {
        $this->tempsIndispo = $tempsIndispo;

        $this->addLog(
            new EquipeLog(
                "Non Disponible (10-06) : ".$this->dateToString($tempsIndispo))
        );
    }

    /**
     * @return mixed
     */
    public function getRaisonIndispo()
    {
        return $this->raisonIndispo;
    }

    /**
     * @param mixed $raisonIndispo
     */
    public function setRaisonIndispo($raisonIndispo): void
    {
        $this->raisonIndispo = $raisonIndispo;

        $this->addLog(
            new EquipeLog(
                "Non Disponible (10-06) : $raisonIndispo")
        );
    }

    public function getDispoColor(){
        if(!$this->debutDispoAppels)
            return "danger";

        if($this->finDispoAppels)
            return "secondary";

        if($this->tempsIndispo)
            return "danger";

        if($this->endroitDestination)
            return "warning";

        if(count($this->getAssignationsEnCours()) > 0)
            return "primary";

        return "success";
    }

    public function hasAssignationEnCours(Carte $carte){
        foreach ($this->getAssignationsEnCours() as $assignation){
            if($carte->getId() == $assignation->getCarte()->getId()) {
                return true;
            }
        }

        return false;
    }

    public function getDisplayMessage(){
        $retour = "";

        if(!$this->debutDispoAppels)
            return "<span class='badge badge-pill badge-info'>En attente du début de quart.</span>";

        if($this->tempsIndispo)
        {
            $retour .= "<span class='badge badge-pill badge-warning'><i class=\"far fa-times-circle\"></i> $this->raisonIndispo</span>";
        }

        if($this->endroitDestination) {
            $retour .= "<span class='badge badge-pill badge-info'>$this->endroitPresent</span>";
            $retour .= " <i class=\"fas fa-arrow-circle-right\"></i> ";
            $retour .= "<span class='badge badge-pill badge-primary'>$this->endroitDestination</span>";
        }

        if(count($this->getAssignationsEnCours()) > 0){
            foreach ($this->getAssignationsEnCours() as $assignation){
                $retour .= "<span class='badge badge-pill badge-". $assignation->getCarte()->getStatusColor() ."'><i class=\"fas fa-ambulance\"></i> Carte ". $assignation->getCarte()->getId() ."</span>";
            }
        }

        if(empty($retour))
            $retour .= "<span class='badge badge-pill badge-info'><i class=\"far fa-clock\"></i> $this->endroitPresent</span>";

        return $retour;
    }

    /**
     * @return mixed
     */
    public function getAssignations()
    {
        return $this->assignations;
    }

    public function getAssignationsEnCours(){
        $retour = new ArrayCollection();

        foreach ($this->assignations as $assignation){
            if($assignation->isEnCours())
                $retour[] = $assignation;
        }

        return $retour;
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
        $assignation->setEquipe($this);

        $this->updatedAt = new \DateTime();
    }


}
