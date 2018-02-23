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
 * Dispositif
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="")
 * @ExclusionPolicy("all")
 */
class Dispositif
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
     * @var float
     *
     * @ORM\Column(name="versionOS", type="float")
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

    public function __construct() {
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




}