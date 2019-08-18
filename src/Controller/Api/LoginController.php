<?php

namespace App\Controller\Api;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Exception\BadRequestException;
use Cake\Utility\Text;

class LoginController extends ApiController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => '/api/login',
            'unauthorizedRedirect' => false,
        ]);
    }

    public function add()
    {
        $user = $this->Auth->identify();

        if ($user) {
            $user['api_key_plain'] = Text::uuid();

            // TODO トークンを Bcrypt で暗号化したものをDBに保存
            $hasher = new DefaultPasswordHasher();
            $user['api_key'] = $hasher->hash($user['api_key_plain']);

            $this->set([
                'user' => $user,
                '_serialize' => ['user'],
            ]);

            return;
        }

        throw new BadRequestException('Invalid email or password');
    }
}
