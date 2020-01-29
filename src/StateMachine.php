<?php
namespace States;

use Exception;

class StateMachine implements StateMachineInterface
{
    protected $states = [];
    protected $transitions = [];
    protected $object = null;
    protected $field = 'state';

    /**
     * StateMachine constructor.
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {

    }

    /**
     * @param $state
     * @return StateMachine
     */
    public function setState($state) : StateMachine
    {
        if (isset($this->states[$state])) {
            return null;
        }

        $this->states[$state] = $state;

        return $this;
    }


    public function setTransition(string $transitionName, $from, $to)
    {
        if (!(gettype($from) == 'string' || gettype($from) == 'array')) {
            return null;
            //throw new Exception('From or To arguments mus be string or array');
        }

        if (!(gettype($to) == 'string' || gettype($to) == 'array')) {
            return null;
            //throw new Exception('From or To arguments mus be string or array');
        }

        if (gettype($from) == 'string') {
            $from = [$from];
        }

        if (gettype($to) == 'string') {
            $to = [$to];
        }

        if (isset($this->transition[$transitionName])) {
            return null;
            //throw new \Exception('Transition already registered: '.$transitionName);
        }

        foreach ($from as $item) {
            if (!isset($this->states[$item])) {
                return 'The state '. $item . ' is not registered.';
                // new \Exception('The state '. $item . ' is not registered.');
            }
        }

        foreach ($to as $item) {
            if (!isset($this->states[$item])) {
                return 'The state '. $item . ' is not registered.';
                //throw new \Exception('The state '. $item . ' is not registered.');
            }
        }

        $this->transitions[$transitionName] = [
            "from" => $from,
            "to" => $to
        ];

        return $this;
    }


    /**
     * @return array
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @return array
     */
    public function getTransitions()
    {
        return $this->transitions;
    }

    public function can($transition)
    {
        if (!isset($this->transitions[$transition])) {
            return false;
            //throw new \Exception('The transition '.$transition.' is not registered.');
        }

        if ($this->object !== null) {
            $object = $this->object;
        }

        $state = $this->object->{$this->field} ?? false;

        if (!$state) {
            return false;
        }

        if (!in_array($state, $this->transitions[$transition]['from'], true)) {
            return false;
        }

        return true;
    }

    /**
     * @param null $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }


}
