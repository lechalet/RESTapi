<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pokemon")
 */
class PokemonController extends AbstractController
{
    /**
     * @Route("/", name="pokemon_index", methods={"GET"})
     */
    public function index(PokemonRepository $pokemonRepository, NormalizerInterface $normalizer): Response
    {
        $pokemons = $pokemonRepository->findAll();
        
        $pokemonNormalises = $normalizer->normalize($pokemons);

        $json = json_encode($pokemonNormalises);
        
        echo $json;
        return $this->render('pokemon/index.html.twig');
    }

    /**
     * @Route("/new", name="pokemon_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pokemon);
            $entityManager->flush();

            return $this->redirectToRoute('pokemon_index');
        }

        $response = new Response($form, 200, [
            "Content-Type" => "application/json"
        ]);
        return $response;
    }

    /**
     * @Route("/{id}", name="pokemon_show", methods={"GET"})
     */
    public function show(Pokemon $pokemon, NormalizerInterface $normalizer): Response
    {
        $pokemonNormalises = $normalizer->normalize($pokemon);

        $json = json_encode($pokemonNormalises);
        echo $json;
        
        return $this->render('pokemon/show.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="pokemon_edit", methods={"PUT"})
     */
    public function edit(Request $request, Pokemon $pokemon): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pokemon_index');
        }

        $response = new Response($form, 200, [
            "Content-Type" => "application/json"
        ]);
        return $response;
    }

    /**
     * @Route("/{id}", name="pokemon_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pokemon $pokemon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pokemon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pokemon);
            $entityManager->flush();
        }
        
        $response = new Response($request, 200, [
            "Content-Type" => "application/json"
        ]);
        return $response;
    }
}
