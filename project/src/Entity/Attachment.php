<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Attachment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @Vich\UploadableField(mapping="annonce_images", fileNameProperty="fileName")
     * @Assert\File(
     *   maxSize="30000K",
     *     mimeTypes = {
     *          "image/png",
     *          "image/jpeg",
     *          "image/jpg",
     *      }
     *  )
     */
    private $file;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fileUpdatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Annonce::class, inversedBy="attachments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $annonce;


    public function __construct()
    {
        $this->fileUpdatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        if (null !== $file) {
            $this->fileUpdatedAt = new \DateTime();
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getFileUpdatedAt(): ?\DateTime
    {
        return $this->fileUpdatedAt;
    }

    public function setFileUpdatedAt(?\DateTime $fileUpdatedAt): self
    {
        $this->fileUpdatedAt = $fileUpdatedAt;

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        return $this;
    }
}
