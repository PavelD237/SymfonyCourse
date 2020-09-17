<?php

namespace App\Controller\Student;

use App\Entity\Course;
use App\Entity\Inscription;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/student/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="student_course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        /**
         * @var $courses Course[]
         */
        $courses=$courseRepository->findAll();
        return $this->render('student/course/index.html.twig', ['listcourse'=>$courses]);
    }


    /**
     * @Route("/new", name="student_course_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('student_course_index');
        }

        return $this->render('student/course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_show", methods={"GET"})
     */
    public function show(Course $course): Response
    {
        return $this->render('student/course/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="student_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_course_index');
        }

        return $this->render('student/course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_course_index');
    }

    /**
     * @Route("/demandeinscription", name="demandeinscription", methods={"POST"})
     */
    public function demandeinscription(Request $request,CourseRepository $courseRepository): Response
    {
        $id = $request->request->get("cours"); //recuperer course dans le formulaire
        $course = $courseRepository->find($id);
        $manager = $this->getDoctrine()->getManager();
        if($course){
            $inscription = new Inscription();
            $inscription->setCourse($course);
            $inscription->setState(false);
            $inscription->setStudent($this->getUser());

            $manager->persist($inscription);//Enregistrer dans la BD
            $manager->flush();
            return $this->redirectToRoute('student_inscription_index');
        }
        else{
            return $this->redirectToRoute('course_index');
        }
    }
}
