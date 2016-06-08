<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 01.06.16
 * Time: 16:36
 */

namespace Controller;

use Entity\Group;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class GroupController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->get("/", [$this, 'index'])->bind('groups');
        $controller->match("/add", [$this, 'add'])->bind('groups_add');
        $controller->match("/{id}/edit", [$this, 'edit'])->bind('groups_edit');
        $controller->get("/{id}/delete", [$this, 'delete'])->bind('groups_delete');

        return $controller;
    }

    public function index(Application $app)
    {
        $groups = $app['repository.group']->findAll();

        return $app['twig']->render('group/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    public function add(Request $request, Application $app)
    {
        $group = new Group();
        $form = $this->createForm($app, $group);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.group']->save($group);
            
            return $app->redirect($app['url_generator']->generate('groups'));
        }

        return $app['twig']->render('group/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function edit(Request $request, Application $app, $id)
    {
        $group = $app['repository.group']->find($id);
        if (!$group) {
            $app->abort(404, 'The requested group was not found.');
        }

        $form = $this->createForm($app, $group, ['name' => $group->getName()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $app['repository.group']->save($group);

            return $app->redirect($app['url_generator']->generate('groups'));
        }

        return $app['twig']->render('group/add.html.twig', [
            'form' => $form->createView(),
            'group' => $group
        ]);
    }

    public function delete(Application $app, $id)
    {
        $app['repository.group']->delete($id);

        return $app->redirect($app['url_generator']->generate('groups'));
    }

    protected function createForm(Application $app, Group $group)
    {
        $form = $app['form.factory']->createBuilder(FormType::class, $group)
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

