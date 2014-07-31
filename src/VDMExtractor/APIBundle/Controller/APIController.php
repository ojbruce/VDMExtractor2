<?php

namespace VDMExtractor\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


use VDMExtractor\ExtractorBundle\Entity;

/**
 * API's controller
 *
 */
class APIController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VDMExtractorAPIBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * Action that will render a unique post
     * Using doctrine to retrieve
     */
    public function getUniqueAction($id)
    {

        /** Getting doctrine entity */
        $repository = $this->getDoctrine()
                           ->getManager()
                           ->getRepository('VDMExtractorExtractorBundle:Post');

        /** Getting back a specific */                   
        $post = $repository->findOneById($id);

        if ($post) {
            $result[] = $post->toArray();
        } else {
            $result   = ['success' => 'false']; 
        }

        return new Response(json_encode(array([
            'post' => $result
        ])));
    }

    /**
     * Action that will render stocked posts
     * Using doctrine to retrieve
     */
    public function getAction()
    {
        // We retrieve each querystrings
        $request = $this->getRequest();
        $to      = $request->query->get('to');
        $from    = $request->query->get('from');
        $author  = $request->query->get('author');
        
        // Getting doctrine entity 
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('VDMExtractorExtractorBundle:Post');


        // Create query with filters or not
        $queryDQL = 'SELECT p FROM VDMExtractorExtractorBundle:Post p WHERE ';

        // Author filter
        if ($author) {
            $queryDQL .= 'p.author = :author AND ';
        }

        // Date filter
        if ($from) {
            $queryDQL .= 'p.date >= :from AND ';

            if ($to) {
                $queryDQL .= 'p.date <= :to AND ';
            }
        }

        $queryDQL .= '1=1';

        // Creating query
        $query = $em->createQuery(
            $queryDQL
        );

        // Set query parameters
        if ($author) {
            $query->setParameter('author', $author);
        }

        if ($from) {
            $query->setParameter('from', $from);

            if ($to) {
                $query->setParameter('to', $to);
            }
        }

        $posts = $query->getResult();

        $postsJsonified = [];
        foreach ($posts as $post) {
            $postsJsonified[] = $post->toArray();
        }

        return new Response(json_encode([
            'posts'  => $postsJsonified,
            'count' => count($postsJsonified),
        ]));
    }

}