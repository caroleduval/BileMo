<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Exception\ResourceValidationException;
use AppBundle\Service\Approver;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;

class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/users", name="app_user_list")
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
     *     description="Max number of users per page."
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
        $user = $this->getUser();
        $id=$user->getClient()->getId();
        $pager = $em->getRepository(User::class)->search(
            $id,
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
        $page=($request->get("page"))?$request->get("page"):1;
        $pager->setCurrentPage($page);
        $pagerfantaFactory   = new PagerfantaFactory();
        $paginatedCollection = $pagerfantaFactory->createRepresentation(
            $pager,
            new Route('app_user_list', array())
        );
        return $paginatedCollection;
    }
    /**
     * @Rest\Get(
     *     path = "/users/{id}",
     *     name = "app_user_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(serializerGroups ={"details"})
     */
    public function showAction(User $user, Approver $approver)
    {
        if (empty($user)) {
            return View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $admin = $this->getUser();

        if (!$approver->isGranted($user, $admin)){
            return View::create(['message' => 'You are not allowed to access this resource'], Response::HTTP_FORBIDDEN);
        }

        return $user;
    }

    /**
     * @Rest\Post(
     *     path = "/users",
     *     name = "app_user_add"
     * )
     * @Rest\QueryParam(
     *     name="role",
     *     requirements="user|admin",
     *     default="user",
     *     description="Type of user to be created"
     * )
     * @Rest\View(StatusCode=201)
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function createAction(ParamFetcherInterface $paramFetcher, User $user, EntityManagerInterface $em, ConstraintViolationList $violations, Request $request)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            throw new ResourceValidationException($message);
        }
        $client = $this->getUser()->getClient();
        $user->setClient($client);

        $type="ROLE_".strtoupper($paramFetcher->get('role'))."";
        $user->setRoles([$type]);


        $em->persist($user);
        $em->flush();
        return $this->view(
            $user,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('app_user_show',['id' =>$user->getId()],UrlGeneratorInterface::ABSOLUTE_URL)
            ]
        );
    }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/users/{id}",
     *     name = "app_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function deleteAction(User $user, EntityManagerInterface $em, Approver $approver)
    {
        if (empty($user)) {
            return View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $admin = $this->getUser();

        if (!$approver->isGranted($user, $admin)){
            return View::create(['message' => 'You are not allowed to access this resource'], Response::HTTP_FORBIDDEN);
        }

        if ($user){
            $em->remove($user);
            $em->flush();
        }
    }
}