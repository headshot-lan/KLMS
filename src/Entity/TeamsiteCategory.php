<?php

namespace App\Entity;

use App\Repository\TeamsiteCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamsiteCategoryRepository::class)
 * @ORM\Table(
 *     name="teamsite_category",
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="teamsite_category_ord_unique", columns={"teamsite_id", "ord" }),
 *     },
 * )
 */
class TeamsiteCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title = '';

    /**
     * @ORM\Column(type="text")
     */
    private ?string $description = '';

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $ord = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Teamsite::class, inversedBy="categories")
     * @ORM\JoinColumn(name="teamsite_id", nullable=false)
     */
    private ?Teamsite $teamsite;

    /**
     * @ORM\OneToMany(
     *     targetEntity=TeamsiteEntry::class,
     *     mappedBy="category",
     *     orphanRemoval=true,
     *     cascade={"all"},
     * )
     * @ORM\OrderBy({"ord" = "ASC"})
     */
    private $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getOrd(): ?int
    {
        return $this->ord;
    }

    public function setOrd(int $ord): self
    {
        $this->ord = $ord;
        return $this;
    }

    public function getTeamsite(): ?Teamsite
    {
        return $this->teamsite;
    }

    public function setTeamsite(?Teamsite $teamsite): self
    {
        $this->teamsite = $teamsite;
        return $this;
    }

    /**
     * @return Collection|TeamsiteEntry[]
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(TeamsiteEntry $entry): self
    {
        if (!$this->entries->contains($entry)) {
            $this->entries[] = $entry;
            $entry->setCategory($this);
        }

        return $this;
    }

    public function removeEntry(TeamsiteEntry $entry): self
    {
        if ($this->entries->removeElement($entry)) {
            // set the owning side to null (unless already changed)
            if ($entry->getCategory() === $this) {
                $entry->setCategory(null);
            }
        }

        return $this;
    }
}