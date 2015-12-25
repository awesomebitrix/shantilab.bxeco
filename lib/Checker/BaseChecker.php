<?php
namespace Shantilab\BxEcho\Checker;

use ReflectionClass;
use ReflectionMethod;
use Bitrix\Main\Application;
use Shantilab\BxEcho\Config;

/**
 * Class BaseChecker
 * @package Shantilab\BxEcho\Checker
 */
abstract class BaseChecker implements CheckerInterface
{
    /**
     * Bitrix
     * @var
     */
    protected $bxUser;

    /**
     * Conditions
     * @var
     */
    protected $userIds;
    /**
     * @var
     */
    protected $userGroups;
    /**
     * @var
     */
    protected $get;
    /**
     * @var
     */
    protected $post;
    /**
     * @var
     */
    protected $request;
    /**
     * @var
     */
    protected $cookies;
    /**
     * @var
     */
    protected $sessIds;
    /**
     * @var
     */
    protected $session;
    /**
     * @var
     */
    protected $url;
    /**
     * @var
     */
    protected $isPost;
    /**
     * @var
     */
    protected $isGet;
    /**
     * @var
     */
    protected $isAjax;
    /**
     * @var
     */
    protected $isAdmin;

    /**
     * @var Config
     */
    protected $config;

    /**
     * BaseChecker constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->config = new Config();
        $this->setDefaultParams();

        global $USER;
        if (!is_object($USER)){
            $USER = new \CUser();
        }

        $this->bxUser = $USER;

        if ($params)
            $this->setParams($params);
    }

    /**
     * @param $method
     * @param $args
     * @return $this
     */
    public function __call($method, $args) {
        if (strpos(toLower($method), 'is') === 0)
            $this->setParam($type = 'bool', $method, $args);
        else
            $this->setParam($type = 'array', $method, $args);

        return $this;
    }

    /**
     * @return bool
     */
    public function check()
    {
        $thisClass = new ReflectionClass(__CLASS__);
        $checkMethods = $thisClass->getMethods(ReflectionMethod::IS_PROTECTED);
        foreach($checkMethods as $method){
            if (strpos($method->name, '_check') === 0){

                $variable = lcfirst(str_replace('_check', '', $method->name));

                if (!isset($this->$variable)) continue;

                if (!$this->{$method->name}()){
                    return false;
                }

            }
        }

        return true;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params = [])
    {
        foreach($params as $key => $param){
            if (strpos($key, 'is') === 0)
                $this->$key = (bool)$param;
            else
                $this->$key = $this->toArray($param);
        }
    }

    /**
     * @return bool
     */
    protected  function _checkUserIds(){
        $userId = $this->bxUser->getId();
        if (in_array($userId, $this->userIds))
            return true;

        return false;
    }

    /**
     * @return bool
     */
    protected  function _checkUserGroups(){
        $userGroups = $this->bxUser->GetUserGroupArray();

        if (array_intersect($userGroups, $this->userGroups))
            return true;

        return false;
    }

    /**
     * @return bool
     */
    protected  function _checkGet(){
        foreach($this->get as $index => $val){
            if ($this->getBxRequest()->getQuery($index) != $val)
                return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected  function _checkPost(){
        foreach($this->post as $index => $val){
            if ($this->getBxRequest()->getPost($index) != $val)
                return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected  function _checkRequest(){
        foreach($this->request as $index => $val){
            if ($this->getBxRequest()->get($index) != $val)
                return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected  function _checkCookies(){
        foreach($this->cookies as $index => $val){
            if ($this->getBxRequest()->getCookie($index) != $val)
                return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected  function _checkSessIds(){
        foreach($this->sessIds as $val){
            if (bitrix_sessid() != $val) return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected  function _checkSession(){
        foreach($this->session as $index => $val){
            if ($_SESSION[$index] != $val) return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected  function _checkUrl(){
        $url = $this->getBxRequest()->getRequestUri();

        if ($this->url == $url)
            return true;

        return false;
    }

    /**
     * @return bool
     */
    protected  function _checkIsPost(){
        if ($this->getBxRequest()->isPost() == $this->isPost)
            return true;

        return false;
    }

    /**
     * @return bool
     */
    protected  function _checkIsGet(){
        $curMethod = ($this->getBxRequest()->getRequestMethod() == 'GET') ? true : false;
        if ($curMethod == $this->isGet)
            return true;

        return false;
    }

    /**
     * @return bool
     */
    protected  function _checkIsAjax(){
        $server = $this->getBxContext()->getServer();

        $ajaxHeader = $server->get('HTTP_X_REQUESTED_WITH');

        if (!isset($ajaxHeader) || empty($ajaxHeader) || strtolower($ajaxHeader) != 'xmlhttprequest') return false;

        return true;
    }

    /**
     * @return bool
     */
    protected  function _checkIsAdmin(){
        if ($this->bxUser->isAdmin() === $this->isAdmin)
            return true;

        return false;
    }

    /**
     * @param $var
     * @return array
     */
    protected function toArray($var){
        if (!is_array($var) && $var)
            return [$var];

        return $var;
    }

    /**
     * @param $type
     * @param $method
     * @param $args
     */
    protected function setParam($type, $method, $args){
        $variable = lcfirst($method);
        if ($type == 'bool')
            $this->setParams([$variable => true]);
        else{
            $this->setParams([$variable => $args[0]]);
        }
    }

    /**
     * @return \Bitrix\Main\Context
     */
    protected function getBxContext(){
        return Application::getInstance()->getContext();
    }

    /**
     * @return \Bitrix\Main\HttpRequest
     */
    protected function getBxRequest(){
        return $this->getBxContext()->getRequest();
    }

    /**
     *
     */
    protected function setDefaultParams(){
        $this->setParams($this->config->getConfig('Checker'));
    }

}