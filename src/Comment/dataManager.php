<?php

namespace App\Comment;

use App\Comment\Entity\Comment;
use App\Comment\Entity\CommentResponse;
use Doctrine\Common\Collections\Collection;

class dataManager
{
    public function buildComment($comment): Array
    {
        $comments = [];
        $commentResponses = [];
        foreach ($comment as $com) {
            if (!empty($com->getResponses())) {
                $responses = $com->getResponses();
                foreach ($responses as $response) {
                    $response = new CommentResponse($response);
                    dump($response->getId());
                }
            }

            $author = ['username' => $com->getAuthor()->getUserIdentifier(), 'name' => $com->getAuthor()->getUsername()];
            $comment = [
                "id" => $com->getId(),
                "message" => htmlentities($com->getMessage()),
                "rate" => $com->getRate(),
                "page" => $com->getPage(),
                "date" => $com->getUpdatedAt(),
                "author" => $author,
                "responses" => $responses ?? null
            ];
            $comments[] = $comment;
        }
        return $comments;
    }

    public function buildResponse($response): Array
    {
        $responses = [];
        foreach ($response as $res) {
            $author = ['id' => $res->getAuthor()->getId(), 'name' => $res->getAuthor()->getName()];
            $element = [
                "id" => $res->getId,
                "message" => htmlentities($res->getMessage),
                "comment" => $res->getComment->getId,
                "date" => $res->getUpdatedAt,
                "author" => $author,
                "responses" => $responses
            ];
            $responses[] = $element;
        }
        return $responses;
    }
}