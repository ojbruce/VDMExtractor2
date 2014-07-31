<?php

namespace VDMExtractor\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    
    public function getUniqueAction()
    {
        $id = (int) $this->params()->fromRoute('id', null);

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $post          = $entityManager->find('Extractor\Entity\Post', $id);

        if ($post) {
            $result[] = $post->toArray();
        } else {
            $result   = ['success' => 'false']; 
        }

        return new JsonModel([
            'post'  => $result
        ]);
    }

    public function getAction()
    {
        // Collect GET params
        $to     = $this->params()->fromQuery('to', null);
        $from   = $this->params()->fromQuery('from', null);
        $author = $this->params()->fromQuery('author', null);

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        // Create query with filters or not
        $queryDQL = 'SELECT p FROM Extractor\Entity\Post p WHERE ';

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

        $queryDQL .= '1 = 1';
        $query     = $entityManager->createQuery($queryDQL);

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

        return new JsonModel([
            'posts'  => $postsJsonified,
            'count' => count($postsJsonified),
        ]);
    }

}