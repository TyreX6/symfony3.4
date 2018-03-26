<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 10/03/2018
 * Time: 22:54
 */

namespace IT\ReservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Regles
 * @package IT\ReservationBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="Regles")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class Regles
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $limDureeReservation;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $nbrLimiteParJour;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $nbrLimiteParSemaine;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $nbrMaxReservParallelPar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_crea", type="datetime" ,nullable=false)
     */
    private $dateCrea;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modif", type="datetime" ,nullable=true)
     */
    private $dateModif;

    /**
     * The duration of the timeout before cancelling a reservation
     * User can not start the reservation if he submit after + "$dureeTimeout" + of the StartTime of his reservation
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $dureeTimeout;

    /**
     * Highest Number of parallel reservations per User
     * In the same time, a user can only reserve $maxResParall devices
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $maxResParall;

    /**
     * Regles constructor.
     */
    public function __construct()
    {
        $this->setDateCrea(new \DateTime(null, new \DateTimeZone("Africa/Tunis"))) ;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return \DateTime
     */
    public function getDateCrea()
    {
        return $this->dateCrea;
    }

    /**
     * @param \DateTime $dateCrea
     */
    public function setDateCrea($dateCrea)
    {
        $this->dateCrea = $dateCrea;
    }

    /**
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }

    /**
     * @param \DateTime $dateModif
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;
    }

    /**
     * @return int
     */
    public function getLimDureeReservation()
    {
        return $this->limDureeReservation;
    }

    /**
     * @param int $limDureeReservation
     */
    public function setLimDureeReservation($limDureeReservation)
    {
        $this->limDureeReservation = $limDureeReservation;
    }

    /**
     * @return int
     */
    public function getNbrLimiteParJour()
    {
        return $this->nbrLimiteParJour;
    }

    /**
     * @param int $nbrLimiteParJour
     */
    public function setNbrLimiteParJour($nbrLimiteParJour)
    {
        $this->nbrLimiteParJour = $nbrLimiteParJour;
    }

    /**
     * @return int
     */
    public function getNbrLimiteParSemaine()
    {
        return $this->nbrLimiteParSemaine;
    }

    /**
     * @param int $nbrLimiteParSemaine
     */
    public function setNbrLimiteParSemaine($nbrLimiteParSemaine)
    {
        $this->nbrLimiteParSemaine = $nbrLimiteParSemaine;
    }

    /**
     * @return int
     */
    public function getDureeTimeout()
    {
        return $this->dureeTimeout;
    }

    /**
     * @param int $dureeTimeout
     */
    public function setDureeTimeout($dureeTimeout)
    {
        $this->dureeTimeout = $dureeTimeout;
    }

    /**
     * @return int
     */
    public function getNbrMaxReservParallelPar()
    {
        return $this->nbrMaxReservParallelPar;
    }

    /**
     * @param int $nbrMaxReservParallelPar
     */
    public function setNbrMaxReservParallelPar($nbrMaxReservParallelPar)
    {
        $this->nbrMaxReservParallelPar = $nbrMaxReservParallelPar;
    }

    /**
     * @return int
     */
    public function getMaxResParall()
    {
        return $this->maxResParall;
    }

    /**
     * @param int $maxResParall
     */
    public function setMaxResParall($maxResParall)
    {
        $this->maxResParall = $maxResParall;
    }




}