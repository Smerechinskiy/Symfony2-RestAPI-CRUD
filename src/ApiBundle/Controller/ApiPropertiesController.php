<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ApiPropertiesController extends FOSRestController
{
       public function getPropertyAction($id)
    {
        $property = $this
            ->getDoctrine()
            ->getRepository('ApiBundle:Property')
            ->find($id);

        if (is_null($property)) {
            throw $this->createNotFoundException('No such property');
        }

        return $property;
    }

    public function getPropertiesAction()
    {
        $properties = $this
            ->getDoctrine()
            ->getRepository('ApiBundle:Property')
            ->findAll();

        return $properties;
    }
}
