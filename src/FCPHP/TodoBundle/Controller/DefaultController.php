<?php

namespace FCPHP\TodoBundle\Controller;

use FCPHP\TodoBundle\Entity\Todo;
use FCPHP\TodoBundle\Form\TodoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
		/** @var \FCPHP\TodoBundle\Entity\TodoRepository $repo */
		$repo = $this->container->get('doctrine')->getRepository('TodoBundle:Todo');

		$todo_form = $this->container->get('form.factory')->create(new TodoType());

		if('POST' === $request->getMethod())
		{
			$todo_form->bind($request);

			if($todo_form->isValid())
			{
				/** @var \Doctrine\ORM\EntityManager $em */
				$em = $this->container->get('doctrine')->getEntityManager();

				/** @var \FCPHP\TodoBundle\Entity\Todo $todo */
				$todo = $todo_form->getData();

				$em->persist($todo);
				$em->flush();
			}
		}

		$incomplete = $repo->findIncompleteTodos();
		$completed = $repo->findCompletedTodos();

		return $this->render('TodoBundle:Default:index.html.twig', array(
			'incomplete' => $incomplete,
			'completed' => $completed,
			'todo_form' => $todo_form->createView()
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

	public function deleteAction($id)
	{
		/** @var \FCPHP\TodoBundle\Entity\TodoRepository $repo */
		$repo = $this->container->get('doctrine')->getRepository('TodoBundle:Todo');

		/** @var \FCPHP\TodoBundle\Entity\Todo $todo */
		$todo = $repo->find($id);

		if(!$todo)
		{
			throw new NotFoundHttpException('That todo was not found.');
		}

		/** @var \Doctrine\ORM\EntityManager $em */
		$em = $this->container->get('doctrine')->getEntityManager();

		$em->remove($todo);
		$em->flush();

		return $this->redirect($this->generateUrl('todo_homepage'));
	}
}
