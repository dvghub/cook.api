<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Extra;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Step;
use App\Entity\Tag;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends AbstractController {
    public function create(Request $request, EntityManagerInterface $manager): Response {
        $data = $request->toArray();
        $recipe = $this->fillRecipe(new Recipe(), $data);

        foreach($recipe->getIngredients() as $ingredient) {
            $manager->persist($ingredient);
        }

        foreach($recipe->getSteps() as $step) {
            $manager->persist($step);
        }

        foreach($recipe->getExtras() as $extra) {
            $manager->persist($extra);
        }

        foreach($recipe->getTags() as $tag) {
            $manager->persist($tag);
        }

        $manager->persist($recipe);
        $manager->flush();

        return new Response('Created recipe "' . $recipe->getName() . '"!');
    }

    public function read(int $id, RecipeRepository $repository): Response {
        $recipe = $repository->find($id);

        return new Response(json_encode($recipe->toArray()));
    }

    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $manager,
        RecipeRepository $repository
    ): Response {
        $data = $request->toArray();
        $recipe = $repository->find($id);
        $updatedRecipe = $this->fillRecipe(new Recipe(), $data);

        $recipe->setName($updatedRecipe->getName());
        $recipe->setPeople($updatedRecipe->getPeople());
        $recipe->setPreparationTime($updatedRecipe->getPreparationTime());
        $recipe->setWaitTime($updatedRecipe->getWaitTime());

        foreach($recipe->getIngredients() as $ingredient) { $recipe->removeIngredient($ingredient); }
        foreach($updatedRecipe->getIngredients() as $ingredient) {
            $manager->persist($ingredient);
            $recipe->addIngredient($ingredient);
        }

        foreach($recipe->getSteps() as $step) { $recipe->removeStep($step); }
        foreach($updatedRecipe->getSteps() as $step) {
            $manager->persist($step);
            $recipe->addStep($step);
        }

        foreach($recipe->getExtras() as $extra) { $recipe->removeExtra($extra); }
        foreach($updatedRecipe->getExtras() as $extra) {
            $manager->persist($extra);
            $recipe->addExtra($extra);
        }

        foreach($recipe->getTags() as $tag) { $recipe->removeTag($tag); }
        foreach($updatedRecipe->getTags() as $tag) {
            $manager->persist($tag);
            $recipe->addTag($tag);
        }

        $manager->persist($recipe);
        $manager->flush();

        return new Response(json_encode($recipe->toArray()));
    }

    public function delete(int $id, EntityManagerInterface $manager, RecipeRepository $repository): Response {
        $recipe = $repository->find($id);
        $manager->remove($recipe);
        $manager->flush();

        return new Response('Deleted recipe "' . $recipe->getName() . '"!');
    }

    private function fillRecipe(Recipe $recipe, array $data): Recipe {
        $recipe->setName($data['name']);
        $recipe->setPeople($data['people']);
        $recipe->setPreparationTime($data['preparation_time']);
        $recipe->setWaitTime($data['wait_time']);

        foreach($data['ingredients'] as $ingredientData) {
            $ingredient = new Ingredient();
            $ingredient->setAmount($ingredientData['amount']);
            $ingredient->setQuantity($ingredientData['quantity']);
            $ingredient->setName($ingredientData['name']);

            $recipe->addIngredient($ingredient);
        }

        foreach($data['steps'] as $stepData) {
            $step = new Step();
            $step->setIndex($stepData['index']);
            $step->setText($stepData['text']);

            $recipe->addStep($step);
        }

        foreach($data['extras'] as $extraData) {
            $extra = new Extra();
            $extra->setText($extraData['text']);

            $recipe->addExtra($extra);
        }

        foreach($data['tags'] as $tagData) {
            $tag = new Tag();
            $tag->setName($tagData['name']);

            $recipe->addTag($tag);
        }

        return $recipe;
    }
}
