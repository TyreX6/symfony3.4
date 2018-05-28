<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 30/03/2018
 * Time: 16:14
 */

namespace IT\ReservationBundle\Entity;

use IT\ResourceBundle\Entity\Ressource;
use IT\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use FOS\RestBundle\Context\Context;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *  Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IT\ReservationBundle\Repository\ReservationsRepository")
 * @SWG\Definition(type="object", @SWG\Xml(name="Reservation"))
 */
class Reservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"resources"})
     * @SWG\Property(description="Identifiant du réservation.")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", length=2)
     * @Serializer\Groups({"resources"})
     * @Assert\NotBlank()
     * @Assert\Choice(
     *     choices = { 0,1,2 },
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
     * @Serializer\Groups({"resources"})
     * @SWG\Property(description="Date de début du réservation.")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="date_fin", type="datetime" ,nullable=false)
     * @Serializer\Groups({"resources"})
     * @SWG\Property(description="Date de fin du réservation.")
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity="IT\UserBundle\Entity\User", inversedBy="reservation" ,cascade="persist")
     * @Assert\NotBlank()
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",nullable=false,onDelete="CASCADE")
     * @Serializer\Groups({"user"})
     * @SWG\Property(type="object", description="L'utilisateur qui a réservé.")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="IT\ResourceBundle\Entity\Ressource", inversedBy="reservations",)
     * @ORM\JoinColumn(name="ressource_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     * @SWG\Property(type="object",description="Le ressource réservé.")
     * @Serializer\Groups({"resources"})
     **/
    private $ressource;

    private $pourcentage;

    /**
     * Reservation constructor.
     */
    public function __construct()
    {
        $this->setStatut(0);
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
     * @param int $statut
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
     * @return Ressource
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