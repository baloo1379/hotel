<?php
/**
 * Created by PhpStorm.
 * User: barte
 * Date: 05.12.2017
 * Time: 12:56
 */

class Room
{
	private $id;
	private $nr;
	private $usingStatus;
	private $lastClean;

	public function __construct($id)
	{
		$this->id = $id;
		//$this->nr = $nr;
		//$this->isUsed = $iu;
		//$this->lastClean = $lc;
	}

	public function fromDB($db)
	{
		$r = $db->query("SELECT `room_nr`, `is_used`, `last_clean` FROM `rooms` WHERE `room_id` LIKE '{$this->id}'");

		if($r)
		{
			if($r->num_rows > 0)
			{
				while($row = $r->fetch_assoc())
				{
					$this->nr = $row['room_nr'];
					$this->usingStatus = $row['is_used'];
					$this->lastClean = $row['last_clean'];
				}
			}
			else
			{
				$this->nr = "Not Found";
				$this->usingStatus = "";
				$this->lastClean = "";
			}
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function getNumber()
	{
		return $this->nr;
	}

	public function getUsingStatus()
	{
		return $this->usingStatus;
	}

	public function getLastClean()
	{
		return $this->lastClean;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setNr($nr)
	{
		$this->nr = $nr;
	}

	public function setUsingStatus($usingStatus)
	{
		$this->usingStatus = $usingStatus;
	}

	public function setLastClean($lastClean)
	{
		$this->lastClean = $lastClean;
	}
}
