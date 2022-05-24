<?php

class Replace
{
    public string $type;
    public string $name;

    /**
     * Replace constructor.
     * @param string $type
     * @param string $name
     */
    public function __construct(string $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}
