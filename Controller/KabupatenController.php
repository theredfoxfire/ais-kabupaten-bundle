<?php

namespace Ais\KabupatenBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Ais\KabupatenBundle\Exception\InvalidFormException;
use Ais\KabupatenBundle\Form\KabupatenType;
use Ais\KabupatenBundle\Model\KabupatenInterface;


class KabupatenController extends FOSRestController
{
    /**
     * List all kabupatens.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing kabupatens.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many kabupatens to return.")
     *
     * @Annotations\View(
     *  templateVar="kabupatens"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getKabupatensAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $offset = null == $offset ? 0 : $offset;
        $limit = $paramFetcher->get('limit');

        return $this->container->get('ais_kabupaten.kabupaten.handler')->all($limit, $offset);
    }

    /**
     * Get single Kabupaten.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Kabupaten for a given id",
     *   output = "Ais\KabupatenBundle\Entity\Kabupaten",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the kabupaten is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="kabupaten")
     *
     * @param int     $id      the kabupaten id
     *
     * @return array
     *
     * @throws NotFoundHttpException when kabupaten not exist
     */
    public function getKabupatenAction($id)
    {
        $kabupaten = $this->getOr404($id);

        return $kabupaten;
    }

    /**
     * Presents the form to use to create a new kabupaten.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newKabupatenAction()
    {
        return $this->createForm(new KabupatenType());
    }
    
    /**
     * Presents the form to use to edit kabupaten.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisKabupatenBundle:Kabupaten:editKabupaten.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the kabupaten id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when kabupaten not exist
     */
    public function editKabupatenAction($id)
    {
		$kabupaten = $this->getKabupatenAction($id);
		
        return array('form' => $this->createForm(new KabupatenType(), $kabupaten), 'kabupaten' => $kabupaten);
    }

    /**
     * Create a Kabupaten from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new kabupaten from the submitted data.",
     *   input = "Ais\KabupatenBundle\Form\KabupatenType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisKabupatenBundle:Kabupaten:newKabupaten.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postKabupatenAction(Request $request)
    {
        try {
            $newKabupaten = $this->container->get('ais_kabupaten.kabupaten.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newKabupaten->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_kabupaten', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing kabupaten from the submitted data or create a new kabupaten at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\KabupatenBundle\Form\KabupatenType",
     *   statusCodes = {
     *     201 = "Returned when the Kabupaten is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisKabupatenBundle:Kabupaten:editKabupaten.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the kabupaten id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when kabupaten not exist
     */
    public function putKabupatenAction(Request $request, $id)
    {
        try {
            if (!($kabupaten = $this->container->get('ais_kabupaten.kabupaten.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $kabupaten = $this->container->get('ais_kabupaten.kabupaten.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $kabupaten = $this->container->get('ais_kabupaten.kabupaten.handler')->put(
                    $kabupaten,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $kabupaten->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_kabupaten', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing kabupaten from the submitted data or create a new kabupaten at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Ais\KabupatenBundle\Form\KabupatenType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "AisKabupatenBundle:Kabupaten:editKabupaten.html.twig",
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the kabupaten id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when kabupaten not exist
     */
    public function patchKabupatenAction(Request $request, $id)
    {
        try {
            $kabupaten = $this->container->get('ais_kabupaten.kabupaten.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $kabupaten->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_kabupaten', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a Kabupaten or throw an 404 Exception.
     *
     * @param mixed $id
     *
     * @return KabupatenInterface
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($kabupaten = $this->container->get('ais_kabupaten.kabupaten.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $kabupaten;
    }
    
    public function postUpdateKabupatenAction(Request $request, $id)
    {
		try {
            $kabupaten = $this->container->get('ais_kabupaten.kabupaten.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $kabupaten->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_kabupaten', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
	}
}
