<?php

namespace App\Comment\Controller;

use App\Comment\Entity\CommentResponse;
use App\Comment\Form\CommentType;
use App\Comment\Repository\CommentResponseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/response", name="app_response_")
 */
class ResponseController
{
    /**
     * @Route("/create", name="_create", methods={"POST"})
     */
    public function responseCreate(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->security->getUser()) {
            $response = new Response(json_encode(["Error" => "You must be authenticated to post a comment"]), 400);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }

        $commentResponse = new CommentResponse();

        $form = $this->createForm(ResponseType::class, $commentResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentResponse->setAuthor($this->security->getUser());

            $em->persist($commentResponse);
            $em->flush();

            $response = new Response(json_encode(["Success" => "The comment has been created"]), 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $response = new Response(json_encode(["Oups" => "An error occured"]), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    // Delete
    /**
     * @Route("/{response}", name="delete", methods={"DELETE"})
     */
    public function commentDelete(CommentResponse $commentResponse, Request $request, EntityManager $em): Response
    {
        if ($this->security->getUser() != $commentResponse->getAuthor()) {
            $response = new Response(json_encode(["Error" => "This action is forbidden"]), 400);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }

        $em->remove($commentResponse);
        $em->flush();

        $response = new Response(json_encode(["Success" => "This comment has been deleted"]), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}