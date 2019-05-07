<?php

namespace AdrianBav\Traffic;

class Traffic
{
    /**
     * An array of site visits.
     *
     * @var  array
     */
    protected $visits;

    /**
     * Instantiate a new Traffic instance.
     */
    public function __construct()
    {
        $this->visits = [];
    }

    /**
     * Record a visit.
     *
     * @param   mixed  $visit
     * @return  void
     */
    public function record($visit)
    {
        $this->visits[] = $visit;
    }

    /**
     * Return the number of visits.
     *
     * @return  integer
     */
    public function visits()
    {
        return count($this->visits);
    }
}
