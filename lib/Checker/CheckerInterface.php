<?php
namespace Shantilab\BxEcho\Checker;

/**
 * Interface CheckerInterface
 * @package Shantilab\BxEcho\Checker
 */
interface CheckerInterface
{
    /**
     * @return mixed
     */
    public function check();

    /**
     * @param array $params
     * @return mixed
     */
    public function setParams(array $params = []);
}