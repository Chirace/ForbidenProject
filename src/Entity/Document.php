<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $intitule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat_document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $date_depot;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, cascade={"persist", "remove"})
     */
    private $depositaire;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="documents")
     */
    private $etudiant;

    /**
     * @ORM\ManyToOne(targetEntity=TypeDocument::class, inversedBy="documents")
     */
    private $type_document;

    private $typeDocument2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getEtatDocument(): ?string
    {
        return $this->etat_document;
    }

    public function setEtatDocument(string $etat_document): self
    {
        $this->etat_document = $etat_document;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateDepot(): ?string
    {
        return $this->date_depot;
    }

    public function setDateDepot(?string $date_depot): self
    {
        $this->date_depot = $date_depot;

        return $this;
    }

    public function getDepositaire(): ?Personne
    {
        return $this->depositaire;
    }

    public function setDepositaire(?Personne $depositaire): self
    {
        $this->depositaire = $depositaire;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getTypeDocument(): ?TypeDocument
    {
        return $this->type_document;
    }

    public function setTypeDocument(?TypeDocument $type_document): self
    {
        $this->type_document = $type_document;

        return $this;
    }

    public function getTypeDocument2(): ?string
    {
        return $this->typeDocument2;
    }

    public function setTypeDocument2(string $typeDocument2): self
    {
        $this->typeDocument2 = $typeDocument2;

        return $this;
    }
}
