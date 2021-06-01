<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlanningRepository::class)
 */
class Planning
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_passage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $heure_passage;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="planning")
     */
    private $etudiants;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePassage(): ?\DateTimeInterface
    {
        return $this->date_passage;
    }

    public function setDatePassage(?\DateTimeInterface $date_passage): self
    {
        $this->date_passage = $date_passage;

        return $this;
    }

    public function getHeurePassage(): ?\DateTimeInterface
    {
        return $this->heure_passage;
    }

    public function setHeurePassage(?\DateTimeInterface $heure_passage): self
    {
        $this->heure_passage = $heure_passage;

        return $this;
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setPlanning($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getPlanning() === $this) {
                $etudiant->setPlanning(null);
            }
        }

        return $this;
    }
}
