<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AssignationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Assignation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Carte", inversedBy="assignations", cascade={"persist"})
     */
    private $carte;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipe", inversedBy="assignations", cascade={"persist"})
     */
    private $equipe;

    /**
     * @ORM\Column(type="datetime")
     */
    private $assignedAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dispatchedAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $directionPatientAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $arrivedPatientAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $directionChAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $arrivedChAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cancelledAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closedAt;

    private $etapes = [
        'assignedAt' => [
        'name' => 'assignedAt',
        'libele' => 'Ass.',
        'previousRequired' => null,
        'ifValueDisabled' => true,
        'displayColorActive' => 'secondary',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => null,
    ],'dispatchedAt'=>[
        'name' => 'dispatchedAt',
        'libele' => 'Rep.',
        'previousRequired' => 'assignedAt',
        'ifValueDisabled' => true,
        'displayColorActive' => 'warning',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => null,
    ],'directionPatientAt'=>[
        'name' => 'directionPatientAt',
        'libele' => '-16 <small>(PA)</small>',
        'previousRequired' => 'dispatchedAt',
        'ifValueDisabled' => true,
        'displayColorActive' => 'info',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => null,
    ],'arrivedPatientAt' => [
        'name' => 'arrivedPatientAt',
        'libele' => '-17 <small>(PA)</small>',
        'previousRequired' => 'directionPatientAt',
        'ifValueDisabled' => true,
        'displayColorActive' => 'danger',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => null,
    ],'directionChAt' => [
        'name' => 'directionChAt',
        'libele' => '-16 <small>(CH)</small>',
        'previousRequired' => 'arrivedPatientAt',
        'ifValueDisabled' => true,
        'displayColorActive' => 'warning',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => ['Volante'],
    ],'arrivedChAt' => [
        'name' => 'arrivedChAt',
        'libele' => '-17 <small>(CH)</small>',
        'previousRequired' => 'directionChAt',
        'ifValueDisabled' => true,
        'displayColorActive' => 'danger',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => ['Volante'],
    ],'closedVolanteAt' => [
        'name' => 'closedAt',
        'libele' => '05',
        'previousRequired' => 'arrivedChAt',
        'ifValueDisabled' => true,
        'displayColorActive' => 'warning',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => ['Volante'],
    ],'closedTerrainAt' => [
        'name' => 'closedAt',
        'libele' => '05',
        'previousRequired' => 'arrivedPatientAt',
        'ifValueDisabled' => true,
        'displayColorActive' => 'warning',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => ['Terrain'],
    ],'cancelledAt' => [
        'name' => 'cancelledAt',
        'libele' => '03',
        'previousRequired' => null,
        'ifValueDisabled' => true,
        'displayColorActive' => 'danger',
        'displayColorDisabled' => 'secondary',
        'typesEquipe' => null,
    ],];

    public function __construct()
    {
        $this->assignedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(){
        $this->carte->setUpdatedAt(new \DateTime());
        $this->equipe->setUpdatedAt(new \DateTime());
    }

    public function getProperty($prop){
        if($prop)
            return $this->$prop;

        return null;
    }

    public function setProperty($prop, $value){
        $this->$prop = $value;
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
    public function getCarte()
    {
        return $this->carte;
    }

    /**
     * @param mixed $carte
     */
    public function setCarte($carte): void
    {
        $this->carte = $carte;
    }

    /**
     * @return mixed
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * @param mixed $equipe
     */
    public function setEquipe($equipe): void
    {
        $this->equipe = $equipe;
    }

    /**
     * @return mixed
     */
    public function getDispatchedAt()
    {
        return $this->dispatchedAt;
    }

    /**
     * @param mixed $dispatchedAt
     */
    public function setDispatchedAt($dispatchedAt): void
    {
        $this->dispatchedAt = $dispatchedAt;
    }

    /**
     * @return \DateTime
     */
    public function getAssignedAt(): \DateTime
    {
        return $this->assignedAt;
    }

    /**
     * @param \DateTime $assignedAt
     */
    public function setAssignedAt(\DateTime $assignedAt): void
    {
        $this->assignedAt = $assignedAt;
    }

    /**
     * @return mixed
     */
    public function getDirectionPatientAt()
    {
        return $this->directionPatientAt;
    }

    /**
     * @param mixed $directionPatientAt
     */
    public function setDirectionPatientAt($directionPatientAt): void
    {
        $this->directionPatientAt = $directionPatientAt;
    }

    /**
     * @return mixed
     */
    public function getArrivedPatientAt()
    {
        return $this->arrivedPatientAt;
    }

    /**
     * @param mixed $arrivedPatientAt
     */
    public function setArrivedPatientAt($arrivedPatientAt): void
    {
        $this->arrivedPatientAt = $arrivedPatientAt;
    }

    /**
     * @return mixed
     */
    public function getDirectionChAt()
    {
        return $this->directionChAt;
    }

    /**
     * @param mixed $directionChAt
     */
    public function setDirectionChAt($directionChAt): void
    {
        $this->directionChAt = $directionChAt;
    }

    /**
     * @return mixed
     */
    public function getArrivedChAt()
    {
        return $this->arrivedChAt;
    }

    /**
     * @param mixed $arrivedChAt
     */
    public function setArrivedChAt($arrivedChAt): void
    {
        $this->arrivedChAt = $arrivedChAt;
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

    /**
     * @return mixed
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    public function isEnCours()
    {
        return ($this->getAssignedAt() &&
            !$this->getClosedAt() &&
            !$this->getCancelledAt());
    }

    /**
     * @param mixed $closedAt
     */
    public function setClosedAt($closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    public function getColor(){
        if($this->closedAt)
            return "secondary";
        if($this->cancelledAt)
            return "danger";

        if($this->assignedAt && !$this->dispatchedAt)
            return "info";

        return "warning";
    }

    /**
     * @return array
     */
    public function getEtapes(): array
    {
        return $this->etapes;
    }
}
