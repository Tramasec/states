<?php
declare(strict_types=1);

namespace States;

interface StateMachineInterface
{
    /**
     * @param string $transition
     * @param $from
     * @param $to
     * @return mixed
     */
    public function setTransition(string $transition, $from, $to);
}
