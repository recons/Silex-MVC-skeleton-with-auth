<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 31.05.16
 * Time: 0:13
 */

namespace Controller;

use Entity\User;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class UserController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $userController = $app['controllers_factory'];

        $userController->get("/login", [$this, 'login'])->bind('login');
        $userController->get("/logout", [$this, 'logout'])->bind('logout');

        return $userController;
    }

    public function login(Request $request, Application $app)
    {
        $form = $app['form.factory']->createBuilder(FormType::class)
            ->add('username', TextType::class, [
                'label' => false,
                'data' => $app['session']->get('_security.last_username'),
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email']
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Пароль']
            ])
            ->getForm();

        return $app['twig']->render('user/login.html.twig', [
            'form' => $form->createView(),
            'error' => $app['security.last_error']($request),
        ]);
    }
    
    private function test(Application $app) {
        $user = new User();
        $user->setUsername('test@test.com');
        $user->setPlainPassword('password');
        $user->setRoles(['ROLE_ADMIN']);

        $repo = $app['repository.user'];
        $repo->save($user);
    }

    public function logout(Application $app)
    {
        $app['session']->clear();

        return $app->redirect($app['url_generator']->generate('login'));
    }
}
