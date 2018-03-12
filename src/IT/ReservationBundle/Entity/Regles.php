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
    protected $id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $limDureeReservation;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $nbrLimiteParJour;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $nbrLimiteParSemaine;

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
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $dureeTimeout;



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




}