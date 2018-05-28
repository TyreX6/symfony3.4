<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 29/03/2018
 * Time: 16:29
 */

namespace IT\ResourceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use IT\ReservationBundle\Entity\Reservation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="IT\ResourceBundle\Repository\ResourcesRepository")
 * @ORM\Table()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "0" = "Ressource",
 *     "1" = "Dispositif",
 *     "2" = "Projecteur",
 *     "3" = "Salle"
 *     })
 * @SWG\Definition(type="object", @SWG\Xml(name="Ressource"))
 */
class Ressource
{

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"categories","resources"})
     * @SWG\Property(description="Identifiant du ressource.")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="bar_code", type="string",nullable=true)
     * @Serializer\Groups({"categories","resources"})
     * @SWG\Property(property="bar_code",type="string",description="Bar code of the resource.")
     */
    protected $bar_code;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer" ,nullable=false)
     * @Serializer\Groups({"categories","resources"})
     * @Assert\Choice(
     *     choices={1,0},
     *      message="Choose a valid status."
     * )
     * @SWG\Property(type="integer",description="Etat du dispositif.")
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime",nullable=false)
     * @Serializer\Groups({"categories","resources"})
     */
    protected $dateAdd;

    /**
     * @var \DateTime
     * @Serializer\Groups({"categories","resources"})
     * @ORM\Column(name="last_check_date", type="datetime",nullable=true)
     */
    protected $lastCheckDate;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50)
     * @Serializer\Groups({"categories","resources"})
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your resource name must be at least {{ limit }} characters long",
     *      maxMessage = "Your resource name cannot be longer than {{ limit }} characters"
     * )
     * @SWG\Property(property="name",type="string",description="Nom du ressource.")
     */
    protected $name;


    /**
     * @ORM\OneToMany(targetEntity="IT\ReservationBundle\Entity\Reservation", mappedBy="ressource",cascade="persist")
     * @SWG\Property(type="object",description="reservation of the resource.")
     * @Serializer\Exclude()
     **/
    protected $reservations;


    /**
     * @ORM\ManyToOne(targetEntity="IT\ResourceBundle\Entity\Categorie", inversedBy="ressource")
     * @Assert\NotBlank()
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     * @SWG\Property(description="Le categorie.")
     * @Serializer\Groups({"resources"})
     **/
    protected $category;
    /**
     * @var boolean
     */
    protected $reserved ;

    /**
     * Ressource constructor.
     */
    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->setDateAdd($date = new \DateTime(null, new \DateTimeZone("Africa/Tunis")));
    }

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
    public function getBarCode()
    {
        return $this->bar_code;
    }

    /**
     * @param string $bar_code
     */
    public function setBarCode(string $bar_code)
    {
        $this->bar_code = $bar_code;
    }


    /**
     * @return mixed
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * @param mixed $dateAdd
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;
    }

    /**
     * @return \DateTime
     */
    public function getLastCheckDate()
    {
        return $this->lastCheckDate;
    }

    /**
     * @param \DateTime $lastCheckDate
     */
    public function setLastCheckDate(\DateTime $lastCheckDate)
    {
        $this->lastCheckDate = $lastCheckDate;
    }

    /**
     * @return ArrayCollection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * @param mixed $reservations
     */
    public function setReservations($reservations)
    {
        $this->reservations = $reservations;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isReserved()
    {
        return $this->reserved;
    }

    /**
     * @param bool $reserved
     */
    public function setReserved(bool $reserved)
    {
        $this->reserved = $reserved;
    }



}