<?php

namespace App\Controller;

use App\Entity\Stat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class Admin extends Controller {

    public function index( Request $request ) {
        if ( $url = $request->get('url') ) {

            $shortener = $this->container->get('Shortener');

            $linkId = $shortener->Decode($url);

            $em = $this->getDoctrine()->getManager();

            $stat = $em->getRepository(Stat::class)->findBy(['link_id' => $linkId]);

            return $this->render('shortener/stat.html.twig', ['stat' => $stat]);

        } else {
            return $this->redirect('/');
        }
    }
}