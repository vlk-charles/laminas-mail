<?php

namespace Laminas\Mail\Protocol\Pop3\Xoauth2;

use Laminas\Mail\Protocol\Exception\RuntimeException;
use Laminas\Mail\Protocol\Xoauth2\Xoauth2;

class Microsoft extends \Laminas\Mail\Protocol\Pop3
{
    protected const AUTH_INITIALIZE_REQUEST = 'AUTH XOAUTH2';
    protected const AUTH_RESPONSE_INITIALIZED_OK = '+';

    /**
     * @param string $username the target mailbox to access
     * @param string $password OAUTH2 accessToken
     * @param bool $tryApop obsolete parameter not used here
     * @return void
     */
    public function login($username, $password, $tryApop = true): void
    {
        $this->sendRequest(self::AUTH_INITIALIZE_REQUEST);

        $response = $this->readRemoteResponse();

        if ($response->status() != self::AUTH_RESPONSE_INITIALIZED_OK) {
            throw new RuntimeException($response->message());
        }

        $this->request(Xoauth2::encodeXoauth2Sasl($username, $password));
    }
}
