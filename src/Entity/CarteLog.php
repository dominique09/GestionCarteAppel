<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarteLogRepository")
 */
class CarteLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Carte", inversedBy="logs", cascade={"persist"})
     */
    private $carte;

    /**
     * @ORM\Column(length=250)
     */
    private $message;

    /**
     * @ORM\Column(length=30)
     */
    private $loggedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $loggedAt;

    public function __construct($message)
    {
        $this->message = $message;
        $this->loggedAt = new \DateTime();
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
     * @param $carte
     */
    public function setCarte($carte): void
    {
        $this->carte = $carte;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * @return mixed
     */
    public function getLoggedBy()
    {
        return $this->loggedBy;
    }

    /**
     * @param mixed $loggedBy
     */
    public function setLoggedBy($loggedBy): void
    {
        $this->loggedBy = $loggedBy;
    }

    /**
     * @return string
     */
    public function toString(){
        $retour = "===== {$this->loggedBy} -";
        $retour.= date_format($this->loggedAt, 'Y-m-d H:i:s');
        $retour.= "=====\r\n";
        $retour.= $this->message;

        return $retour;
    }
}
