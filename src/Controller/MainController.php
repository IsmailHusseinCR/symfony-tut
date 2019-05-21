<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    // Dit zijn annotations, een alternatief is route.yaml (dat lijkt dan meer op Laravel)
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/custom/{name?}", name="custom")
     */

    // begin met spelen met controllers
    public function secondRoute(Request $request) : Response{
        $name = $request->get('name');
        return $this->render('home/custom.html.twig', [
            'name' => $name
        ]);
    }
}
