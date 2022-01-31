<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    public function getProperties(){
        return  ['titre'=> 'titre','parent'=> 'parent'];
    }
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;


    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="module",cascade={"persist"})
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=ModuleParent::class, inversedBy="modules")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;

    /**
     * @ORM\Column(type="integer")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Icon::class, mappedBy="module",cascade={"persist"})
     */
    private $icons;


    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->icons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }


    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setModule($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getModule() === $this) {
                $groupe->setModule(null);
            }
        }

        return $this;
    }

    public function getParent(): ?ModuleParent
    {
        return $this->parent;
    }

    public function setParent(?ModuleParent $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Icon[]
     */
    public function getIcons(): Collection
    {
        return $this->icons;
    }

    public function addIcon(Icon $icon): self
    {
        if (!$this->icons->contains($icon)) {
            $this->icons[] = $icon;
            $icon->setModule($this);
        }

        return $this;
    }

    public function removeIcon(Icon $icon): self
    {
        if ($this->icons->removeElement($icon)) {
            // set the owning side to null (unless already changed)
            if ($icon->getModule() === $this) {
                $icon->setModule(null);
            }
        }

        return $this;
    }

}
