<?php

namespace App\Controller;

use App\Entity\Dresseur;
use App\Form\DresseurType;
use App\Repository\DresseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dresseur")
 */
class DresseurController extends AbstractController
{
    /**
     * @Route("/", name="dresseur_index", methods={"GET"})
     */
    public function index(DresseurRepository $dresseurRepository, NormalizerInterface $normalizer): Response
    {
        $dresseurs = $dresseurRepository->findAll();

        $dresseursNormalises = $normalizer->normalize($dresseurs);

        $json = json_encode($dresseursNormalises);
        echo $json;
        
        return $this->render('dresseur/index.html.twig');
    }

    /**
     * @Route("/", name="dresseur_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $dresseur = new Dresseur();
        $form = $this->createForm(DresseurType::class, $dresseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dresseur);
            $entityManager->flush();

            return $this->redirectToRoute('dresseur_index');
        }

        $response = new Response($form, 200, [
            "Content-Type" => "application/json"
        ]);
        return $response;
    }

    /**
     * @Route("/{id}", name="dresseur_show", methods={"GET"})
     */
    public function show(Dresseur $dresseur, NormalizerInterface $normalizer): Response
    {

        $dresseursNormalises = $normalizer->normalize($dresseur);

        $json = json_encode($dresseursNormalises);
        echo $json;
        
        return $this->render('dresseur/show.html.twig');
    }

    /**
     * @Route("/{id}/", name="dresseur_edit", methods={"PUT"})
     */
    public function edit(Request $request, Dresseur $dresseur): Response
    {
        $form = $this->createForm(DresseurType::class, $dresseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dresseur_index');
        }
        
        $response = new Response($form, 200, [
            "Content-Type" => "application/json"
        ]);
        return $response;
    }

    /**
     * @Route("/{id}", name="dresseur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Dresseur $dresseur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dresseur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dresseur);
            $entityManager->flush();
        }

        $response = new Response($request, 200, [
            "Content-Type" => "application/json"
        ]);
        return $response;
    }
}
