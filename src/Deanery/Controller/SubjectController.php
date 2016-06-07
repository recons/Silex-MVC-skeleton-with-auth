<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 19:28
 */

namespace Controller;

use Entity\Subject;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class SubjectController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->get("/", [$this, 'index'])->bind('subjects');
        $controller->match("/add", [$this, 'add'])->bind('subjects_add');
        $controller->match("/{id}/edit", [$this, 'edit'])->bind('subjects_edit');
        $controller->get("/{id}/delete", [$this, 'delete'])->bind('subjects_delete');

        return $controller;
    }

    public function index(Application $app)
    {
        $subjects = $app['repository.subject']->findAll();

        return $app['twig']->render('subject/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }

    public function add(Request $request, Application $app)
    {
        $subject = new Subject();
        $form = $this->createForm($app, $subject);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.subject']->save($subject);

            return $app->redirect($app['url_generator']->generate('subjects'));
        }

        return $app['twig']->render('subject/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, Application $app, $id)
    {
        $subject = $app['repository.subject']->find($id);
        if (!$subject) {
            $app->abort(404, 'The requested subject was not found.');
        }

        $form = $this->createForm($app, $subject);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.subject']->save($subject);

            return $app->redirect($app['url_generator']->generate('subjects'));
        }

        return $app['twig']->render('subject/add.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject
        ]);
    }

    public function delete(Application $app, $id)
    {
        $app['repository.subject']->delete($id);

        return $app->redirect($app['url_generator']->generate('subjects'));
    }

    protected function createForm(Application $app, Subject $subject)
    {
        $form = $app['form.factory']->createBuilder(FormType::class, $subject)
            ->add('name', TextType::class, [
                'label' => 'Наименование',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12',
                    'placeholder' => 'наименование'
                ]
            ])
            ->getForm();

        return $form;
    }
}