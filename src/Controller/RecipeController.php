<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Extra;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\Step;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends AbstractController {
    /** @var EntityManager */
    private $manager;
    /** @var ManagerRegistry */
    private $doctrine;

    public function __construct() {
        $this->doctrine = $this->getDoctrine();
        $this->manager = $this->doctrine->getManager();
    }

    /** @throws ORMException */
    public function create(Request $request): Response {
        $data = $request->toArray();

        $recipe = new Recipe();
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
            $this->manager->persist($ingredient);
        }

        foreach($data['steps'] as $stepData) {
            $step = new Step();
            $step->setIndex($stepData['index']);
            $step->setText($stepData['text']);

            $recipe->addStep($step);
            $this->manager->persist($step);
        }

        foreach($data['extras'] as $extraData) {
            $extra = new Extra();
            $extra->setText($extraData['text']);

            $recipe->addExtra($extra);
            $this->manager->persist($extra);
        }

        $this->manager->persist($recipe);
        $this->manager->flush();

        return new Response('Created recipe "' . $recipe->getName() . '""!');
    }
}
