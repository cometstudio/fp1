<?php

namespace App\Providers\Date;

class Date
{
    public function getInterval($dateTime = null, $hour = 0, $min = 0, $sec = 0)
    {
        try{
            if(empty($dateTime)) throw new \Exception;

            if(!is_numeric($dateTime)) $dateTime = $this->getTimeFromDate($dateTime, $hour, $min, $sec);

            $diff = $dateTime - time();

            if($diff < 0) throw new \Exception;

            $days = intval($diff / 86400);

            $tail = $diff - ($days * 86400);

            $hours = intval($tail / 3600);

            $tail = $tail - ($hours * 3600);

            $mins = intval($tail / 60);

            return [$days, $hours, $mins];
        }catch (\Exception $e){
            return [0, 0, 0];
        }
    }

    public function getTimeFromDate($date = null, $hour = 0, $min = 0, $sec = 0)
    {
        try{
            if(is_string($date)) $date = explode('.', $date);

            $time = mktime($hour, $min, $sec, $date[1], $date[0], $date[2]);

            return $time;
        }catch (\Exception $e){
            return time();
        }
    }

    /**
     * @param int $time
     * @param int $type
     * @return array|int|string
     */
    public function getDateFromTime($time = 0, $type = 0)
    {
        try{
            $date = [];
            
            switch($type){
                default:
                    $months = config('dictionary.months', []);

                    $date[] = date('j', $time);
                    $date[] = $months[date('n', $time)][1];
                    $date[] = date('Y', $time);

                    $date = implode(' ', $date);
                break;
                case 1:
                    $date[] = date('d', $time);
                    $date[] = date('m', $time);
                    $date[] = date('Y', $time);

                    $date = implode('.', $date);
                break;
                case 2:
                    $date[] = date('d', $time);
                    $date[] = date('m', $time);
                    $date[] = date('y', $time);

                    $date = implode('.', $date);
                break;
                case 3:
                    $months = config('dictionary.months', []);

                    $date[] = date('j', $time);
                    $date[] = $months[date('n', $time)][1];
                    $date[] = date('Y', $time);
                    $date[] = 'в';
                    $date[] = date('H:i', $time);

                    $date = implode(' ', $date);
                break;
            }


            return $date;
        }catch (\Exception $e){
            return time();
        }
    }

    public function constructDate(array $data)
    {
        $date = implode('.', $data);
        
        return $date;
    }
    
    public function getMonthLength($month = 0, $year = 0)
    {
        try{
            if(empty($month) || !is_numeric($month)) $month = date('n');
            if(empty($year) || !is_numeric($year)) $year = date('Y');

            $time = mktime(0, 0, 1, $month, 1, $year);

            if(empty($time)) throw new \Exception;

            $length = date('t', $time);

            if(empty($length)) throw new \Exception;

            return $length;
        }catch (\Exception $e){
            return 0;
        }
    }

    public function getMonthStartDay($month = 0, $year = 0)
    {
        try{
            if(empty($month) || !is_numeric($month)) $month = date('n');
            if(empty($year) || !is_numeric($year)) $year = date('Y');

            $time = mktime(0, 0, 1, $month, 1, $year);

            if(empty($time)) throw new \Exception;

            $startDay = date('N', $time);

            if(empty($startDay)) throw new \Exception;

            return $startDay;
        }catch (\Exception $e){
            return 0;
        }
    }

    public function seasonDaysLeft($startAt = 0)
    {
        $seasonStartedAt = !empty(view()->shared('settings')->start_at) ? view()->shared('settings')->start_at : time();

        if(empty($startAt)) $startAt = mktime(0, 0, 0, date('n'), date('j'), date('Y'));

        return intval(($startAt - $seasonStartedAt) / 86400) + 1;
    }

    public function weekday($time = 0, $type = 0)
    {
        if(empty($time)) $time = time();

        return config('dictionary.daysOfWeek.'.date('N', $time))[$type];
    }
}
