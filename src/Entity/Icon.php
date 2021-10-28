<?php

namespace App\Entity;

use App\Repository\IconRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IconRepository::class)
 */
class Icon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $d1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fill;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $opacite;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="icons")
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transform;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $x;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $y;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $exist;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $point;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rx;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getD1(): ?string
    {
        return $this->d1;
    }

    public function setD1(string $d1): self
    {
        $this->d1 = $d1;

        return $this;
    }

 
    public function getFill(): ?string
    {
        return $this->fill;
    }

    public function setFill(string $fill): self
    {
        $this->fill = $fill;

        return $this;
    }

    public function getOpacite(): ?string
    {
        return $this->opacite;
    }

    public function setOpacite(string $opacite): self
    {
        $this->opacite = $opacite;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getTransform(): ?string
    {
        return $this->transform;
    }

    public function setTransform(string $transform): self
    {
        $this->transform = $transform;

        return $this;
    }

    public function getRule(): ?string
    {
        return $this->rule;
    }

    public function setRule(string $rule): self
    {
        $this->rule = $rule;
        return $this;
    }

    public function getX(): ?string
    {
        return $this->x;
    }

    public function setX(string $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?string
    {
        return $this->y;
    }

    public function setY(string $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getExist(): ?string
    {
        return $this->exist;
    }

    public function setExist(string $exist): self
    {
        $this->exist = $exist;

        return $this;
    }

    public function getPoint(): ?string
    {
        return $this->point;
    }

    public function setPoint(string $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getRx(): ?string
    {
        return $this->rx;
    }

    public function setRx(string $rx): self
    {
        $this->rx = $rx;

        return $this;
    }
}
