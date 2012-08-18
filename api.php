<?php
//|============================================|
//| api.php                                    |
//| Author: nwxxeh                             |
//| Website: http://github.com/nwxxeh/MineAuth |
//| Licensed under GPLv3                       |
//|============================================|

class MineAuthClient {
  public $username;
  public $loginName;
  public $sessionId;
  public $uuid;
  public $error;
  public $loggedIn = 0;
  
  function logIn($username, $password)
  {
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, 'http://login.minecraft.net/');
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_POSTFIELDS, 'user='.$_POST['username'].'&password='.$_POST['password'].'&version=69');
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    $page = curl_exec($c);
    curl_close($c);
    if (strpos($page,':') !== false) {
      $data = explode(':', $page);
      $this->username = $data[2];
      $this->sessionId = $data[3];
      $this->uuid = $data[4];
      $this->loggedIn = 1;
      $this->loginName = $username;
      return 1;
    } else {
      if ($page == "")
      {
	$error = "Couldn't connect to minecraft.net";
	return 0;
      } else {
      $error = $page;
      return 0;
      }
    }
  }

  function keepAlive()
  {
    if ($this->loggedIn == 1)
    {
      $c = curl_init();
      curl_setopt($c, CURLOPT_URL, 'http://login.minecraft.net/session?name='.$this->username.'&session='.$this->sessionId);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      $page = curl_exec($c);
      curl_close($c);
      return 0;
    } else {
      throw new Exception("Tried to send keep alive to server, but is logged out.");
    }
  }

  function authenticate($serverId)
  {
    if ($this->loggedIn == 1)
    {
      $c = curl_init();
      curl_setopt($c, CURLOPT_URL, 'http://session.minecraft.net/game/joinserver.jsp?user='.$this->username.'&sessionId='.$this->sessionId.'&serverId='.$serverId);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
      $page = curl_exec($c);
      curl_close($c);
      if ($page == "OK")
      {
	return 1;
      } else {
	return 0;
      }
    } else {
      throw new Exception("Tried to authenticate to server, but is logged out.");
    }
  }

}

class MineAuthServer {
  public $serverId = "mineAuthTest";

  function validate($username)
  { 
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, 'http://session.minecraft.net/game/checkserver.jsp?user='.$username.'&serverId='.$this->serverId);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    $page = curl_exec($c);
    curl_close($c);
    if ($page == "YES")
    {
      return 1;
    } else {
      return 0;
    }
  }
}
?>