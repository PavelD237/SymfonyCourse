<?php

namespace App\Controller\Teacher;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("teacher/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="teacher_course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('teacher/course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/teacher", name="teacher_course_teachercourse", methods={"GET"})
     */
    public function teachercourse(CourseRepository $courseRepository): Response
    {
        $user = $this->getUser();
        return $this->render('teacher/course/index.html.twig', [
            'courses' => $courseRepository->findByCourseTeacher($user),
        ]);
    }

    /**
     * @Route("/new", name="teacher_course_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('teacher_course_index');
        }

        return $this->render('teacher/course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

 /*   /**
     * @Route("/{id}", name="teacher_course_show", methods={"GET"})
     /
    public function show(Course $course): Response
    {
        return $this->render('teacher/course/show.html.twig', [
            'course' => $course,
        ]);
    }*/

    /**
     * @Route("/{id}/edit", name="teacher_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('teacher_course_index');
        }

        return $this->render('teacher/course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="teacher_course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('teacher_course_index');
    }
}
