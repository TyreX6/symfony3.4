<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 23/02/2018
 * Time: 10:19
 */

namespace IT\DispositifBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use IT\DispositifBundle\Entity\Image ;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IT\DispositifBundle\Repository\DispositifsRepository")
 * @ExclusionPolicy("all")
 * @SWG\Definition(type="object", @SWG\Xml(name="Dispositif"))
 */
class Dispositif extends Ressource
{

    /**
     * @var string
     * @ORM\Column(name="modele", type="string", length=50)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your model must be at least {{ limit }} characters long",
     *      maxMessage = "Your model cannot be longer than {{ limit }} characters"
     * )
     * @SWG\Property(property="modele",type="string",description="modele du dispositif.")
     */
    private $modele;


    /**
     * @var string
     *
     * @ORM\Column(name="os", type="string", length=20)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Choice(
     *     choices={"ANDROID", "IOS","WINDOWS","LINUX"},
     *      message="Choose a valid OS."
     * )
     * @SWG\Property(type="string",description="Systéme d'exploitation du dispositif.")
     */
    private $os;

    /**
     * @var string
     * @ORM\Column(name="versionOS", type="string")
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Regex("/(^.+)\.(\w+)/",
     *     message="This is not a valid version")
     * @SWG\Property(type="string",description="Version du systéme d'exploitation du dispositif.")
     */
    private $versionOS;

    /**
     * @var string
     *
     * @ORM\Column(name="processeur", type="string", length=40)
     * @Expose
     * @Assert\NotBlank()
     * @SWG\Property(type="string",description="Processeur du dispositif.")
     */
    private $processeur;

    /**
     * @var float
     *
     * @ORM\Column(name="ram", type="float")
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0.512,
     *      max = 24.000,
     *      minMessage = "Your RAM must be at least {{ limit }} MB",
     *      maxMessage = "Your RAM be greater than {{ limit }} MB"
     * )
     * @SWG\Property(type="number",description="Ram du dispositif.")
     */
    private $ram;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_serie", type="string")
     * @Expose
     * @Assert\NotBlank()
     * @SWG\Property(description="Numero de serie du dispositif.")
     */
    private $numero_serie;

    /**
     * @var string
     * @ORM\Column(name="resolution", type="string" ,length=20)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Regex("/(^[0-9]{4}+)(x|X)([0-9]{4}$)/",
     *     message="This is not a valid resolution")
     * @SWG\Property(type="string",description="Résolution du dispositif.")
     */
    private $resolution;

    /**
     * @ORM\OneToMany(targetEntity="IT\DispositifBundle\Entity\Application", mappedBy="dispositif" ,cascade={"remove", "persist"},orphanRemoval=true)
     **/
    private $apps;



    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="dispositif" ,cascade={"remove", "persist"},orphanRemoval=true)
     **/
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="IT\ReservationBundle\Entity\Reservation", mappedBy="dispositif",cascade="persist")
     **/
    private $reservation;



    public function __construct() {
        parent::__construct();
        $this->reservation = new ArrayCollection();
        $this->apps = new ArrayCollection();
        $this->images = new ArrayCollection();
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
     * add image
     *
     * @param Image $image
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
     * @return string
     */
    public function getNumeroSerie()
    {
        return $this->numero_serie;
    }

    /**
     * @param string $numero_serie
     */
    public function setNumeroSerie($numero_serie)
    {
        $this->numero_serie = $numero_serie;
    }
    /**
     * @return mixed
     */
    public function getApps()
    {
        return $this->apps;
    }

    /**
     * @param mixed array $apps
     */
    public function setApps($apps)
    {
        $this->apps = $apps;
    }

    /**
     * {@inheritdoc}
     */
    public function addApp($app)
    {
        $app = strtoupper($app);
        if (!in_array($app, $this->apps, true)) {
            $this->apps[] = $app;
        }
        return $this;
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





}