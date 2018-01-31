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
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class PhoneController extends Controller
{
    /**
     * @Rest\Get("/phones", name="app_phone_list")
     * @Rest\View(serializerGroups ={"list"})
     *
     * @SWG\Tag(name="phones")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of available phones",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=Phone::class)
     *     )
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher, Request $request, EntityManagerInterface $em)
    {
        $phones = $em->getRepository(Phone::class)->findAll();

        return $phones;
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
     *     description="Returns the details of a phone",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Phone::class)
     *     )
     * )
     */
    public function showAction(Phone $phone)
    {
        if (empty($phone)) {
            return View::create(['message' => 'Phone not found'], Response::HTTP_NOT_FOUND);
        }
        return $phone;
    }
}
