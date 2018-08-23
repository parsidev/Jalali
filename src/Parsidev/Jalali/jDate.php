<?php namespace Parsidev\Jalali;

class jDate
{
    protected $time;

    protected $convert = true;

    protected $formats = array(
		'datetime' => '%Y-%m-%d %H:%M:%S',
        'datetime2' => '%Y/%m/%d %H:%M:%S',
        'date' => '%Y-%m-%d',
        'time' => '%H:%M:%S',
	);

    public static function forge($str = null, $convert = true)
    {
        $class = __CLASS__;
        return new $class($str, $convert);
    }

    public function __construct($str = null, $convert = true)
    {
        $this->convert = $convert;
        if ($str === null) {
            $this->time = time();
        } else {
            if (is_numeric($str)) {
                $this->time = $str;
            } else {
                $time = strtotime($str);

                if (!$time) {
                    $this->time = false;
                } else {
                    $this->time = $time;
                }
            }
        }
    }

    public function time()
    {
        return $this->time;
    }

    public function format($str)
    {
        // convert alias string
        if (in_array($str, array_keys($this->formats))) {
            $str = $this->formats[$str];
        }

        // if valid unix timestamp...
        if ($this->time !== false) {
            return jDateTime::strftime($str, $this->time, $this->convert);
        } else {
            return false;
        }
    }

    public function reforge($str)
    {
        if ($this->time !== false) {
            // amend the time
            $time = strtotime($str, $this->time);

            // if conversion fails...
            if (!$time) {
                // set time as false
                $this->time = false;
            } else {
                // accept time value
                $this->time = $time;
            }
        }

        return $this;
    }

    public function ago()
    {
        $now = time();
        $time = $this->time();

        // catch error
        if (!$time) return false;

        // build period and length arrays
        $periods = array('ثانیه', 'دقیقه', 'ساعت', 'روز', 'هفته', 'ماه', 'سال', 'قرن');
        $lengths = array(60, 60, 24, 7, 4.35, 12, 10);

        // get difference
        $difference = $now - $time;

        // set descriptor
        if ($difference < 0) {
            $difference = abs($difference); // absolute value
            $negative = true;
        }

        // do math
        for ($j = 0; $difference >= $lengths[$j] and $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        // round difference
        $difference = intval(round($difference));

        // return
        return number_format($difference) . ' ' . $periods[$j] . ' ' . (isset($negative) ? '' : 'پیش');
    }

    public function until()
    {
        return $this->ago();
    }
	
	public static function isLeapJalaliYear($jy)
    {
        return self::jalaliCal($jy)['leap'] === 0;
    }

    private static function jalaliCal($jy)
    {
        $breaks = [-61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181, 1210, 1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178];
        $breaksCount = count($breaks);
        $gy = $jy + 621;
        $leapJ = -14;
        $jp = $breaks[0];
        if ($jy < $jp || $jy >= $breaks[$breaksCount - 1]) {
            throw new \InvalidArgumentException('Invalid Jalali year : ' . $jy);
        }
        $jump = 0;
        for ($i = 1; $i < $breaksCount; $i += 1) {
            $jm = $breaks[$i];
            $jump = $jm - $jp;
            if ($jy < $jm) {
                break;
            }
            $leapJ = $leapJ + self::div($jump, 33) * 8 + self::div(self::mod($jump, 33), 4);
            $jp = $jm;
        }
        $n = $jy - $jp;
        $leapJ = $leapJ + self::div($n, 33) * 8 + self::div(self::mod($n, 33) + 3, 4);
        if (self::mod($jump, 33) === 4 && $jump - $n === 4) {
            $leapJ += 1;
        }
        $leapG = self::div($gy, 4) - self::div((self::div($gy, 100) + 1) * 3, 4) - 150;
        $march = 20 + $leapJ - $leapG;
        if ($jump - $n < 6) {
            $n = $n - $jump + self::div($jump + 4, 33) * 33;
        }
        $leap = self::mod(self::mod($n + 1, 33) - 1, 4);
        if ($leap === -1) {
            $leap = 4;
        }
        return ['leap' => $leap, 'gy' => $gy, 'march' => $march];
    }

    private static function div($a, $b)
    {
        return ~~($a / $b);
    }

    private static function mod($a, $b)
    {
        return $a - ~~($a / $b) * $b;
    }
}
