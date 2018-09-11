<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Container;

class Shortener
{
    protected $alphabet;
    protected $base;

    function __construct(Container $container) {
        $shortParam = $container->getParameter('shortener');
        $this->alphabet = $shortParam['alphabet'];
        $this->base = mb_strlen($this->alphabet);
    }

    public function Encode( $i ) {

        if ( $i == 0 )
            return $this->alphabet[0];

        $s = '';

        while ( $i > ($this->base - 1) ) {
            $s = $this->alphabet[ (int) fmod($i, $this->base -  1 ) ] .$s;
            $i = (int) floor( $i / $this->base );
        }

        return $this->alphabet[$i].$s;
    }

    public function Decode( $s ) {
        $i = 0;

        for ( $i; $i < mb_strlen($s); $i++ ) {
            $i = ($i * $this->base) + mb_strpos($this->alphabet, $s[$i]);
        }

        return $i;
    }
}