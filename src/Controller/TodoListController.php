<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoListController extends AbstractController
{
    public static $session_key = "todolist";

    #[Route('/', name: 'list')]
    public function list(RequestStack $requestStack): Response
    {
        //$list = ["Florian", "Clémentce", "Kanelle", "Keshia"];

        $session = $requestStack->getSession();
        return $this->render('todo_list/index.html.twig', [
            'listSession' => $session->get(self::$session_key),
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $list = $session->get(self::$session_key);

        // INFORMATION - Query pour GET | request pour POST

        //$list[] = $requestStack->getMainRequest()->query->get("addItem");
        $list[] = $requestStack->getMainRequest()->request->get("addItem");
        $session->set(self::$session_key, $list);

        return $this->redirectToRoute("list");
    }

    #[Route('/remove', name: 'remove')]
    public function remove(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $list = $session->get(self::$session_key);
        $removedElement = $requestStack->getMainRequest()->query->get("removedItem");
        unset($list[$removedElement]);
        $newList = [];
        foreach ($list as $item){
            $newList[] = $item;
        }
        $session->set(self::$session_key, $newList);
        return $this->redirectToRoute("list");
    }
}