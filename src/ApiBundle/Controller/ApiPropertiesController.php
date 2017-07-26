<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Property;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiPropertiesController extends FOSRestController
{
    /**
     * REST action which returns property by id.
     * Method: GET, url: /api/properties/{id}.{_format}
     *
     * @param $id
     * @return mixed
     */
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

    /**
     * REST action which returns all properties.
     * Method: GET, url: /api/properties.{_format}
     *
     * @return mixed
     */
    public function getPropertiesAction()
    {
        $properties = $this
            ->getDoctrine()
            ->getRepository('ApiBundle:Property')
            ->findAll();

        return $properties;
    }

    /**
     * REST action which deletes property by id.
     * Method: DELETE, url: /api/properties/{id}.{_format}
     *
     * @param $id
     * @return mixed
     */
    public function deletePropertyAction($id)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();

        $propertyRepository = $this
            ->getDoctrine()
            ->getRepository('ApiBundle:Property');
        /** @var Property $type */
        $property = $propertyRepository->find($id);

        if ($property) {
            try {
                $em->remove($property);
                $em->flush();
                return new JsonResponse('The property id=' .$id . ' successfully deleted.');
            } catch (\Exception $exception) {
                throw new NotFoundHttpException(sprintf($exception));
            }
        } else {
            throw new NotFoundHttpException(sprintf('The property id=\'%s\' was not found.', $id));
        }
    }
}
