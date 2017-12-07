<?php
/**
 * Created by PhpStorm.
 * User: barte
 * Date: 05.12.2017
 * Time: 12:57
 */

class Hotel
{
	private $id;
	private $name;
	private $adress;

	public function __construct($id=1, $name='', $adress='')
	{
		$this->id = $id;
		$this->name = $name;
		$this->adress = $adress;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getAdress(): string
	{
		return $this->adress;
	}

	public function setId(int $id)
	{
		$this->id = $id;
	}

	public function setName(string $name)
	{
		$this->name = $name;
	}

	public function setAdress(string $adress)
	{
		$this->adress = $adress;
	}

	public function getHotelDetailsFromDB($db)
	{
		try
		{
			if($stmt = $db->prepare('SELECT * FROM `hotel_informations` WHERE `hotel_id` LIKE ?'))
			{
				$stmt->bind_param('i', $this->id);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($hotel_id, $hotel_name, $hotel_adress);
				$stmt->fetch();
				if($stmt->num_rows == 1)
				{
					$this->name = $hotel_name;
					$this->adress = $hotel_adress;
				}
				else
				{
					throw new Exception('Hotel nie istnieje', 404);
				}
			}
			else throw Exception($db->error, $db->errno);
		}
		catch (Exception $e)
		{
			echo $e->getMessage()." Kod: ".$e->getCode();
		}

	}
}
