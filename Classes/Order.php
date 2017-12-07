<?php
/**
 * Created by PhpStorm.
 * User: barte
 * Date: 05.12.2017
 * Time: 12:56
 */

class Order
{
	private $id;
	private $rid;
	private $cid;
	private $eid;
	private $sd;
	private $ed;

	public function __construct($id=0, $rid="", $cid="", $eid="", $sd="", $ed="")
	{
		$this->id = $id;
		$this->rid = $rid;
		$this->cid = $cid;
		$this->eid = $eid;
		$this->sd = $sd;
		$this->ed = $ed;
	}

	public function fromDB($db)
	{
		$r = $db->query("SELECT `room_id`, `client_id`, `employee_id`, `start_date`, `end_date` FROM `orders` WHERE `order_id` LIKE '{$this->id}'");

		if($r)
		{
			if($r->num_rows > 0)
			{
				while($row = $r->fetch_assoc())
				{
					$this->rid = $row['room_id'];
					$this->cid = $row['client_id'];
					$this->eid = $row['employee_id'];
					$this->sd = $row['start_date'];
					$this->ed = $row['end_date'];
				}
			}
			else
			{
				$this->cid = "Not Found";
				$this->eid = "";
				$this->sd = "";
				$this->ed = "";
			}
		}
	}

	public function toDB($db)
	{
		$adding_query = "INSERT INTO `orders` (`order_id`, `room_id`, `client_id`, `employee_id`, `start_date`, `end_date`) VALUES (NULL, '{$this->rid}', '{$this->cid}', '{$this->eid}', '{$this->sd}', '{$this->ed}')";

		if($db->query($adding_query))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function updateInDb($db)
	{
		try
		{
			if(!$this->exists($db)) throw new Exception('Rezerwacja nie istnieje', 404);
			if($stmt = $db->prepare('UPDATE `orders` SET `room_id` = ?, `client_id` = ?, `start_date` = ?, `end_date` = ? WHERE `order_id` = ?'))
			{
				$stmt->bind_param('iissi', $this->rid, $this->cid, $this->sd, $this->ed, $this->id);
				$stmt->execute();
				$stmt->store_result();
				$stmt->fetch();
				return true;
			}
			else throw new Exception($db->error, $db->errno);
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public function getID()
	{
		return $this->id;
	}

	public function getCID()
	{
		return $this->cid;
	}

	public function getRID()
	{
		return $this->rid;
	}

	public function getEID()
	{
		return $this->eid;
	}

	public function getStartDate()
	{
		return $this->sd;
	}

	public function getEndDate()
	{
		return $this->ed;
	}

	public function exists($db)
	{
		try
		{
			if($stmt = $db->prepare("SELECT `order_id` FROM `orders` WHERE `order_id` = ? LIMIT 1" ))
			{
				$stmt->bind_param('i', $this->id);
				$stmt->execute();
				$stmt->store_result();

				if($stmt->num_rows == 1)
				{
					return true;
				}
				else
				{
					throw new Exception('Rezerwacja nie istnieje', 404);
				}
			}
			else
			{
				throw new Exception('Nie można przygotować zapytania', $db->errno);
			}
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	public function delLink()
	{
		return '<a href="'.ROOT_PATH.'Scripts/delete.php?oid='.$this->id.'">Usuń</a>';
	}

	public function editLink()
	{
		return '<a href="'.ROOT_PATH.'View/edit.php?oid='.$this->id.'">Edytuj</a>';
	}

	public function remove($db)
	{
		if($stmt = $db->prepare('DELETE FROM `orders` WHERE `order_id` = ?'))
		{
			$stmt->bind_param('i', $this->id);
			$stmt->execute();
			return true;
		}
		else
		{
			return false;
		}

	}
}
