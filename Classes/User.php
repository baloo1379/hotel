<?php
/**
 * Created by PhpStorm.
 * User: barte
 * Date: 05.12.2017
 * Time: 10:23
 */

class User
{
    private $id;
    private $login;
    private $name;

    public function __construct($id=0, $login="", $name="", $mail="")
    {
        $this->id = $id;
        $this->login = $login;
        $this->name = $name;
        $this->email = $mail;
        return true;
    }

	public function getLogin(): string
	{
		return $this->login;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setLogin(string $login)
	{
		$this->login = $login;
	}

	public function setName(string $name)
	{
		$this->name = $name;
	}

	public function setId(int $id)
	{
		$this->id = $id;
	}

    public function get_all_details()
    {
        return [$this->id, $this->login, $this->name, $this->email];
        /*
        echo "<table class=\"blueTable\">
                    <thead>
                        <th>Id</th>
                        <th>Login</th>
                        <th>Nazwa</th>
                        <th>e-mail</th>
                    </thead>
                    <tr>";
        echo '<td>';
        echo $this->id;
        echo '</td>';

        echo '<td>';
        echo $this->login;
        echo '</td>';

        echo '<td>';
        echo $this->name;
        echo '</td>';

        echo '<td>';
        echo $this->email;
        echo '</td>';
        echo "</tr>
            </table>";
        */
    }

    public function exists($db)
    {
        if($stmt = $db->prepare("SELECT `user_id` FROM `users` WHERE `user_login` = ? LIMIT 1" ))
        {
            $stmt->bind_param('s', $this->login);
            $stmt->execute();
            $stmt->store_result();

            if($stmt->num_rows == 1)
            {
                return true;
            	//throw new Exception('Uźytkownik istnieje', 101);
            }
            else
            {
                return false;
            }
        }
        else
        {
			return false;
        	//throw new Exception('Nie można przygotować zapytania', $db->errno);
        }
    }

    public function login($db, $login, $pass)
    {
        $login = $db->real_escape_string($login);
        $pass = $db->real_escape_string($pass);

        if($stmt = $db->prepare("SELECT `user_id`, `user_pass`, `user_name` FROM `users` WHERE `user_login` = ? LIMIT 1"))
        {
            $stmt->bind_param('s', $login);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id, $db_pass, $user_name);
            $stmt->fetch();

            if($stmt->num_rows == 1)
            {
                if(password_verify($pass, $db_pass))
                {
                    $this->id = $user_id;
                    $this->login = $login;
                    $this->name = $user_name;
                    $_SESSION['id'] = hash('sha256', $db_pass.$user_id.$_SERVER['HTTP_USER_AGENT']);
                    return true;
                }
                else {
                    return false;
                }
            }
            else return false;
        }
        else return false;
    }

    public function logged($db)
    {
        if(isset($_SESSION['user'],$_SESSION['id']))
        {
            $id = $_SESSION['id'];
            $user = $_SESSION['user'];

            if($stmt = $db->prepare("SELECT `user_id`, `user_pass` FROM `users` WHERE user_login = ? "))
            {
                $stmt->bind_param('s', $user->login);
                $stmt->execute();
                $stmt->store_result();
				$stmt->bind_result($user_id, $user_pass);
				$stmt->fetch();

                if($stmt->num_rows == 1)
                {
                    $login_ckeck = hash('sha256', $user_pass.$user_id.$_SERVER['HTTP_USER_AGENT']);

                    if(hash_equals($id, $login_ckeck))
                    {
                        //logged
                        return true;
                    }
                    else
                    {
                        //hash isnt equal
                        return false;
                    }
                }
                else
                {
                    //rows
                    return false;
                }
            }
            else
            {
                //database 1
                return false;
            }
        }
        else
        {
            //session
            return false;
        }
    }

    public function logout()
    {
        $_SESSION = array();
        $params = session_get_cookie_params();
        setcookie(session_name(),
            '', time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]);
        session_destroy();
        header('Location: ../index.php');
        exit();
    }

    public function register($db, $pass)
    {
        if($this->exists($db))
        {
            throw new Exception('Konto o tym loginie już istnieje', 301);
        }
        else
        {
            $pass = password_hash($db->real_escape_string($pass),PASSWORD_DEFAULT);
            if($stmt = $db->prepare('INSERT  INTO `users` (`user_id`, `user_login`, `user_pass`, `user_name`) VALUES (NULL, ?, ?, ?)'))
            {
                $stmt->bind_param('sss', $this->login, $pass, $this->name);
                if(!$stmt->execute())
                {
                    throw new Exception($db->error, $db->errno);
                }
                else
                {
                    return true;
                }
            }
            throw new Exception($db->error, $db->errno);
        }
    }

	public function change_password($db, $old_pass, $new_pass)
	{
		if(!login($db, $this->login, $old_pass)) return false;
		else
		{
			$new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
			if($stmt = $db->prepare('UPDATE `users` SET `user_pass` = ? WHERE `user_id` = ?'))
			{
				$stmt->bind_param('si', $new_pass, $this->id);
				$stmt->execute();
				$stmt->store_result();
				$stmt->fetch();
				$this->logout();
				//header('Location: '.ROOT_PATH.'/index.php?chg=1');
				return true;
			}
			else throw new Exception($db->error, $db->errno);
		}
	}
}
