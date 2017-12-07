<?php
/**
 * Created by PhpStorm.
 * User: barte
 * Date: 05.12.2017
 * Time: 19:38
 */

class Notification
{
	private $message;
	private $where;

	public function __construct($message='', $where='')
	{
		$this->message = $message;
		$this->where = $where;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function getWhere()
	{
		return $this->where;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function setWhere($where)
	{
		$this->where = $where;
	}

	public  function send()
	{
		header('Location: '.'http://'.$_SERVER['SERVER_NAME'].'/hotel/'.$this->where.'?message='.$this->message);
	}
}
