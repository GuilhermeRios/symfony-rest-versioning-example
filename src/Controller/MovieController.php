<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MovieController.
 *
 * @Rest\RouteResource("Movie")
 */
class MovieController extends AbstractFOSRestController
{
    /**
     * List all movies.
     *
     * @return Response
     * @Serializer\Since("v2")
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        /** @var Movie[] $movies */
        $movies = $repository->findAll();

        return $this->handleView($this->view($movies));
    }

    /**
     * Create a movie.
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Delete a movie.
     *
     * @return Response
     */
    public function deleteAction()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        /** @var Movie[] $movies */
        $movies = $repository->findAll();

        return $this->handleView($this->view($movies));
    }
}
