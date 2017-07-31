<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Property;
use ApiBundle\Entity\User;
use ApiBundle\Form\ApiPropertyForm;
use ApiBundle\Security\Access;
use Doctrine\DBAL\Schema\View;
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
     * @param $id
     * @return null|object
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
     * @return array|View
     */
    public function getPropertiesAction()
    {
        $properties = $this
            ->getDoctrine()
            ->getRepository('ApiBundle:Property')
            ->findAll();
        $serializer = $this->container->get('serializer');
        $response = $serializer->serialize($properties, 'json');
        $request = Request::createFromGlobals();
        $access = new Access();
        $accessToken = $access->signRequest('GET', $request->headers->get('host') . $request->getPathInfo(), $response, time(), $this->getParameter('secret'));
        $properties['X-AUTH-TOKEN'] = $accessToken;
//        $properties['X-USER-ID'] = 2;
        return $properties;
    }

    /**
 * REST action update property all field.
 * Method: PUT, url: /api/properties/{id}.{_format}
 * Update existing property from the submitted data or create a new property.
 * @param Request $request
 * @param $id
 * @return View|\FOS\RestBundle\View\View|\Symfony\Component\Form\Form
 */
    public function putPropertiesAction(Request $request, $id)
    {
        $propertyRepository = $this->getDoctrine()->getRepository('ApiBundle:Property');
        $property = $propertyRepository->find($id);

        if ($property === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ApiPropertyForm::class, $property, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $property->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
    }

    /**
     * REST action update property  field.
     * Method: PATCH, url: /api/properties/{id}.{_format}
     * Update existing property from the submitted data or create a new property.
     * @param Request $request
     * @param $id
     * @return View|\FOS\RestBundle\View\View|\Symfony\Component\Form\Form
     */
    public function patchPropertiesAction(Request $request, $id)
    {
        /**
         * @var $property Property
         */
        $property = $this->getDoctrine()->getRepository('ApiBundle:Property')->find($id);

        if ($property === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ApiPropertyForm::class, $property, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all(), false);

        if (!$form->isValid()) {
            return $form;
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $routeOptions = [
            'id' => $property->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('', $routeOptions, Response::HTTP_NO_CONTENT);
    }

    /**
     * REST action creates a new properties.
     * Method: POST, url: /api/properties
     * @param Request $request
     * @return \FOS\RestBundle\View\View|\Symfony\Component\Form\Form
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

        $em = $this->getDoctrine()->getManager();

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
     * @param $id
     * @return JsonResponse
     */
    public function deletePropertyAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $propertyRepository = $this->getDoctrine()->getRepository('ApiBundle:Property');
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
