<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Species;
use Symfony\Component\BrowserKit\Response;

class SpeciesController extends Controller {

    /**
     * @Route("/species/", name="app_species_index")
     * @Method("GET")
     */
    public function indexAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Species');

        $species = $repository->findAll();

        return $this->
            render('AppBundle:Species:index.html.twig', [
                'entities' => $species
            ]);
    }

    /**
     * @Route("/species/new")
     */
    public function newAction(Request $request)
    {
        $entity = new Species();

        return $this->newOrEdit('AppBundle:Species:new.html.twig',
                $request, $entity);
    }

    /**
     * @Route("/species/{id}/edit", requirements={"id"="^\d+$"})
     */
    public function editAction(Request $request, Species $entity)
    {
        return $this->newOrEdit('AppBundle:Species:edit.html.twig',
                $request, $entity);
    }

    /**
     * @Route("/species/{id}", requirements={"id"="^\d+$"})
     * @Method("GET")
     */
    public function showAction(Species $entity)
    {
        return $this->render('AppBundle:Species:show.html.twig', [
            'entity' => $entity
        ]);
    }

    /**
     * @Route("/species/{id}/delete", requirements={"id"="^\d+$"})
     * @Method("GET")
     */
    public function deleteAction(Species $entity)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($entity);
        $manager->flush();

        return $this->redirectToRoute('app_species_index');
    }

    /**
     * Do stuff for edit or create new species.
     *
     * @param string $template
     * @param Request $request
     * @param Species $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function newOrEdit(string $template, Request $request, Species $entity)
    {
        $form = $this->createFormBuilder($entity);

        $form->add('name');
        $form->add('price');

        $form->add('submit', 'submit');

        $form = $form->getForm();

        $form->handleRequest($request);

        if ($request->getMethod() == 'POST' && $form->isValid()) {
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();

            $manager->persist($entity);
            $manager->flush();

            return $this->redirectToRoute('app_species_index');
        }

        return $this->render($template, [
            'form' => $form->createView()
        ]);
    }
}
