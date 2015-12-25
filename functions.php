<?php

/**
 * @param null $var
 * @param null $name
 * @param array $options
 * @param CheckerInterface|null $checker
 * @param bool|true $showImmidiatly
 * @return BxEcho|void
 */
function bxe($var = null, $name = null, $options = [], Shantilab\BxEcho\Checker\CheckerInterface $checker = null, $showImmidiatly = true){
    $bxEcho = new BxEcho($var, $name, $options, $checker);

    if ($showImmidiatly)
        $bxEcho->show();

    return $bxEcho;
}
