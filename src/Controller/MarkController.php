<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 02.06.16
 * Time: 19:03
 */

namespace Controller;

use Entity\Human;
use Entity\Mark;
use Entity\Subject;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class MarkController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->get("/", [$this, 'index'])->bind('marks');
        $controller->match("/add", [$this, 'add'])->bind('marks_add');
        $controller->match("/{id}/edit", [$this, 'edit'])->bind('marks_edit');
        $controller->get("/{id}/delete", [$this, 'delete'])->bind('marks_delete');

        return $controller;
    }

    public function index(Application $app)
    {
        $marks = $app['repository.mark']->findAll();

        return $app['twig']->render('mark/index.html.twig', [
            'marks' => $marks,
        ]);
    }

    public function add(Request $request, Application $app)
    {
        $mark = new Mark();
        $form = $this->createForm($app, $mark);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.mark']->save($mark);

            return $app->redirect($app['url_generator']->generate('marks'));
        }

        return $app['twig']->render('mark/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, Application $app, $id)
    {
        $mark = $app['repository.mark']->find($id);
        if (!$mark) {
            $app->abort(404, 'The requested subject was not found.');
        }

        $form = $this->createForm($app, $mark);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.mark']->save($mark);

            return $app->redirect($app['url_generator']->generate('marks'));
        }

        return $app['twig']->render('mark/edit.html.twig', [
            'form' => $form->createView(),
            'mark' => $mark
        ]);
    }

    public function delete(Application $app, $id)
    {
        $app['repository.mark']->delete($id);

        return $app->redirect($app['url_generator']->generate('marks'));
    }

    protected function createForm(Application $app, Mark $mark)
    {
        $form = $app['form.factory']->createBuilder(FormType::class, $mark)
            ->add('student', ChoiceType::class, [
                'choices' => $app['repository.human']->findAllStudents(),
                'choice_label' => function ($human, $key, $index) {
                    /** @var Human $human */
                    return $human->getFullname();
                },
                'label' => 'Студент',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12'
                ]
            ])
            ->add('subject', ChoiceType::class, [
                'choices' => $app['repository.subject']->findAll(),
                'choice_label' => function ($subject, $key, $index) {
                    /** @var Subject $subject */
                    return $subject->getName();
                },
                'label' => 'Предмет',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12'
                ]
            ])
            ->add('teacher', ChoiceType::class, [
                'choices' => $app['repository.human']->findAllTeachers(),
                'choice_label' => function ($human, $key, $index) {
                    /** @var Human $human */
                    return $human->getFullname();
                },
                'label' => 'Преподаватель',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12'
                ]
            ])
            ->add('value', TextType::class, [
                'label' => 'Оценка',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12',
                    'placeholder' => 'Оценка'
                ]
            ])
            ->getForm();

        return $form;
    }
}