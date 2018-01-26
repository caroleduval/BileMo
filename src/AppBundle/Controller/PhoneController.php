<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View as View;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;

class PhoneController extends Controller
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
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
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
     * @Rest\View()
     */
    public function listAction(ParamFetcherInterface $paramFetcher, Request $request, EntityManagerInterface $em)
    {
        $pager = $em->getRepository(Phone::class)->search(
            $paramFetcher->get('order'),
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
     */
    public function showAction(Phone $phone)
    {
        if (empty($phone)) {
            return View::create(['message' => 'Phone not found'], Response::HTTP_NOT_FOUND);
        }
        return $phone;
    }
}

///**
// * @Rest\Get("/phones", name="app_phone_list")
// * @Rest\View(serializerGroups ={"list"})
// */
//public function listAction(EntityManagerInterface $em)
//{
//    $phones = $em->getRepository(Phone::class)->findAll();
//
//    return $phones;
//}
