<?php

namespace AdrianBav\Traffic;

class Traffic
{
    protected $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function record($visit)
    {
        $this->data[] = $visit;
    }

    public function visits()
    {
        return count($this->data);
    }
}
