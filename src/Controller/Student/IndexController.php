<?php

namespace App\Controller\Student;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/student", name="index")
     */
    public function index()
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
