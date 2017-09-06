<?php

namespace Timeslot;

use DateTime;
use ArrayIterator;
use Carbon\Carbon;

class Timeslot implements TimeslotInterface
{
    protected $start;
    protected $hours;
    protected $end;

    /**
     * The Timeslot constructor accepts a DateTime instance, turns it into a
     * Carbon instance and sets start and end time according to the duration
     * provided (default = 1 hour).
     *
     * @param DateTime    $start
     * @param int         $hours
     */
    public function __construct(DateTime $start, int $hours = 1)
    {
        if (! $start instanceof Carbon) {
            $start = Carbon::instance($start);
        }

        $this->setStart($start);
        $this->hours = $hours;
        $this->setEnd($hours);
    }

    /**
     * Set the start date / time for the timeslot.
     *
     * @param Carbon\Carbon $start
     */
    protected function setStart($start)
    {
        $this->start = $start->minute(0)->second(0);
    }

    /**
     * Set the end date / time for the timeslot.
     *
     * @param Carbon\Carbon $end
     */
    protected function setEnd($hours)
    {
        // If the interval is 1 hour, set it to 0 hours, 59 mins and 59 secs
        $hours -= 1;

        $this->end = clone $this->start;
        $this->end->addHours($hours)->minute(59)->second(59);
    }

    /**
     * Add a specific number of $hours to the timeslot's start and end date / time.
     *
     * @param int $hours
     */
    public function addHour(int $hours = 1)
    {
        $this->start->addHour($hours);
        $this->end->addHour($hours);

        return $this;
    }

    /**
     * Get the start date / time.
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
     * Return a Timeslot on a custom date / time.
     *
     * @param  Carbon\Carbon $start
     * @param  integer $hours
     * @return App\Timeslot
     */
    public static function custom($start, $hours = 1)
    {
        $timeslot = new Timeslot($start, $hours);
        $timeslot->setStart($timeslot->start);
        $timeslot->setEnd($timeslot->hours);
        return $timeslot;
    }

    /**
     * Create a new Timeslot instance based on the current date / time.
     *
     * @param  integer $hours
     * @return App\Timeslot
     */
    public static function now($hours = 1)
    {
        return Timeslot::custom(Carbon::now(), $hours);
    }
}
