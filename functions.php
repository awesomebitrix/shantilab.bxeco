<?php

/**
 * @param null $var
 * @param null $name
 * @param array $options
 * @param CheckerInterface|null $checker
 * @param bool|true $showImmidiatly
 * @return BxEcho|void
 */
function bxe($var = null, $name = null, $options = [], CheckerInterface $checker = null, $showImmidiatly = true){

    if ($showImmidiatly)
        return ((new BxEcho($var, $name, $options, $checker))->show());

    return (new BxEcho($var, $name, $options, $checker));
}