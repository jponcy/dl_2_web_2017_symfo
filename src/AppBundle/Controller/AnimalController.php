<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Animal;

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

        return $this->render('AppBundle:Animal:new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
