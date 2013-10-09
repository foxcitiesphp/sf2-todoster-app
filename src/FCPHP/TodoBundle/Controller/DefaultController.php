<?php

namespace FCPHP\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
		/** @var \FCPHP\TodoBundle\Entity\TodoRepository $repo */
		$repo = $this->container->get('doctrine')->getRepository('TodoBundle:Todo');

		$incomplete = $repo->findIncompleteTodos();
		$completed = $repo->findCompletedTodos();

        return $this->render('TodoBundle:Default:index.html.twig', array(
			'incomplete' => $incomplete,
			'completed' => $completed
		));
    }

	public function completeAction($id)
	{
		/** @var \FCPHP\TodoBundle\Entity\TodoRepository $repo */
		$repo = $this->container->get('doctrine')->getRepository('TodoBundle:Todo');

		/** @var \FCPHP\TodoBundle\Entity\Todo $todo */
		$todo = $repo->find($id);

		if(!$todo)
		{
			throw new NotFoundHttpException('That todo was not found.');
		}

		$todo->setCompleted(true);
		/** @var \Doctrine\ORM\EntityManager $em */
		$em = $this->container->get('doctrine')->getEntityManager();
		$em->persist($todo);
		$em->flush();

		return $this->redirect($this->generateUrl('todo_homepage'));
	}
}
