<?php
/**
 * Created by IntelliJ IDEA.
 * User: TyreX
 * Date: 23/02/2018
 * Time: 10:19
 */

namespace IT\ResourceBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use IT\ResourceBundle\Entity\Image;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IT\ResourceBundle\Repository\DispositifsRepository")
 * @ExclusionPolicy("all")
 * @SWG\Definition(type="object", @SWG\Xml(name="Dispositif"))
 */
class Dispositif extends Ressource
{

    /**
     * @var string
     * @ORM\Column(name="model", type="string", length=50)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your model must be at least {{ limit }} characters long",
     *      maxMessage = "Your model cannot be longer than {{ limit }} characters"
     * )
     * @SWG\Property(property="model",type="string",description="The model of the device.")
     */
    private $model;

    /**
     * @var string
     * @ORM\Column(name="deviceName", type="string", length=50)
     * @Expose
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your deviceName must be at least {{ limit }} characters long",
     *      maxMessage = "Your deviceName cannot be longer than {{ limit }} characters"
     * )
     * @SWG\Property(property="deviceName",type="string",description="Nom du dispositif.")
     */
    private $deviceName;

    /**
     * @var string
     **/
    private $name;

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
     * @ORM\Column(name="OsVersion", type="string")
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Regex("/(^.+)\.(\w+)/",
     *     message="This is not a valid version")
     * @SWG\Property(type="string",description="Version of the system's OS")
     */
    private $OsVersion;

    /**
     * @var string
     *
     * @ORM\Column(name="device_UUID", type="string", length=80)
     * @Expose
     * @SWG\Property(type="string",description="Identifiant unique du dispositif.")
     */
    private $deviceUUID;

    /**
     * @var string
     *
     * @ORM\Column(name="cpu", type="string", length=40)
     * @Expose
     * @Assert\NotBlank()
     * @SWG\Property(type="string",description="Cpu of the device.")
     */
    private $cpu;

    /**
     * @var int
     *
     * @ORM\Column(name="cpu_cores", type="integer")
     * @Expose
     * @SWG\Property(type="number",description="Nombre de coeurs du dispositif.")
     */
    private $cpuCores;

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
     * @ORM\Column(name="disk_space", type="string")
     * @Expose
     * @SWG\Property(description="Storage du dispositif.")
     */
    private $diskSpace;

    /**
     * @var string
     *
     * @ORM\Column(name="free_disk_space", type="string")
     * @Expose
     * @SWG\Property(description="Storage disponible du dispositif.")
     */
    private $freeDiskSpace;

    /**
     * @var string
     *
     * @ORM\Column(name="used_disk_space", type="string")
     * @Expose
     * @SWG\Property(description="Storage utilisé du dispositif.")
     */
    private $usedDiskSpace;


    /**
     * @var string
     * @ORM\Column(name="resolution", type="string" ,length=20)
     * @Expose
     * @Assert\NotBlank()
     * @Assert\Regex("/(^[0-9]{3,4}+)(x|X)([0-9]{3,4}$)/",
     *     message="This is not a valid resolution")
     * @SWG\Property(type="string",description="Resolution of the device.")
     */
    private $resolution;

    /**
     * @ORM\OneToMany(targetEntity="IT\ResourceBundle\Entity\Application", mappedBy="dispositif" ,cascade={"remove", "persist"},orphanRemoval=true)
     **/
    private $apps;


    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="dispositif" ,cascade={"remove", "persist"},orphanRemoval=true)
     **/
    private $images;


    public function __construct()
    {
        parent::__construct();
        $this->reservation = new ArrayCollection();
        $this->apps = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->deviceName;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }




    /**
     * add image
     *
     * @param Image $image
     * @return Dispositif
     */
    public function addImage($image)
    {

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

    public function removeImage($image)
    {
        $this->images->removeElement($image);
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
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * @param string $deviceName
     */
    public function setDeviceName(string $deviceName)
    {
        $this->deviceName = $deviceName;
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
    public function setOs(string $os)
    {
        $this->os = $os;
    }

    /**
     * @return string
     */
    public function getOsVersion()
    {
        return $this->OsVersion;
    }

    /**
     * @param string $OsVersion
     */
    public function setOsVersion(string $OsVersion)
    {
        $this->OsVersion = $OsVersion;
    }

    /**
     * @return string
     */
    public function getDeviceUUID()
    {
        return $this->deviceUUID;
    }

    /**
     * @param string $deviceUUID
     */
    public function setDeviceUUID(string $deviceUUID)
    {
        $this->deviceUUID = $deviceUUID;
    }

    /**
     * @return string
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * @param string $cpu
     */
    public function setCpu(string $cpu)
    {
        $this->cpu = $cpu;
    }

    /**
     * @return int
     */
    public function getCpuCores()
    {
        return $this->cpuCores;
    }

    /**
     * @param int $cpuCores
     */
    public function setCpuCores(int $cpuCores)
    {
        $this->cpuCores = $cpuCores;
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
    public function setRam(float $ram)
    {
        $this->ram = $ram;
    }

    /**
     * @return string
     */
    public function getDiskSpace()
    {
        return $this->diskSpace;
    }

    /**
     * @param string $diskSpace
     */
    public function setDiskSpace(string $diskSpace)
    {
        $this->diskSpace = $diskSpace;
    }

    /**
     * @return string
     */
    public function getFreeDiskSpace()
    {
        return $this->freeDiskSpace;
    }

    /**
     * @param string $freeDiskSpace
     */
    public function setFreeDiskSpace(string $freeDiskSpace)
    {
        $this->freeDiskSpace = $freeDiskSpace;
    }

    /**
     * @return string
     */
    public function getUsedDiskSpace()
    {
        return $this->usedDiskSpace;
    }

    /**
     * @param string $usedDiskSpace
     */
    public function setUsedDiskSpace(string $usedDiskSpace)
    {
        $this->usedDiskSpace = $usedDiskSpace;
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
    public function setResolution(string $resolution)
    {
        $this->resolution = $resolution;
    }


    public function __toString()
    {
        return $this->model;
    }


}