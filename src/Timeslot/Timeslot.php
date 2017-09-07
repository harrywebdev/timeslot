<?php

namespace Timeslot;

use DateTime;
use ArrayIterator;
use Carbon\Carbon;

class Timeslot implements TimeslotInterface
{
    protected $start;
    protected $hours;
    protected $minutes;
    protected $end;

    /**
     * The Timeslot constructor accepts a DateTime instance, turns it into a
     * Carbon instance and sets start and end time according to the duration
     * provided (default = 1 hour, 0 minutes).
     * If no arguments are passed, it creates a 1-hour timeslot wrapping the
     * current date and time.
     *
     * @param DateTime    $start
     * @param int         $hours
     */
    public function __construct(DateTime $start = null, int $hours = 1, int $minutes = 0)
    {
        $start = $start ?: Carbon::now();

        if (! $start instanceof Carbon) {
            $start = Carbon::instance($start);
        }

        $this->start = $start;
        $this->hours = $hours;
        $this->minutes = $minutes;

        $this->setStart();
        $this->setEnd();
    }

    /**
     * Alternative Timeslot constructor that allows fluent syntax.
     *
     * @param  Carbon\Carbon $start
     * @param  integer $hours
     * @param  integer $minutes
     *
     * @return App\Timeslot
     */
    public static function create($start, $hours = 1, $minutes = 0)
    {
        return new static($start, $hours, $minutes);
    }

    /**
     * Set the start date & time for the timeslot.
     */
    protected function setStart()
    {
        $this->start->second(0);
    }

    /**
     * Set the end date & time for the current instance.
     * A timeslot ends always one second before the
     * duration selected.
     */
    protected function setEnd()
    {
        $this->end = (clone $this->start)
            ->addHours($this->hours)
            ->addMinutes($this->minutes)
            ->subSecond();
    }

    /**
     * Round up start and end time to the start and end of the current hour.
     *
     * @return this
     */
    public function round()
    {
        $this->start->minute(0);
        $this->setEnd();

        return $this;
    }

    /**
     * Add a specific number of $hours to the timeslot's start and end date & time.
     *
     * @param int $hours
     */
    public function addHour(int $hours = 1)
    {
        // TODO: there is a syntax chaos here.
        $this->start = clone ($this->start)->addHour($hours);
        $this->end = clone ($this->end)->addHour($hours);

        return $this;
    }

    /**
     * Get the start date & time.
     *
     * @return Carbon\Carbon
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * Get the end date / time.
     *
     * @return Carbon\Carbon
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * Get an array of start and end date / time.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'start' => $this->start(),
            'end' => $this->end()
        ];
    }

    /**
     * Create a new Timeslot instance based on the current date / time.
     * It is still possible to specify a duration in hours.
     *
     * @param  integer $hours
     *
     * @return App\Timeslot
     */
    public static function now($hours = 1, $minutes = 0)
    {
        return new static(Carbon::now(), $hours, $minutes);
    }
}
