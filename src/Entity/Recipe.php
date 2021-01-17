<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 */
class Recipe
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
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $people;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preparationTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $waitTime;

    /**
     * @ORM\OneToMany(targetEntity=Ingredient::class, mappedBy="recipe", orphanRemoval=true)
     */
    private $ingredients;

    /**
     * @ORM\OneToMany(targetEntity=Step::class, mappedBy="recipe", orphanRemoval=true)
     */
    private $steps;

    /**
     * @ORM\OneToMany(targetEntity=Extra::class, mappedBy="recipe", orphanRemoval=true)
     */
    private $extras;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->extras = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPeople(): ?int
    {
        return $this->people;
    }

    public function setPeople(?int $people): self
    {
        $this->people = $people;

        return $this;
    }

    public function getPreparationTime(): ?string
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(?string $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getWaitTime(): ?string
    {
        return $this->waitTime;
    }

    public function setWaitTime(?string $waitTime): self
    {
        $this->waitTime = $waitTime;

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Step[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getRecipe() === $this) {
                $step->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Extra[]
     */
    public function getExtras(): Collection
    {
        return $this->extras;
    }

    public function addExtra(Extra $extra): self
    {
        if (!$this->extras->contains($extra)) {
            $this->extras[] = $extra;
            $extra->setRecipe($this);
        }

        return $this;
    }

    public function removeExtra(Extra $extra): self
    {
        if ($this->extras->removeElement($extra)) {
            // set the owning side to null (unless already changed)
            if ($extra->getRecipe() === $this) {
                $extra->setRecipe(null);
            }
        }

        return $this;
    }

    /** @return array<string, mixed> */
    public function toArray(): array {
        $ingredients = [];
        foreach($this->getIngredients() as $ingredient) {
            $ingredients[] = [
                'amount' => $ingredient->getAmount(),
                'quantity' => $ingredient->getQuantity(),
                'name' => $ingredient->getName()
            ];
        }

        $steps = [];
        foreach($this->getSteps() as $step) {
            $steps[] = [
                'index' => $step->getIndex(),
                'text' => $step->getText()
            ];
        }

        $extras = [];
        foreach($this->getExtras() as $extra) {
            $extras[] =[
                'text' => $extra->getText()
            ];
        }

        return [
            'name' => $this->getName(),
            'people' => $this->getPeople(),
            'preparation_time' => $this->getPreparationTime(),
            'wait_time' => $this->getWaitTime(),
            'ingredients' => $ingredients,
            'steps' => $steps,
            'extras' => $extras
        ];
    }
}
