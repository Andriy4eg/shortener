<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Shortener extends Controller {

    public function index(){
        $shortener = $this->container->get('Shortener');
        // Simple test of encode/decode operations
        for ( $i = 0; $i < 20000; $i++ ) {
            $encoded = $shortener->Encode($i);
//            var_dump($encoded); die;

            if ($shortener->Decode($encoded) != $i) {
                printf("%s is not %s", $shortener->Encode($i), $i);
                echo('<br/>');
            }
        }
    }
}