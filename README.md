What is MineAuth?
=================
MineAuth is try to implement Minecraft login protocol as website authentication method (like OpenID etc.).

Sample usage
============

Login into Minecraft account and return error if they'll happen:

    include("api.php");
    $auth = new MineAuthClient();
    if ($auth->logIn($_POST['username'], $_POST['password']) == 1)
    {
      echo "You are logged in as: ".$auth->username;
    } else {
      echo $auth->error;
    }