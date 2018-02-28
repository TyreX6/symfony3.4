<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/02/2018
 * Time: 19:52
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Reservation
 *
 * @ORM\Table(name="Reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=20)
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime" ,nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime" ,nullable=false)
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="reservation" ,cascade="persist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=false)
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Dispositif", inversedBy="reservation",)
     * @ORM\JoinColumn(name="dispositif_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     **/
    private $dispositif;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param string $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }


    public function getDateDebutTimeStamp()
    {
        return $this->dateDebut->getTimestamp();
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function getDateFinTimeStamp()
    {
        return $this->dateFin->getTimestamp();
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getDispositif()
    {
        return $this->dispositif;
    }

    /**
     * @param mixed $dispositif
     */
    public function setDispositif($dispositif)
    {
        $this->dispositif = $dispositif;
    }





}