<?php
Class Calendar {
	private $id;
	private $number_person;
	private $name;
	private $date;
	private $hour_start;
	private $hour_end;
	private $activity;
	private $location;

	const TABLE_NAME = 'wp_alice_calendar';

	public function __construct($data)
	{
		global $wpdb;
		$this->hydrate($data);
	}

	public function hydrate($data)
	{
		foreach ($data as $key => $value)
		{
			$method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
			if (is_callable(array($this, $method)))
			{
				$this->$method($value);
			}
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function setNumberPerson($number_person)
	{
		return $this->number_person = $number_person;
	}
	public function getNumberPerson()
	{
		return $this->number_person;
	}

	public function setName($name)
	{
		return $this->name = $name;
	}
	public function getName()
	{
		return $this->name;
	}

	public function setDate($date)
	{
		return $this->date = $date;
	}
	public function getDate()
	{
		return $this->date;
	}

	public function setHourStart($hour_start)
	{
		return $this->hour_start = $hour_start;
	}
	public function getHourStart()
	{
		return $this->hour_start;
	}

	public function setHourEnd($hour_end)
	{
		return $this->hour_end = $hour_end;
	}
	public function getHourEnd()
	{
		return $this->hour_end;
	}

	public function setActivity($activity)
	{
		return $this->activity = $activity;
	}
	public function getActivity()
	{
		return $this->activity;
	}

	public function setLocation($location)
	{
		return $this->location = $location;
	}
	public function getLocation()
	{
		return $this->location;
	}



	public static function findAll()
	{
	    global $wpdb;
	    $result = $wpdb->get_results( "SELECT * FROM ".Calendar::TABLE_NAME);
	    return $result;
	  }

  	public static function insert($data)
  	{
  		global $wpdb;
  		$wpdb->insert(
			Calendar::TABLE_NAME,
			array(
				'number_person' => $data['number_person'],
				'name' => $data['name'],
				'date' => $data['date'],
				'hour_start' => $data['hour_start'],
				'hour_end' => $data['hour_end'],
				'activity' => $data['activity'],
				'location' => $data['location'],
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			)
		);
  	}
}
