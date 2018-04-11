<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 29/03/2018
 * Time: 16:29
 */

namespace IT\DispositifBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ORM\InheritanceType("JOINED")
 * @ExclusionPolicy("all")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *     "0" = "Ressource",
 *     "1" = "Dispositif",
 *     "2" = "Projecteur"
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
     * @Expose
     * @SWG\Property(description="Identifiant du ressource.")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="code_barre", type="string",nullable=true)
     * @Expose
     * @SWG\Property(property="code_barre",type="string",description="code barre de ressource.")
     */
    private $code_barre;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string" ,length=20,nullable=false)
     * @Expose
     * @Assert\Choice(
     *     choices={"Fonctionnel", "Détruit","Perdu"},
     *      message="Choose a valid status."
     * )
     * @SWG\Property(type="string",description="Etat du dispositif.")
     */
    private $etat;

    /**
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $dateAtjout;



    /**
     * @ORM\OneToMany(targetEntity="IT\ReservationBundle\Entity\AbstractReservation", mappedBy="ressource",cascade="persist")
     **/
    private $abstract_reservation;


    /**
     * @ORM\ManyToOne(targetEntity="IT\DispositifBundle\Entity\Categorie", inversedBy="ressource")
     * @Assert\NotBlank()
     * @Expose
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id",onDelete="CASCADE",nullable=false)
     * @SWG\Property(description="Le categorie.")
     **/
    private $categorie;

    /**
     * Ressource constructor.
     */
    public function __construct()
    {
        $this->abstract_reservation = new ArrayCollection();
        $this->setDateAtjout($date = new \DateTime(null, new \DateTimeZone("Africa/Tunis")));
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
    public function getCodeBarre()
    {
        return $this->code_barre;
    }

    /**
     * @param string $code_barre
     */
    public function setCodeBarre($code_barre)
    {
        $this->code_barre = $code_barre;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getDateAtjout()
    {
        return $this->dateAtjout;
    }

    /**
     * @param mixed $dateAtjout
     */
    public function setDateAtjout($dateAtjout)
    {
        $this->dateAtjout = $dateAtjout;
    }

    /**
     * @return mixed
     */
    public function getAbstractReservation()
    {
        return $this->abstract_reservation;
    }

    /**
     * @param mixed $abstract_reservation
     */
    public function setAbstractReservation($abstract_reservation)
    {
        $this->abstract_reservation = $abstract_reservation;
    }


    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }




}