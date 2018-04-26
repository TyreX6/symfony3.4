<?php

namespace IT\ResourceBundle\Entity;

use JMS\Serializer\Annotation\Expose;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;
use IT\ResourceBundle\Entity\Dispositif ;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $fileName;


    /**
     * @ORM\ManyToOne(targetEntity="IT\ResourceBundle\Entity\Dispositif", inversedBy="images" ,cascade={"all"})
     * @ORM\JoinColumn(name="dispositive_id", referencedColumnName="id",onDelete="CASCADE")
     **/
    private $dispositif;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateAjout;



    /**
     * Image constructor.
     */
    public function __construct()
    {
        $this->setPath();
        $this->setDateAjout(new \DateTime(null, new \DateTimeZone("Africa/Tunis"))) ;
    }


    function __toString()
    {
        return $this->getFileName();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * @param mixed $dateAjout
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Set file
     *
     * @var UploadedFile $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        $this->fileName = sha1(uniqid(mt_rand(), true)) . '.' . $file->guessExtension();

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir() ."/".$this->file;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
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

    public function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        //return __DIR__.'/../web/'.$this->getUploadDir();

       return  __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'bundles/app/uploads/documents';
    }


    /**
     * Computes a default name
     * @param string $extension
     * @return string
     */
    protected function getDefaultFileName($extension)
    {
        return uniqid() . '.' . ($extension || self::DEFAULT_EXTENSION);
    }

    /**
     * Sets the file name, needed to store the file when not using transloadit
     * @ORM\PrePersist
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if ($this->file !== NULL) {
            $extension = $this->file->guessExtension();
            if ($this->fileName == NULL) {
                $this->setFileName($this->getDefaultFileName($extension));
            }
        }
    }

    /**
     * Handles the eventual upload of the file.
     * If we have no file, the file_path and file_name are set by transloadit.
     * Else, the  file_path must be set to '' (empty string) and the name
     * generated if not given
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if ($this->file === NULL) {
            return;
        }
        /* You must throw an exception here if the file cannot be moved – so
         * that the entity is not persisted to the database – which the
         * UploadedFile::move() method does automatically */
        $this->file->move($this->getUploadRootDir(), '/' . $this->fileName);
        unset($this->file);
    }

    /**
     * Remove the file from the server if it was hosted on it.
     * The commented out parts are suspected to work with Symfony>2.1
     * @ORM\PreRemove()
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink(realpath($file));
        }
    }

     public function getPath() {
         return $this->path;
     }

     public function setPath() {
         $this->path = '/../../../bundles/app/uploads/documents/';
         return $this;
     }


}
    


