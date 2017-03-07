<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Animal;
use Symfony\Component\BrowserKit\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/animal")
 */
class AnimalController extends Controller {

    /**
     * @Route("/", name="app_animal_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Animal');

        $animals = $repository->findAll();

        return [
            'entities' => $animals
        ];
    }

    /**
     * @Route("/new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new Animal();

        return $this->newOrEdit($request, $entity);
    }

    /**
     * @Route("/{id}/edit", requirements={"id"="^\d+$"})
     * @Template()
     */
    public function editAction(Request $request, Animal $entity)
    {
        return $this->newOrEdit($request, $entity);
    }

    /**
     * @Route("/{id}", requirements={"id"="^\d+$"})
     * @Method("GET")
     */
    public function showAction(Animal $entity)
    {
        return $this->render('AppBundle:Animal:show.html.twig', [
            'entity' => $entity
        ]);
    }

    /**
     * @Route("/{id}/delete", requirements={"id"="^\d+$"})
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
    protected function newOrEdit(Request $request, Animal $entity)
    {
        $form = $this->createFormBuilder($entity);

        $form->add('reference');
        $form->add('weight');
        $form->add('price', null, ['required' => false]);
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

        return [
            'form' => $form->createView()
        ];
    }
}
