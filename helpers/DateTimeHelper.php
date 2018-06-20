<?
namespace app\helpers;

use Yii;

class DateTimeHelper
{
	public static function format($datetime, $format = 'd/m/Y', $timezone = 'Asia/Ho_Chi_Minh')
	{
		return date_format(date_timezone_set(date_create($datetime), timezone_open($timezone)), $format);
	}

	/**
	 * Converts DT from TZ 1 to TZ 2
	 * And formats DT
	 */
	public static function convert($datetime, $format='d/m/Y', $fromTimeZone = 'UTC', $toTimeZone = 'Asia/Ho_Chi_Minh')
	{
		$dt = new \DateTime($datetime, new \DateTimeZone($fromTimeZone));
		$dt->setTimezone(new \DateTimeZone($toTimeZone));
		return $dt->format($format);
	}
}