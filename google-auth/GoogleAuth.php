<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2023-03-21
 * Time: 9:48 AM
 */

namespace Maatify\GoogleAuth;

use Exception;
use Vectorface\GoogleAuthenticator;

class GoogleAuth
{
    private static self $instance;
    private GoogleAuthenticator $ga;

    public static function obj(): self
    {
        if(empty(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->ga = (new GoogleAuthenticator());
    }

    /**
     * @throws Exception
     */
    public function GenerateSecret(): string
    {
        return $this->ga->createSecret();
    }

    public function CheckCode(string $secret, string $code): bool
    {
        return $this->ga->verifyCode($secret, $code, 2);
    }

    /**
     * @throws Exception
     */
    public function GetCode(string $secret): string
    {
        return $this->ga->getCode($secret);
    }

    /**
     * @throws Exception
     */
    public function GetImg(string $username, string $secret, string $issuer): string
    {
        return $this->ga->getQRCodeUrl($username, $secret, $issuer);
    }
}