<?php
/**
 * Created by PhpStorm.
 * User: barte
 * Date: 05.12.2017
 * Time: 12:55
 */

class Client
{
	private $id;
	private $firstName;
	private $lastName;
	private $tel;
	private $email;

	public function __construct($id=0, $first="", $last="", $tel="", $email="")
	{
		$this->id = $id;
		$this->firstName = $first;
		$this->lastName = $last;
		$this->tel = $tel;
		$this->email = $email;
	}

	public function fromDB($db): bool
	{
		if($stmt = $db->prepare('SELECT `first_name`, `last_name`, `contact_nr`, `contact_email` FROM `clients` WHERE `client_id` LIKE ?'))
		{
			$stmt->bind_param('s', $this->id);
			$stmt->execute();
			$stmt->store_result();

			$stmt->bind_result($this->firstName, $this->lastName, $this->tel, $this->email);
			$stmt->fetch();
			return true;
		}
		else
		{
			$this->firstName = "Not Found";
			$this->lastName = "";
			$this->tel = "";
			$this->email = "";
			return false;
		}
	}

	public function toDB($db)
	{
		$adding_query = "INSERT INTO `clients` (`client_id`, `first_name`, `last_name`, `contact_nr`, `contact_email`) VALUES (NULL, '{$this->getFName()}', '{$this->getLName()}', '{$this->getTel()}', '{$this->getEmail()}')";

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
			if(!$this->exists($db)) throw new Exception('Klient nie istnieje', 404);
			if($stmt = $db->prepare('UPDATE `clients` SET `first_name` = ?, `last_name` = ?, `contact_nr` = ?, `contact_email` = ? WHERE `client_id` = ?'))
			{
				$stmt->bind_param('ssssi', $this->firstName, $this->lastName, $this->tel, $this->email, $this->id);
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

	public function getFName()
	{
		return $this->firstName;
	}

	public function getLName()
	{
		return $this->lastName;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTel()
	{
		return $this->tel;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function exists($db)
	{
		try
		{
			if($stmt = $db->prepare("SELECT `client_id` FROM `clients` WHERE `client_id` = ? LIMIT 1" ))
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
					throw new Exception('Uźytkownik nie istnieje', 404);
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

	public function NameLink()
	{
		$link = '<a href="'.ROOT_PATH.'View/details.php?cid='.$this->id.'">'.$this->firstName.' '.$this->lastName.'</a>';
		return $link;
	}

	public function delLink()
	{
		$link = '<a href="'.ROOT_PATH.'Scripts/delete.php?cid='.$this->id.'">Usuń</a>';
		return $link;
	}

	public function editLink()
	{
		return '<a href="'.ROOT_PATH.'View/edit.php?cid='.$this->id.'">Edytuj</a>';
	}

	public function remove($db)
	{
		if($stmt = $db->prepare('DELETE FROM `clients` WHERE `clients`.`client_id` = ?'))
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
