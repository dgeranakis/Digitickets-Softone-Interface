<?php

/**
 * Return current applicatio locale language
 */
if (!function_exists('appLocale')) {
    function appLocale()
    {
        return app()->getLocale();
    }
}

/**
 * Return current user's lines per page or default lines per page 
 */
if (!function_exists('userLinesPerPage')) {
    function userLinesPerPage()
    {
        return (filled(auth()->user()->lines_per_page) ? auth()->user()->lines_per_page : config('constants.default_lines_per_page'));
    }
}

if (!function_exists('changeDateFormat')) {
    function changeDateFormat($date, $date_format = null)
    {
        if (!filled($date_format)) $date_format = config('constants.date_format');
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($date_format);
    }
}

if (!function_exists('changeDateTimeFormat')) {
    function changeDateTimeFormat($date, $date_format = null)
    {
        if (!filled($date_format)) $date_format = config('constants.date_time_format');
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format($date_format);
    }
}

/**
 * Html for create button
 * 
 * @param string $title of button
 * @param string $id id attribute of button
 * @param string $class classes to add to the button
 * 
 * @return string html
 */
if (!function_exists('dataTableCreateBtn')) {
    function dataTableCreateBtn($title = null, $id = 'create', $onclick = null, $class = null)
    {
        if (!filled($title)) $title = __('forms.create');
        return '<button type="button" class="btn btn-primary d-print-none fs-5 py-0 px-2 create ' . $class . '"
                            id="' . $id . '" title="' . $title . '" onclick="' . $onclick . '"><i class="bi bi-plus"></i></button>';
    }
}

/**
 * Create query
 *
 * @param string table name
 * @param array advanced search
 * @param object $query
 *
 * @return boolean
 */
if (!function_exists('createQuery')) {
    function createQuery($table, $adv_search, &$query)
    {
        foreach ($adv_search as $where) {
            if (isset($where->operator) && isset($where->search) && isset($where->value)) {
                switch ($where->operator) {
                    case "yes":
                        $query->where($table . '.' . $where->search, '=', '1');
                        break;
                    case "no":
                        $query->where($table . '.' . $where->search, '=', '0');
                        break;
                    case "isnot":
                        $query->where(function ($qr) use ($table, $where) {
                            $qr->where($table . '.' . $where->search, '!=', $where->value)
                                ->orWhereNull($table . '.' . $where->search);
                        });
                        break;
                    case "in":
                        $query->whereIn($table . '.' . $where->search, explode(',', $where->value));
                        break;
                    case "t_contains":
                        $query->whereTranslationLike($where->search, "%" . $where->value . "%");
                        break;
                    case "contains":
                        $query->where($table . '.' . $where->search, 'like', '%' . $where->value . '%');
                        break;
                    case "none":
                        $query->whereNull($table . '.' . $where->search);
                        break;
                    case "any":
                        $query->whereNotNull($table . '.' . $where->search);
                        break;
                    case "t_is":
                        $query->whereTranslation($where->search, $where->value);
                        break;
                    case "is_time":
                        $query->where($table . '.' . $where->search, 'like', $where->value . '%');
                        break;
                    case "greater_than":
                        $query->where($table . '.' . $where->search, '>=', $where->value);
                        break;
                    case "greater_than_time":
                        $query->where($table . '.' . $where->search, '>=', $where->value . ' 00:00:00');
                        break;
                    case "less_than":
                        $query->where($table . '.' . $where->search, '<=', $where->value);
                        break;
                    case "less_than_time":
                        $query->where($table . '.' . $where->search, '<=', $where->value . ' 23:59:59');
                        break;
                    case "today":
                        $query->where($table . '.' . $where->search, '=', date('Y-m-d'));
                        break;
                    case "today_time":
                        $query->where($table . '.' . $where->search, 'like', date('Y-m-d') . '%');
                        break;
                    case "yesterday":
                        $query->where($table . '.' . $where->search, '=', date('Y-m-d', strtotime("-1 days")));
                        break;
                    case "yesterday_time":
                        $query->where($table . '.' . $where->search, 'like', date('Y-m-d', strtotime("-1 days")) . '%');
                        break;
                    case "between":
                        $values = explode(',', $where->value);
                        $query->where([
                            [$table . '.' . $where->search, '>=', $values[0]],
                            [$table . '.' . $where->search, '<=', $values[1]]
                        ]);
                        break;
                    case "between_time":
                        $values = explode(',', $where->value);
                        $query->where([
                            [$table . '.' . $where->search, '>=', $values[0] . ' 00:00:00'],
                            [$table . '.' . $where->search, '<=', $values[1] . ' 23:59:59']
                        ]);
                        break;
                    case "this_week":
                    case "last_week":
                    case "this_month":
                    case "last_month":
                    case "this_year":
                    case "last_year":
                        $query->where([
                            [$table . '.' . $where->search, '>=', firstDayOf($where->operator)->format('Y-m-d')],
                            [$table . '.' . $where->search, '<=', lastDayOf($where->operator)->format('Y-m-d')]
                        ]);
                        break;
                    case "this_week_time":
                    case "last_week_time":
                    case "this_month_time":
                    case "last_month_time":
                    case "this_year_time":
                    case "last_year_time":
                        $query->where([
                            [$table . '.' . $where->search, '>=', firstDayOf(substr($where->operator, 0, -5))->format('Y-m-d') . ' 00:00:00'],
                            [$table . '.' . $where->search, '<=', lastDayOf(substr($where->operator, 0, -5))->format('Y-m-d') . ' 23:59:59']
                        ]);
                        break;
                    case "is":
                    default:
                        $query->where($table . '.' . $where->search, '=', $where->value);
                        break;
                } // switch
            }
        } // foreach

        return true;
    }
}

