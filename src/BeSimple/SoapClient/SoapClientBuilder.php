<?php

/*
 * This file is part of the BeSimpleSoapBundle.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BeSimple\SoapClient;

use BeSimple\SoapCommon\AbstractSoapBuilder;

/**
 * Fluent interface builder for SoapClient instance.
 *
 * @author Francis Besset <francis.besset@gmail.com>
 * @author Christian Kerl <christian-kerl@web.de>
 */
class SoapClientBuilder extends AbstractSoapBuilder
{
    /**
     * Authentication options.
     *
     * @var array(string=>mixed)
     */
    protected $soapOptionAuthentication = array();

    /**
     * Create new instance with default options.
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public static function createWithDefaults()
    {
        return parent::createWithDefaults()
            ->withUserAgent('BeSimpleSoap');
    }

    /**
     * Finally returns a SoapClient instance.
     *
     * @return \BeSimple\SoapClient\SoapClient
     */
    public function build()
    {
        $this->validateOptions();

        return new SoapClient($this->wsdl, $this->getSoapOptions());
    }

    /**
     * Get final array of SOAP options.
     *
     * @return array(string=>mixed)
     */
    public function getSoapOptions()
    {
        return parent::getSoapOptions() + $this->soapOptionAuthentication;
    }

    /**
     * Configure option 'trace'.
     *
     * @param boolean $trace Enable/Disable
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withTrace($trace = true)
    {
        $this->soapOptions['trace'] = $trace;

        return $this;
    }

    /**
     * Configure option 'exceptions'.
     *
     * @param boolean $exceptions Enable/Disable
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withExceptions($exceptions = true)
    {
        $this->soapOptions['exceptions'] = $exceptions;

        return $this;
    }

    /**
     * Configure option 'user_agent'.
     *
     * @param string $userAgent User agent string
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withUserAgent($userAgent)
    {
        $this->soapOptions['user_agent'] = $userAgent;

        return $this;
    }

    /**
     * Enable gzip compression.
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withCompressionGzip()
    {
        $this->soapOptions['compression'] = SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP;

        return $this;
    }

    /**
     * Enable deflate compression.
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withCompressionDeflate()
    {
        $this->soapOptions['compression'] = SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_DEFLATE;

        return $this;
    }

    /**
     * Configure basic authentication
     *
     * @param string $username Username
     * @param string $password Password
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withBasicAuthentication($username, $password)
    {
        $this->soapOptionAuthentication = array(
            'authentication' => SOAP_AUTHENTICATION_BASIC,
            'login'          => $username,
            'password'       => $password,
        );

        return $this;
    }

    /**
     * Configure digest authentication.
     *
     * @param string $certificate Certificate
     * @param string $passphrase  Passphrase
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withDigestAuthentication($certificate, $passphrase = null)
    {
        $this->soapOptionAuthentication = array(
            'authentication' => SOAP_AUTHENTICATION_DIGEST,
            'local_cert'     => $certificate,
        );

        if ($passphrase) {
            $this->soapOptionAuthentication['passphrase'] = $passphrase;
        }

        return $this;
    }

    /**
     * Configure proxy.
     *
     * @param string $host     Host
     * @param int    $port     Port
     * @param string $username Username
     * @param string $password Password
     *
     * @return \BeSimple\SoapClient\SoapClientBuilder
     */
    public function withProxy($host, $port, $username = null, $password = null)
    {
        $this->soapOptions['proxy_host'] = $host;
        $this->soapOptions['proxy_port'] = $port;

        if ($username) {
            $this->soapOptions['proxy_login']    = $username;
            $this->soapOptions['proxy_password'] = $password;
        }

        return $this;
    }

    /**
     * Validate options.
     */
    protected function validateOptions()
    {
        $this->validateWsdl();
    }
}