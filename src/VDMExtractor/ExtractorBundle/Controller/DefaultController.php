<?php

namespace VDMExtractor\ExtractorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VDMExtractorExtractorBundle:Default:index.html.twig', array('name' => $name));
    }
}
