<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 26/02/2018
 * Time: 19:52
 */

namespace IT\ReservationBundle\Entity;

use IT\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Serializer\Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JsonSerializable;


/**
 * Reservation
 *
 * @ORM\Table(name="Reservation")
 * @ORM\Entity(repositoryClass="IT\ReservationBundle\Repository\ReservationsRepository")
 * @ExclusionPolicy("all")
 */
class Reservation implements JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=20)
     * @Expose
     */
    private $statut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime" ,nullable=false)
     * @Expose
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime" ,nullable=false)
     * @Expose
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="IT\UserBundle\Entity\User", inversedBy="reservation" ,cascade="persist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="IT\DispositifBundle\Entity\Dispositif", inversedBy="reservation",)
     * @ORM\JoinColumn(name="dispositif_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     **/
    private $dispositif;


    private $pourcentage;

    /**
     * Reservation constructor.
     */
    public function __construct()
    {
        $this->setStatut("En attente");
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPourcentage()
    {
        $class = "warning";
        $dateNowTimStamp = new \DateTime(null);
        $dateNowTimStamp = $dateNowTimStamp->getTimestamp() + 3600;
        $avancement = ($dateNowTimStamp - $this->dateDebut->getTimestamp()) / ($this->dateFin->getTimestamp() - $this->dateDebut->getTimestamp());
        $pourcentage = 0;
        if ($avancement > 1) {
            $class = "danger";
            $pourcentage = 100;
        } elseif ($avancement > 0) {
            $class = "success";
            $pourcentage = $avancement * 100;
        }
        return array("class" => $class, "pourcentage" => (int)$pourcentage);
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
     * @return User
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

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [

            'id' => $this->getId(),
            'user' => $this->getUser(),
            'dispositif' => $this->getDispositif(),
            'dateDebut' => $this->getDateDebut(),
            'dateFin' => $this->getDateFin(),
            'etat' => $this->getStatut(),
        ];
    }
}