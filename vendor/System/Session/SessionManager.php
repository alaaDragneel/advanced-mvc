<?php

namespace System\Session;

abstract class SessionManager extends \SessionHandler
{
    /**
     * Session Start Time Key
     */
    const SESSION_START_TIME_KEY = 'SESSION_START_TIME';

    /**
     * $sessionName
     *
     * @var string
     */
    protected $sessionName = 'ADVANCED_MVC';
    
    /**
     * $sessionMaxLifeTime
     *
     * @var integer
     */
    protected $sessionMaxLifeTime = 0;
    
    /**
     * $sessionSSL
     *
     * @var boolean
     */
    protected $sessionSSL = true;
    
    /**
     * $sessionHTTPOnly
     *
     * @var boolean
     */
    protected $sessionHTTPOnly = true;

    /**
     * $sessionPath
     *
     * @var string
     */
    protected $sessionPath = '/';

    /**
     * $sessionDomain
     *
     * @var string
     */
    protected $sessionDomain = '127.0.0.1'; // EX: .domain.com

    /**
     * $sessionSavePath
     *
     * @var string
     */
    protected $sessionSavePath = 'SESSION_SAVE_PATH';

    /**
     * $sessionCipherAlgo
     *
     * @var string
     */
    protected $sessionCipherAlgo = 'AES-128-CBC';

    /**
     * $sessionCipherKey
     *
     * @var string
     */
    protected $sessionCipherKey = 'CREPT0K3Y@2018'; // EX: CREPT0K3Y@2018

    /**
     * $ttl
     *
     * @var integer
     */
    protected $ttl = 30;

    /**
     * Session Start Time
     *
     * @var integer
     */
    protected $sessionStartTime = 0;

    /**
     * Cipher Key For Finger Print
     *
     * @var mixed
     */
    protected $cipherKey;
    
    /**
     * Finger Print For Preventing Hijacking and Fixation
     *
     * @var mixed
     */
    protected $fingerPrint;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->sessionSSL = isset($_SERVER['HTTPS']) ? true : false;
        $this->sessionDomain = str_replace('www.', '', $_SERVER['SERVER_NAME']);

        // initialize some directive
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_trans_sid', 0);
        ini_set('session.save_handler', 'files');

        // Define the basic session information

        session_name($this->sessionName);

        session_save_path($this->sessionSavePath);

        session_set_cookie_params($this->sessionMaxLifeTime, $this->sessionPath, $this->sessionDomain, $this->sessionSSL, $this->sessionHTTPOnly);
    }

    /**
     * Specify How PHP Start The Session
     *
     * @return void
     */
    protected function startSessionTime()
    {
        $this->setSessionStartTime();
        $this->checkSessionExpiration();
    }

    /**
     * Set Session Start Time To Prevent Hijacking and Fixation
     *
     * @return void
     */
    protected function setSessionStartTime()
    {
        if ($this->has(static::SESSION_START_TIME_KEY)) {
            $this->sessionStartTime = $this->get(static::SESSION_START_TIME_KEY);
        } else {
            $time = time();
            $this->set(static::SESSION_START_TIME_KEY, $time);
            $this->sessionStartTime = $time;
        }
    }

    /**
     * Check Session Expiration Time To Prevent Hijacking and Fixation
     *
     * @return void
     */
    protected function checkSessionExpiration()
    {
        if ((time() - $this->sessionStartTime) > ($this->ttl * 60)) {
            $this->generateNewSession();
            $this->generateFingerPrint();
        }
    }

    /**
     * Generate New Session and Delete The Old one To Prevent Hijacking and Fixation
     *
     * @return void
     */
    protected function generateNewSession($deleteOldSession = true)
    {
        $this->remove(static::SESSION_START_TIME_KEY);
       
        return session_regenerate_id($deleteOldSession);
    }

    /**
     * Generate New Finger Print To Prevent Hijacking and Fixation
     *
     * @return void
     */
    protected function generateFingerPrint()
    {
        // generate finger print to prevent session hijacking and fixation
        $userAgentId = $_SERVER['HTTP_USER_AGENT'];
        $this->cipherKey = openssl_random_pseudo_bytes(16);
        $sessionId = session_id();
        $this->fingerPrint = md5($userAgentId . $this->cipherKey . $sessionId);
    }

    /**
     * Determine If The Finger Print Expire ot not To Prevent Hijacking and Fixation
     *
     * @return boolean
     */
    protected function isFingerPrintValid()
    {
        if (! isset($this->fingerPrint)) $this->generateFingerPrint();

        $fingerPrint = md5($_SERVER['HTTP_USER_AGENT'] . $this->cipherKey . session_id());

        if ($fingerPrint === $this->fingerPrint) return true;

        return false;
    }

    /**
     * Close The Session
     * Unset All Session Variables
     * Destroy The Session Completely
     * Close The Session Write
     * Delete The Old Session File
     * @return void
     */
    protected function destroySession()
    {
        session_unset();
        session_destroy(); 
        session_write_close(); 
        setcookie($this->sessionName, '', 0, $this->sessionPath, $this->sessionDomain, $this->sessionSSL, $this->sessionHTTPOnly);
        // Suspend the error to avoid [ "session_regenerate_id cannot generate new session because session is not active" ]
        @session_regenerate_id(true); // if not needed remove it but old session file will not deleted
    }

    /**
     * Start Session
     * However if the Session Finger Print Not Valid It Will be Destroyed
     * 
     * @return void
     */
    abstract public function start();

    /**
     * Set New Value To Session
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    abstract public function set($key, $value);

    /**
     * Get Value From Session By The Given Key
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    abstract public function get($key, $default = null);

    /**
     * Determine if The Session Have The Given Key
     * 
     * @param string $key
     * @return bool
     */
    abstract public function has($key);

    /**
     * Remove The Given Key From The Session
     * 
     * @param string $key
     * @return void
     */
    abstract public function remove($key);

    /**
     * Get Value From Session By The Given Key And Remove It
     * 
     * @param string $key
     * @return mixed
     */
    abstract public function pull($key);

    /**
     * Get All Session Data
     * 
     * @return array
     */
    abstract public function all();
}