<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ShortUrl;
use App\Entity\Stat;

class Shortener extends Controller {

    public function index(Request $request){
        if ( $url = filter_var($request->get('url', FILTER_VALIDATE_URL) ) ) {

            $shortener = $this->container->get('Shortener');

            $linkId = $shortener->Decode($url);

            $em = $this->getDoctrine()->getManager();

            $shortUrl = $em->getRepository(ShortUrl::class)->find($linkId);

            if ( $shortUrl ) {
                $userIp = $request->getClientIp();

                $stat = new Stat();

                $stat->setIp($userIp);
                $stat->setLinkId($linkId);

                $em->persist($stat);
                $em->flush();

                return $this->redirect($shortUrl->getLink());
            }

        }

        return $this->render('shortener/index.html.twig');
    }

    public function shortUrl(Request $request){
        $link = $request->get('link');

        if ( $link ) {
            $shortener = $this->container->get('Shortener');
            $em = $this->getDoctrine()->getManager();

            $shortUrl = new ShortUrl();

            $shortUrl->setLink($link);

            $em->persist($shortUrl);
            $em->flush();

            $shortLink = $shortener->Encode( $shortUrl->getId()-1 );
            $shortUrl->setShortUrl( $shortLink );

            $em->persist($shortUrl);

            $em->flush();
        }

        if ( !$shortLink )
            return $this->redirect('/');
        else
            return $this->render('shortener/short.html.twig', ['shortLink' => $shortLink]);
    }
}