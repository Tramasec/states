<?php
namespace States;

class StateMachine implements StateMachineInterface
{
    protected $states = [];
    protected $transitions = [];
    protected $object = null;

    /**
     * StateMachine constructor.
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {

    }

    public function setState($state)
    {
        if (isset($this->states[$state])) {
            throw new \Exception('State already registered: '.$state);
        }

        $this->states[$state] = $state;
    }


    public function setTransition(string $transitionName, $from, $to)
    {
        if (!(gettype($from) == 'string' || gettype($from) == 'array')) {
            throw new \Exception('From or To arguments mus be string or array');
        }

        if (gettype($from) == 'string') {
            $from = [$from];
        }

        if (gettype($to) == 'string') {
            $to = [$to];
        }

        if (isset($this->transition[$transitionName])) {
            throw new \Exception('Transition already registered: '.$transitionName);
        }

        foreach ($from as $item) {
            if (!isset($this->states[$item])) {
                throw new \Exception('The state '. $item . ' is not registered.');
            }
        }

        foreach ($to as $item) {
            if (!isset($this->states[$item])) {
                throw new \Exception('The state '. $item . ' is not registered.');
            }
        }

        $this->transitions[$transitionName] = [
            "from" => $from,
            "to" => $to
        ];
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
            throw new \Exception('The transition '.$transition.' is not registered.');
        }
        if ($this->object !== null) {
            $object = $this->object;
        }

        if (!in_array($object->state, $this->transitions[$transition]['from'], true)) {
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
}
