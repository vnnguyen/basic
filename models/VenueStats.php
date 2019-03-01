<?php
namespace common\models;

class VenueStats extends MyActiveRecord
{
	public static function tableName()
	{
		return 'venue_stats';
	}

    public function getVenue()
    {
        return $this->hasOne(Venue::className(), ['id' => 'venue_id']);
    }
}
