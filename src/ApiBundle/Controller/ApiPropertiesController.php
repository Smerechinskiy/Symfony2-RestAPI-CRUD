<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\BrowserKit\Request;

class ApiPropertiesController extends FOSRestController
{
       public function getPropertyAction($id)
    {
        $propertyRepository = $this
            ->getDoctrine()
            ->getRepository('ApiBundle:Property');

        $property = NULL;
        try {
            $property = $propertyRepository->find($id);
        } catch (\Exception $exception) {
            $type = NULL;
        }

        if (!$property) {
            throw new NotFoundHttpException(sprintf('The property id=\'%s\' was not found.', $id));
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
