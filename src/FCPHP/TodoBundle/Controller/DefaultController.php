<?php

namespace FCPHP\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
		/** @var \PCPHP\TodoBundle\Entity\TodoRepository $repo */
		$repo = $this->container->get('doctrine')->getRepository('TodoBundle:Todo');

		$incomplete = $repo->findIncompleteTodos();
		$completed = $repo->findCompletedTodos();

        return $this->render('TodoBundle:Default:index.html.twig', array(
			'incomplete' => $incomplete,
			'completed' => $completed
		));
    }
}
