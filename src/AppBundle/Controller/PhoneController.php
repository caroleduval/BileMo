<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;

class PhoneController extends FOSRestController
{
    /**
     * @Rest\Get("/phones", name="app_phone_list")
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="Page on demand"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="5",
     *     description="Max number of phones per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View(serializerGroups ={"Default","list"})
     *
     * @SWG\Tag(name="phones")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of available phones",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Phone::class)
     *     )
     * )
     * @Cache(smaxage="3600", public=true)
     */
    public function listAction(ParamFetcherInterface $paramFetcher, Request $request, EntityManagerInterface $emi)
    {
        $pager = $emi->getRepository(Phone::class)->search(
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );


        $page=($request->get("page"))?$request->get("page"):1;
        $pager->setCurrentPage($page);
        $pagerfantaFactory   = new PagerfantaFactory();
        $paginatedCollection = $pagerfantaFactory->createRepresentation(
            $pager,
            new Route('app_phone_list', array())
        );

        return $paginatedCollection;
    }

    /**
     * @Rest\Get(
     *     path = "/phones/{id}",
     *     name = "app_phone_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(serializerGroups ={"details"})
     *
     * @SWG\Tag(name="phones")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the details of a phone, identified by his identification number in the database",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Phone::class)
     *     )
     * )
     * @Cache(smaxage="86400", public=true)
     */
    public function showAction(Phone $phone=null)
    {
        if (empty($phone)) {
            return $this->view(['message' => 'Sorry, this phone is not available'], Response::HTTP_NOT_FOUND);
        }
        return $phone;
    }
}
