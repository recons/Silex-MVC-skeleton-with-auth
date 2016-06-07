<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 20:04
 */

namespace Controller;

use Entity\Group;
use Entity\Human;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class HumanController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->get("/", [$this, 'index'])->bind('people');
        $controller->match("/add", [$this, 'add'])->bind('people_add');
        $controller->match("/{id}/edit", [$this, 'edit'])->bind('people_edit');
        $controller->get("/{id}/delete", [$this, 'delete'])->bind('people_delete');

        return $controller;
    }

    public function index(Application $app)
    {
        $people = $app['repository.human']->findAll();

        return $app['twig']->render('human/index.html.twig', [
            'people' => $people,
        ]);
    }

    public function add(Request $request, Application $app)
    {
        $human = new Human();
        $form = $this->createForm($app, $human);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.human']->save($human);

            return $app->redirect($app['url_generator']->generate('people'));
        }

        return $app['twig']->render('human/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, Application $app, $id)
    {
        $human = $app['repository.human']->find($id);
        if (!$human) {
            $app->abort(404, 'The requested subject was not found.');
        }

        $form = $this->createForm($app, $human);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.human']->save($human);

            return $app->redirect($app['url_generator']->generate('people'));
        }

        return $app['twig']->render('human/edit.html.twig', [
            'form' => $form->createView(),
            'human' => $human
        ]);
    }

    public function delete(Application $app, $id)
    {
        $app['repository.human']->delete($id);

        return $app->redirect($app['url_generator']->generate('people'));
    }

    protected function createForm(Application $app, Human $human)
    {
        $form = $app['form.factory']->createBuilder(FormType::class, $human)
            ->add('lastname', TextType::class, [
                'label' => 'Фамилия',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12',
                    'placeholder' => 'Фамилия'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Имя',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12',
                    'placeholder' => 'Имя'
                ]
            ])
            ->add('pathername', TextType::class, [
                'label' => 'Отчество',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12',
                    'placeholder' => 'Отчество'
                ]
            ])
            ->add('group', ChoiceType::class, [
                'choices' => $app['repository.group']->findAll(),
                'choice_label' => function($group, $key, $index) {
                    /** @var Group $group */
                    return $group->getName();
                },
                'label' => 'Группа',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Студент' => 's',
                    'Преподаватель' => 'p'
                ],
                'expanded' => true,
                'label' => 'Тип',
                'attr' => [
                    'class' => 'form-control col-md-7 col-xs-12'
                ]
            ])
            ->getForm();

        return $form;
    }
}