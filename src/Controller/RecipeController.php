<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeController extends AbstractController {
    public function create(
        Request $request,
        EntityManagerInterface $manager,
        SerializerInterface $serializer
    ): Response {
        $recipe = $serializer->deserialize(
            $request->getContent(), Recipe::class, 'json', [ 'groups' => 'recipe' ]
        );

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

    public function all(RecipeRepository $repository, SerializerInterface $serializer): Response {
        return new Response($serializer->serialize($repository->findAll(), 'json', [ 'groups' => 'recipe' ]));
    }

    public function list(RecipeRepository $repository, SerializerInterface $serializer): Response {
        return new Response($serializer->serialize($repository->findAll(), 'json', ['groups' => 'list']));
    }

    public function read(Recipe $recipe, SerializerInterface $serializer): Response {
        return new Response($serializer->serialize($recipe, 'json', [ 'groups' => 'recipe' ]));
    }

    public function update(
        Recipe $recipe,
        Request $request,
        EntityManagerInterface $manager,
        SerializerInterface $serializer
    ): Response {
        $updatedRecipe = $serializer->deserialize(
            $request->getContent(), Recipe::class, 'json', [ 'groups' => 'recipe' ]
        );

        $recipe->setName($updatedRecipe->getName());
        $recipe->setPeople($updatedRecipe->getPeople());
        $recipe->setPreparationTime($updatedRecipe->getPreparationTime());
        $recipe->setWaitTime($updatedRecipe->getWaitTime());

        foreach($recipe->getIngredients() as $ingredient) {
            $recipe->removeIngredient($ingredient);
        }
        foreach($updatedRecipe->getIngredients() as $ingredient) {
            $manager->persist($ingredient);
            $recipe->addIngredient($ingredient);
        }

        foreach($recipe->getSteps() as $step) {
            $recipe->removeStep($step);
        }
        foreach($updatedRecipe->getSteps() as $step) {
            $manager->persist($step);
            $recipe->addStep($step);
        }

        foreach($recipe->getExtras() as $extra) {
            $recipe->removeExtra($extra);
        }
        foreach($updatedRecipe->getExtras() as $extra) {
            $manager->persist($extra);
            $recipe->addExtra($extra);
        }

        foreach($recipe->getTags() as $tag) {
            $recipe->removeTag($tag);
        }
        foreach($updatedRecipe->getTags() as $tag) {
            $manager->persist($tag);
            $recipe->addTag($tag);
        }

        $manager->persist($recipe);
        $manager->flush();

        return new Response($serializer->serialize($recipe, 'json', [ 'groups' => 'recipe' ]));
    }

    public function delete(Recipe $recipe, EntityManagerInterface $manager): Response {
        $manager->remove($recipe);
        $manager->flush();

        return new Response('Deleted recipe "' . $recipe->getName() . '"!');
    }
}
