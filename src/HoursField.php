<?php

namespace Lorisleiva\CronTranslator;

class HoursField extends Field
{
    public $position = 1;

    public function translateEvery()
    {
        if ($this->expression->minute->hasType('Once')) {
            return $this->lang('hours.once_an_hour');
        }

        return $this->lang('hours.every');
    }

    public function translateIncrement()
    {
        if ($this->expression->minute->hasType('Once')) {
            return $this->lang('hours.multiple_times_every_few_hours', [
                'count' => $this->times($this->count),
                'increment' => $this->increment,
            ]);
        }

        if ($this->count > 1) {
            return $this->lang('hours.multiple_hours_out_of_few', [
                'count' => $this->count,
                'increment' => $this->increment,
            ]);
        }

        if ($this->expression->minute->hasType('Every')) {
            return $this->lang('hours.multiple_every_few_hours', [
                'increment' => $this->increment
            ]);
        }

        return $this->lang('hours.every_few_hours', [
            'increment' => $this->increment
        ]);
    }

    public function translateMultiple()
    {
        if ($this->expression->minute->hasType('Once')) {
            return $this->lang('hours.multiple_times_a_day', [
                'times' => $this->times($this->count)
            ]);
        }

        return $this->lang('hours.multiple_hours_a_day', [
            'count' => $this->count
        ]);
    }

    public function translateOnce()
    {
        $minute = $this->expression->minute->hasType('Once')
            ? $this->expression->minute
            : null;

        return $this->lang('hours.once_at_time', [
            'time' => $this->format($minute)
        ]);
    }

    public function format($minute = null, $clock = '12hour')
    {
        $amOrPm = '';
        if ('12hour' === $clock) {
            $amOrPm = $this->value < 12 ? 'am' : 'pm';
        }
        $hour = $this->value === 0 ? 12 : $this->value;
        $hour = $hour > 12 ? $hour - 12 : $hour;

        if ($this->expression->timeFormat24hours) {
            return $minute
                ? date("H:i", strtotime("{$hour}:{$minute->format()} {$amOrPm}"))
                : date("H:i", strtotime("{$hour} {$amOrPm}"));
        }

        return $minute
            ? "{$hour}:{$minute->format()}{$amOrPm}"
            : "{$hour}{$amOrPm}";
    }
}
