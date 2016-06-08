<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 31.05.16
 * Time: 10:29
 */

namespace Repository;

use Doctrine\DBAL\Connection;
use Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository implements UserProviderInterface
{
    protected $db;

    protected $encoder;

    public function __construct(Connection $db, $encoder)
    {
        $this->db = $db;
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($email)
    {
        $email = strtolower($email);

        $sql = 'SELECT * FROM users WHERE status = \'active\' AND username = ?';
        $stmt = $this->db->executeQuery($sql, [$email]);
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException( sprintf( 'Username "%s" does not exist.', $email ) );
        }

        return $this->buildUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instance of "%s" are not supported'), get_class($user));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Entity\User';
    }

    protected function buildUser(array $data)
    {
        $user = new User();
        $user->setId($data['ID']);
        $user->setUsername($data['USERNAME']);
        $user->setSalt($data['SALT']);
        $user->setPassword($data['PASSWORD']);
        $user->setRoles(explode(',', $data['ROLES']));
        $createdAt = new \DateTime('@' . $data['CREATED_AT']);
        $user->setCreatedAt($createdAt);

        return $user;
    }

    public function save(User $user)
    {
        $data = [
            'username' => $user->getUsername(),
            'roles' => implode(',', $user->getRoles()),
        ];

        $createdAt = time();
        
        if ($user->getPlainPassword()) {
            $encoder = $this->encoder->getEncoder($user);
            $data['salt'] = md5($createdAt);
            $data['password'] = $encoder->encodePassword($user->getPlainPassword(), $data['salt']);
        }

        if ($user->getId()) {
            $this->db->update('users', $data, array('id' => $user->getId()));
        } else {
            // The user is new, note the creation timestamp.
            $data['created_at'] = $createdAt;

            $this->db->insert('users', $data);

            // Get the id of the newly created user and set it on the entity.
            $id = $this->db->lastInsertId();
            $user->setId($id);
        }
    }
}