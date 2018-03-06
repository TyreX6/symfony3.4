<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 23/02/2018
 * Time: 10:19
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use AppBundle\Entity\Image ;


/**
 * @ORM\Entity
 * @ORM\Table()
 * @ExclusionPolicy("all")
 */
class Dispositif implements \JsonSerializable
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;



    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=50)
     * @Expose
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="os", type="string", length=20)
     * @Expose
     */
    private $os;

    /**
     * @var string
     *
     * @ORM\Column(name="versionOS", type="string")
     * @Expose
     */
    private $versionOS;

    /**
     * @var string
     *
     * @ORM\Column(name="processeur", type="string", length=40)
     * @Expose
     */
    private $processeur;

    /**
     * @var float
     *
     * @ORM\Column(name="ram", type="float")
     * @Expose
     */
    private $ram;

    /**
     * @var string
     *
     * @ORM\Column(name="resolution", type="string" ,length=20)
     * @Expose
     */
    private $resolution;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="dispositif" ,cascade={"remove", "persist"},orphanRemoval=true)
     * @Expose
     **/
    private $images;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string" ,length=20,nullable=false)
     * @Expose
     */
    private $etat;





    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Reservation", mappedBy="dispositif",cascade="persist")
     **/
    private $reservation;


    /**
     * @ORM\Column(type="datetime",nullable=false)
     */
    private $dateAtjout ;


    public function __construct() {
        $this->reservation = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->setDateAtjout($date= new \DateTime(null, new \DateTimeZone("Africa/Tunis"))) ;
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
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * @param string $modele
     */
    public function setModele($modele)
    {
        $this->modele = $modele;
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
     * @return string
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * @param string $os
     */
    public function setOs($os)
    {
        $this->os = $os;
    }

    /**
     * @return float
     */
    public function getVersionOS()
    {
        return $this->versionOS;
    }

    /**
     * @param float $versionOS
     */
    public function setVersionOS($versionOS)
    {
        $this->versionOS = $versionOS;
    }

    /**
     * @return string
     */
    public function getProcesseur()
    {
        return $this->processeur;
    }

    /**
     * @param string $processeur
     */
    public function setProcesseur($processeur)
    {
        $this->processeur = $processeur;
    }

    /**
     * @return float
     */
    public function getRam()
    {
        return $this->ram;
    }

    /**
     * @param float $ram
     */
    public function setRam($ram)
    {
        $this->ram = $ram;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param string $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
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
     * add image
     *
     * @param \AppBundle\Entity\Image $image
     * @return Dispositif
     */
    public function addImage($image) {

        $image->setDispositif($this);
        $this->images[] = $image;
        return $this;
    }
    /**
     * Set images
     *
     * @param string $images
     * @return Dispositif
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get $images
     *
     * @return collection
     */
    public function getImages()
    {
        return $this->images;
    }


    public function removeImage($image) {
        $this->images->removeElement($image);
    }

    /**
     * @return mixed
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @param mixed $reservation
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
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
                'modele' => $this->getModele(),
                'os' => $this->getOs(),
                'osVersion' => $this->getVersionOS(),
                'processeur' => $this->getProcesseur(),
                'ram' => $this->getRam(),
                'resolution' => $this->getResolution(),

        ];
    }
}