/**
 * Return the first day of the Week/Month/Quarter/Year that the
 * current/provided date falls within
 *
 * @param string   $period The period to find the first day of. ('year', 'quarter', 'month', 'week')
 * @param DateTime $date   The date to use instead of the current date
 *
 * @return DateTime
 * @throws InvalidArgumentException
 */
if (!function_exists('firstDayOf')) {
    function firstDayOf($period, DateTime $date = null)
    {
        $period = strtolower($period);
        $validPeriods = array('this_year', 'last_year', 'quarter', 'this_month', 'last_month', 'this_week', 'last_week');
        if (!in_array($period, $validPeriods)) throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

        $newDate = ($date === null) ? new DateTime() : clone $date;

        switch ($period) {
            case 'this_year':
                $newDate->modify('first day of january ' . $newDate->format('Y'));
                break;
            case 'last_year':
                $newDate->modify('first day of january ' . ($newDate->format('Y') - 1));
                break;
            case 'quarter':
                $month = $newDate->format('n');

                if ($month < 4) $newDate->modify('first day of january ' . $newDate->format('Y'));
                elseif ($month > 3 && $month < 7) $newDate->modify('first day of april ' . $newDate->format('Y'));
                elseif ($month > 6 && $month < 10) $newDate->modify('first day of july ' . $newDate->format('Y'));
                elseif ($month > 9) $newDate->modify('first day of october ' . $newDate->format('Y'));

                break;
            case 'this_month':
                $newDate->modify('first day of this month');
                break;
            case 'last_month':
                $newDate->modify('first day of last month');
                break;
            case 'this_week':
                $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
                break;
            case 'last_week':
                $newDate->modify(($newDate->format('w') === '0') ? 'monday -2 weeks' : 'monday last week');
                break;
        }

        return $newDate;
    }
}


/**
 * Return the last day of the Week/Month/Quarter/Year that the
 * current/provided date falls within
 *
 * @param string   $period The period to find the last day of. ('year', 'quarter', 'month', 'week')
 * @param DateTime $date   The date to use instead of the current date
 *
 * @return DateTime
 * @throws InvalidArgumentException
 */
if (!function_exists('lastDayOf')) {
    function lastDayOf($period, DateTime $date = null)
    {
        $period = strtolower($period);
        $validPeriods = array('this_year', 'last_year', 'quarter', 'this_month', 'last_month', 'this_week', 'last_week');
        if (!in_array($period, $validPeriods)) throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

        $newDate = ($date === null) ? new DateTime() : clone $date;

        switch ($period) {
            case 'this_year':
                $newDate->modify('last day of december ' . $newDate->format('Y'));
                break;
            case 'last_year':
                $newDate->modify('last day of december ' . ($newDate->format('Y') - 1));
                break;
            case 'quarter':
                $month = $newDate->format('n');

                if ($month < 4) $newDate->modify('last day of march ' . $newDate->format('Y'));
                elseif ($month > 3 && $month < 7) $newDate->modify('last day of june ' . $newDate->format('Y'));
                elseif ($month > 6 && $month < 10) $newDate->modify('last day of september ' . $newDate->format('Y'));
                elseif ($month > 9) $newDate->modify('last day of december ' . $newDate->format('Y'));

                break;
            case 'this_month':
                $newDate->modify('last day of this month');
                break;
            case 'last_month':
                $newDate->modify('last day of last month');
                break;
            case 'this_week':
                $newDate->modify(($newDate->format('w') === '0') ? 'now' : 'sunday this week');
                break;
            case 'last_week':
                $newDate->modify(($newDate->format('w') === '0') ? 'sunday last week' : 'sunday last week');
                break;
        }

        return $newDate;
    }
}
