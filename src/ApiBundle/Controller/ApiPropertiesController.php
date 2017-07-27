<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Property;
use ApiBundle\Form\ApiPropertyForm;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

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
     * REST action creates a new properties.
     * Method: POST, url: /api/properties
     *
     * @param Request $request the request object
     * @return mixed
     */
    public function postPropertyAction(Request $request)
    {
        $form = $this->createForm(ApiPropertyForm::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }
        /**
         * @var $property Property
         */
        $property = $form->getData();

        $em = $this
            ->getDoctrine()
            ->getManager();

        $em->persist($property);
        $em->flush();

        $routeOptions = [
            'id' => $property->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_CREATED);
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
