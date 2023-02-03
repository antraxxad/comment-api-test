<?php

namespace App\Comment\Controller;


use App\Comment\dataManager;
use App\Comment\Entity\Comment;
use App\Comment\Form\CommentType;
use App\Comment\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/comment", name="app_comment_")
 */
class CommentController extends AbstractController
{
    public function __construct(CommentRepository $repository, Security $security) {
        $this->repository = $repository;
        $this->security = $security;
    }
    // Show
    /**
     * @Route("/", methods={"GET"})
     * @Route("/{page}", name="list", methods={"GET"})
     */
    public function list($page = null, Request $request): Response
    {
        $comments = $this->repository->findLast();
        if (preg_match('/^page1(.html)*$/', $page)) {
            $comments = $this->repository->findByPage('page1');
        }
        if (preg_match('/^page2(.html)*$/', $page)) {
            $comments = $this->repository->findByPage('page2');
        }

        $commentList = dataManager::buildComment($comments);

        $response = new Response(json_encode($commentList));

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    // Create
    /**
     * @Route("/create", name="_create", methods={"POST"})
     */
    public function commentCreate(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->security->getUser()) {
            $response = new Response(json_encode(["Error" => "You must be authenticated to post a comment"]), 400);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
            $comment->setAuthor($this->security->getUser());

            $em->persist($comment);
            $em->flush();

            $response = new Response(json_encode(["Success" => "The comment has been created"]), 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $response = new Response(json_encode(["Oups" => "An error occured"]), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    // Update
    /**
     * @Route("/{comment}", name="_update", methods={"PATCH","PUT"})
     */
    public function commentUpdate(Comment $comment, Request $request, EntityManager $em): Response
    {
        if ($this->security->getUser() != $comment->getAuthor()) {
            $response = new Response(json_encode(["Error" => "This action is forbidden"]), 400);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }

        $comment = $em->find(Comment, $comment->getId());
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
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
     * @Route("/{comment}", name="delete", methods={"DELETE"})
     */
    public function commentDelete(Comment $comment, Request $request, EntityManager $em): Response
    {
        if ($this->security->getUser() != $comment->getAuthor()) {
            $response = new Response(json_encode(["Error" => "This action is forbidden"]), 400);
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');

            return $response;
        }

        $em->remove($comment);
        $em->flush();

        $response = new Response(json_encode(["Success" => "This comment has been deleted"]), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}