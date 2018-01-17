<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use AppBundle\Representation\Phones;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends Controller
{

    /**
     * @Rest\Get("/phones", name="app_phone_list")
     * @Rest\View
     */
    public function listAction(EntityManagerInterface $em, Request $request)
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
     * @Rest\View
     */
    public function showAction(Phone $phone)
    {
        return $phone;
    }
}
