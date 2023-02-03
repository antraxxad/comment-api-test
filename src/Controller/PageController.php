<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class PageController extends AbstractController
{
    /**
     * @Route("/", name="app_default", methods={"GET"})
     * @Route("/index.html", name="app_page_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('page/index.html.twig', []);
    }


    /**
     * @Route("/page1.html", name="app_page1", methods={"GET"})
     */
    public function page1(): Response
    {
        return $this->render('page/index.html.twig', []);
    }

    /**
     * @Route("/page2.html", name="app_page2", methods={"GET"})
     */
    public function page2(): Response
    {
        return $this->render('page/index.html.twig', []);
    }
}
