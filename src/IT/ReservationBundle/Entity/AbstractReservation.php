<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 30/03/2018
 * Time: 16:14
 */

namespace IT\ReservationBundle\Entity;

use IT\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Serializer\Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract Reservation
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "0" = "AbstractReservation",
 *     "1" = "Reservation",
 *     "2" = "ReservationProjecteur"
 *     })
 */
abstract class AbstractReservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @SWG\Property(description="Identifiant du réservation.")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=20)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Choice(
     *     choices = { "En cours", "En attente","Terminé" },
     *     message = "Choose a valid status."
     * )
     * @SWG\Property(description="Statut du réservation.")
     */
    private $statut;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = "now"
     * )
     * @ORM\Column(name="date_debut", type="datetime" ,nullable=false)
     * @Expose
     * @SWG\Property(description="Date de début du réservation.")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="date_fin", type="datetime" ,nullable=false)
     * @Expose
     * @SWG\Property(description="Date de fin du réservation.")
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="IT\UserBundle\Entity\User", inversedBy="reservation" ,cascade="persist")
     * @Assert\NotBlank()
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     * @Expose
     * @SWG\Property(description="L'utilisateur qui a réservé.")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="IT\DispositifBundle\Entity\Ressource", inversedBy="abstract_reservation",)
     * @ORM\JoinColumn(name="ressource_id", referencedColumnName="id",onDelete="CASCADE")
     * @SWG\Property(description="Le ressource réservé.")
     * @Expose
     **/
    private $ressource;

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
        $dateNowTimStamp = new \DateTime(null, new \DateTimeZone("Africa/Tunis"));
        $dateNowTimStamp = $dateNowTimStamp->getTimestamp();
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
    public function getRessource()
    {
        return $this->ressource;
    }

    /**
     * @param mixed $ressource
     */
    public function setRessource($ressource)
    {
        $this->ressource = $ressource;
    }





}