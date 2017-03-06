<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Animal;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\BrowserKit\Response;

class AnimalController extends Controller {

    /**
     * @Route("/animal/", name="app_animal_index")
     * @Method("GET")
     */
    public function indexAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Animal');

        $animals = $repository->findAll();

        return $this->
            render('AppBundle:Animal:index.html.twig', [
                'entities' => $animals
            ]);
    }

    /**
     * @Route("/animal/new")
     */
    public function newAction(Request $request)
    {
        $entity = new Animal();

        return $this->newOrEdit('AppBundle:Animal:new.html.twig',
                $request, $entity);
    }

    /**
     * @Route("/animal/{id}/edit", requirements={"id"="^\d+$"})
     */
    public function editAction(Request $request, Animal $entity)
    {
        return $this->newOrEdit('AppBundle:Animal:edit.html.twig',
                $request, $entity);
    }

    /**
     * @Route("/animal/{id}", requirements={"id"="^\d+$"})
     * @Method("GET")
     */
    public function showAction(Animal $entity)
    {
        return $this->render('AppBundle:Animal:show.html.twig', [
            'entity' => $entity
        ]);
    }

    /**
     * @Route("/animal/{id}/delete", requirements={"id"="^\d+$"})
     * @Method("GET")
     */
    public function deleteAction(Animal $entity)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($entity);
        $manager->flush();

        return $this->redirectToRoute('app_animal_index');
    }

    /**
     * Do stuff for edit or create new animal.
     *
     * @param string $template
     * @param Request $request
     * @param Animal $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function newOrEdit(string $template, Request $request, Animal $entity)
    {
        $form = $this->createFormBuilder($entity);

        $form->add('reference');
        $form->add('weight');
        $form->add('price');
        $form->add('birthdate');
        $form->add('color');
        $form->add('species');

        $form->add('submit', 'submit');

        $form = $form->getForm();

        $form->handleRequest($request);

        if ($request->getMethod() == 'POST' && $form->isValid()) {
            $doctrine = $this->getDoctrine();
            $manager = $doctrine->getManager();

            $manager->persist($entity);
            $manager->flush();

            return $this->redirectToRoute('app_animal_index');
        }

        return $this->render($template, [
            'form' => $form->createView()
        ]);
    }
}
