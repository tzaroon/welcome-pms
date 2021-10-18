<?php

/*
 * This file is part of Laravel WuBook.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wubook\Wired;

use fXmlRpc\Client;
use fXmlRpc\Parser\NativeParser;
use fXmlRpc\Serializer\NativeSerializer;
use Illuminate\Contracts\Config\Repository;
use Wubook\Wired\Exceptions\WuBookException;
use Wubook\Wired\Api\WuBookAuth;
use Wubook\Wired\Api\WuBookAvailability;
use Wubook\Wired\Api\WuBookCancellationPolicies;
use Wubook\Wired\Api\WuBookChannelManager;
use Wubook\Wired\Api\WuBookCorporate;
use Wubook\Wired\Api\WuBookExtras;
use Wubook\Wired\Api\WuBookPrices;
use Wubook\Wired\Api\WuBookReservations;
use Wubook\Wired\Api\WuBookRestrictions;
use Wubook\Wired\Api\WuBookRooms;
use Wubook\Wired\Api\WuBookTransactions;

/**
 * This is the WuBook manager class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class WuBookManager
{

    /**
     * @var string
     */
    const ENDPOINT = 'https://wired.wubook.net/xrws/';

    /**
     * @var array
     */
    private $config;

    /**
     * @var Illuminate\Cache\Repository
     */
    private $cache;

    /**
     * Create a new WuBook Instance.
     *
     * @param Repository $config
     * @throws WuBookException
     */
    public function __construct(Repository $config)
    {
        // Setup credentials
        $this->config = array_only($config->get('wubook'), ['username', 'password', 'provider_key', 'lcode']);

        // Credentials check
        if (!array_key_exists('username', $this->config) || !array_key_exists('password', $this->config) || !array_key_exists('provider_key', $this->config) || !array_key_exists('lcode', $this->config)) {
            throw new WuBookException('Credentials are required!');
        }

        if (!array_key_exists('cache_token', $this->config)) {
            $this->config['cache_token'] = false;
        }

        // Utilities
        $this->cache = app()['cache'];
    }

    /**
     * Auth API
     *
     * @return Wubook\Wired\Api\WuBookAuth
     */
    public function auth()
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        return new WuBookAuth($this->config, $this->cache, $client);
    }

    /**
     * Availability API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookAvailability
     */
    public function availability($token = null, $lcode = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());
        
        if($lcode) {
            $this->config['lcode'] = $lcode;
        }

        return new WuBookAvailability($this->config, $this->cache, $client, $token);
    }

    /**
     * Cancellation polices API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookCancellationPolicies
     */
    public function cancellation_policies($token = null)
    {
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        return new WuBookCancellationPolicies($this->config, $this->cache, $client, $token);
    }

    /**
     * Channel manager API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookChannelManager
     */
    public function channel_manager($token = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        return new WuBookChannelManager($this->config, $this->cache, $client, $token);
    }

    /**
     * Corporate function API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookCorporate
     */
    public function corporate_functions($token = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        return new WuBookCorporate($this->config, $this->cache, $client, $token);
    }

    /**
     * Extra functions API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookExtras
     */
    public function extras($token = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        return new WuBookExtras($this->config, $this->cache, $client, $token);
    }

    /**
     * Prices API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookPrices
     */
    public function prices($token = null, $lcode = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        if($lcode) {
            $this->config['lcode'] = $lcode;
        }

        return new WuBookPrices($this->config, $this->cache, $client, $token);
    }

    /**
     * Reservations API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookPrices
     */
    public function reservations($token = null, $lcode = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());
        if($lcode) {
            $this->config['lcode'] = $lcode;
        }

        return new WuBookReservations($this->config, $this->cache, $client, $token);
    }

    /**
     * Restrictions API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookRestrictions
     */
    public function restrictions($token = null, $lcode = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        if($lcode) {
            $this->config['lcode'] = $lcode;
        }
        
        return new WuBookRestrictions($this->config, $this->cache, $client, $token);
    }

    /**
     * Rooms API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookRooms
     */
    public function rooms($token = null, $lcode = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());
        
        if($lcode) {
            $this->config['lcode'] = $lcode;
        }

        return new WuBookRooms($this->config, $this->cache, $client, $token);
    }

    /**
     * Transactions API
     *
     * @param string $token
     * @return Wubook\Wired\Api\WuBookTransactions
     */
    public function transactions($token = null)
    {
        // Setup client
        $client = new Client(self::ENDPOINT, null, new NativeParser(), new NativeSerializer());

        return new WuBookTransactions($this->config, $this->cache, $client, $token);
    }

    /**
     * Username getter.
     *
     * @return string
     */
    public function get_username()
    {
        return $this->username;
    }

    /**
     * Password getter.
     *
     * @return string
     */
    public function get_password()
    {
        return $this->password;
    }

    /**
     * Provider key getter.
     *
     * @return string
     */
    public function get_provider_key()
    {
        return $this->provider_key;
    }

    /**
     * Client getter.
     *
     * @return PhpXmlRpc\Client
     */
    public function get_client()
    {
        return $this->client;
    }
}