<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Exception\ResourceValidationException;
use AppBundle\Service\Approver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as View;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;


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
     * @Rest\View(serializerGroups ={"Default","list"})
     *
     * @Cache(smaxage="3600", public=true)
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
     *
     * @SWG\Tag(name="users")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the details of a user linked to a client",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=User::class)
     *     )
     * )
     * @Cache(smaxage="86400", public=true)
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
     *
     * @SWG\Tag(name="users")
     * @SWG\Response(
     *     response=201,
     *     description="Create a user linked to a client",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=User::class)
     *     )
     * )
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
     *
     * @SWG\Tag(name="users")
     * @SWG\Response(
     *     response=204,
     *     description="Delete a user linked to a client",
     *     @SWG\Schema(
     *         type="array",
     *         @Model(type=User::class)
     *     )
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

        $em->remove($user);
        $em->flush();
    }
}
