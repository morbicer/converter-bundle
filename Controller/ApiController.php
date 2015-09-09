<?php

namespace Morbicer\ConverterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    public function convertAction($amount = null, $from = null, $to = null)
    {
        $post = $this->getRequest()->request;

        if ($amount == null) {
            $amount = $post->filter('amount', null, false, FILTER_SANITIZE_NUMBER_FLOAT);
        }
        if ($from == null) {
            $from = $post->filter('from', null, false, FILTER_SANITIZE_STRING);
        }
        if ($to == null) {
            $to = $post->filter('to', null, false, FILTER_SANITIZE_STRING);
        }

        try {
            $convert = $this->get('morbicer_converter.convert');
            $converted = $convert->convert($amount, $from, $to);
            $result = array(
                'amount' => $converted->getAmount()/100,
                'currency' => (string)$converted->getCurrency(),
            );
        }
        catch(\Exception $e) {
            $result = array('error' => $e->getMessage());
        }

        $response =  new Response( json_encode($result) );
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
