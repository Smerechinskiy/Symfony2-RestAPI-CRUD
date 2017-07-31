<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ApiBundle\Form\PropertyForm;
use Symfony\Component\HttpFoundation\Request;

class PropertiesController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $propertiesRepository = $em->getRepository('ApiBundle:Property');
        $properties = $propertiesRepository->findAll();

        $data = array('properties' => $properties);
        return $this->render('ApiBundle:Property:index.html.twig', $data);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public  function  deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $propertiesRepository = $em->getRepository('ApiBundle:Property');
        $property = $propertiesRepository->find((int)$id);
        if ($property) {
            $em->remove($property);
            $em->flush();
        } else {
            throw $this->createNotFoundException('Не удалось удалить, данное объявление не найдено.');
        }

        return $this->redirect($this->generateUrl('property_page'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public  function  formEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $propertiesRepository = $em->getRepository('ApiBundle:Property');
        $property = $propertiesRepository->find((int)$id);

        if (!$property) {
            throw $this->createNotFoundException('Не удалось найти объявление.');
        }

        $form = $this->createForm(new PropertyForm(), $property);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($property);
            $em->flush();

            return $this->redirect($this->generateUrl('property_page'));
        }

        return $this->render('ApiBundle:Property:edit.html.twig',  array(
            'property' => $property,
            'form' => $form->createView(),
        ));

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function formCreateAction(Request $request)
    {

        $property = new Property();

        $form = $this->createForm(new PropertyForm(), $property);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();

            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Новое объявление создано!');

            return $this->redirect($this->generateUrl('property_page'));
        }

        return $this->render('ApiBundle:Property:create.html.twig', array('form' => $form->createView()));

    }

}
