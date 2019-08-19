<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Auth;

use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;

class AccessTokenAuthenticate extends BaseAuthenticate
{

    /**
     * Authenticate a user using HTTP auth. Will use the configured User model and attempt a
     * login using HTTP auth.
     *
     * @param \Cake\Network\Request $request The request to authenticate with.
     * @param \Cake\Network\Response $response The response to add headers to.
     * @return mixed Either false on failure, or an array of user data on success.
     */
    public function authenticate(Request $request, Response $response)
    {
        return $this->getUser($request);
    }

    /**
     * Get a user based on information in the request. Used by cookie-less auth for stateless clients.
     *
     * @param \Cake\Network\Request $request Request object.
     * @return mixed Either false or an array of user information
     */
    public function getUser(Request $request)
    {
        $authorization = $request->header('AUTHORIZATION');

        $exploded = explode(' ', $authorization);
        if (count($exploded) !== 2) {
            return false;
        }

        $type = $exploded[0];
        $credential = $exploded[1];

        if (strtoupper($type) !== 'BEARER' && $credential === '') {
            return false;
        }

        return $this->_findUserByAccessToekn($credential);
    }

    private function _findUserByAccessToekn($credential)
    {
        $result = $this->_queryByAccessToken($credential)->first();

        if (empty($result)) {
            return false;
        }

        $result->unsetProperty($this->_config['fields']['password']);

        return $result->toArray();
    }

    private function _queryByAccessToken($credential)
    {
        $table = TableRegistry::get($this->_config['userModel']);

        $config = $this->_config;
        $query = $table->find('all')->matching('AccessTokens', function ($q) use ($credential) {
            return $q->where(['AccessTokens.access_token' => $credential]);
        });

        if ($config['contain']) {
            $query = $query->contain($config['contain']);
        }
        return $query;
    }

    public function unauthenticated(Request $request, Response $response)
    {
        throw new UnauthorizedException();
    }
}
