<?php
class Task
{
    protected $generator;

    protected $run = false;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function run()
    {
        if ($this->run) {
            $this->generator->next();
        } else {
            $this->generator->current();
        }

        $this->run = true;
    }

    public function finished()
    {
        return !$this->generator->valid();
    }
}