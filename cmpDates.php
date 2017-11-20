<?php

class cmpDates
{
    public static $month_days = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    public static function is_LeapYear($year)
    {
        return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year % 400) == 0)));
    }

    public static function get_DaysInMonth($month, $year)
    {
        if ($month == 2 && cmpDates::is_LeapYear($year)) {
            return cmpDates::$month_days[$month] + 1;
        }
        return cmpDates::$month_days[$month];

    }

    public static function get_TotalDaysInMonths($month, $year)
    {
        $total_days = 0;
        for ($m = 1; $m <= $month; $m++) {
            $total_days += cmpDates::get_DaysInMonth($m, $year);
        }
        return $total_days;
    }

    public static function get_TotalDaysInYears($year)
    {
        $leap_years = ($year / 4);
        $not_leap_years = $year - $leap_years;
        return round($leap_years * 366 + $not_leap_years * 365);
    }

    public static function diff($start_date, $end_date)
    {
        $s_date = explode('-', $start_date);
        $s_year = $s_date[0];
        $s_month = $s_date[1];
        $s_day = $s_date[2];

        $e_date = explode('-', $end_date);
        $e_year = $e_date[0];
        $e_month = $e_date[1];
        $e_day = $e_date[2];

        //determining bigger date
        if ($s_year > $e_year) {
            $invert = true;
            $year1 = $e_year;
            $month1 = $e_month;
            $day1 = $e_day;
            $year2 = $s_year;
            $month2 = $s_month;
            $day2 = $s_day;
        } else if ($s_year == $e_year && $s_month > $e_month) {
            $invert = true;
            $year1 = $e_year;
            $month1 = $e_month;
            $day1 = $e_day;
            $year2 = $s_year;
            $month2 = $s_month;
            $day2 = $s_day;
        } else if ($s_year == $e_year && $s_month == $e_month && $s_day > $e_day) {
            $invert = true;
            $year1 = $e_year;
            $month1 = $e_month;
            $day1 = $e_day;
            $year2 = $s_year;
            $month2 = $s_month;
            $day2 = $s_day;
        } else {
            $invert = false;
            $year1 = $s_year;
            $month1 = $s_month;
            $day1 = $s_day;
            $year2 = $e_year;
            $month2 = $e_month;
            $day2 = $e_day;
        }

        $i = 0;
        if ($day1 > $day2) {
            $i = cmpDates::$month_days[$month2 - 1];
        }
        if ($i == 28) {
            if (cmpDates::is_LeapYear($year2)) $i = 29;
        }

        //days
        if ($i != 0) {
            $days = ($day2 + $i) - $day1;
            $i = 1;
        } else {
            $days = $day2 - $day1;

        }

        //months
        if (($month1 + $i) > $month2) {
            $months = ($month2 + 12) - ($month1 + $i);
            $i = 1;

        } else {
            $months = ($month2) - ($month1 + $i);
            $i = 0;
        }

        //years
        $years = $year2 - ($year1 + $i);

        //total_days
        $total_days_months = cmpDates::get_TotalDaysInMonths($month2, $year2) - cmpDates::get_TotalDaysInMonths($month1, $year1);
        $total_days_years = cmpDates::get_TotalDaysInYears($year2) - cmpDates::get_TotalDaysInYears($year1);
        $total_days_days = $day2 - $day1;
        $total_days = $total_days_years + $total_days_months + $total_days_days;

        return (object)array(
            'years' => $years,
            'months' => $months,
            'days' => $days,
            'total_days' => $total_days,
            'invert' => $invert
        );
    }
}

print_r(cmpDates::diff('2015-04-05', '2011-11-15'));