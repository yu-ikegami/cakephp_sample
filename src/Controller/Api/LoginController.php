<?php

namespace App\Controller\Api;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
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
        ]);
    }

    public function add()
    {
        $user = $this->Auth->identify();

        if ($user) {
            $accessToken = $this->newAccessToken();

            $this->saveAccessToken($user['id'], $accessToken);

            // TODO レスポンスをちゃんと考える
            $user['access_token'] = $accessToken;

            $this->set([
                'user' => $user,
                '_serialize' => ['user'],
            ]);

            return;
        }

        throw new BadRequestException('Invalid email or password');
    }

    private function newAccessToken()
    {
        return Text::uuid();
    }

    private function saveAccessToken($userId, $plain)
    {
        $accessTokensTable = TableRegistry::get('AccessTokens');

        $accessToken = $accessTokensTable->newEntity();
        $accessToken->access_token = $plain;
        $accessToken->user_id = $userId;

        $accessTokensTable->save($accessToken);
    }
}
