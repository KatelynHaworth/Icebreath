<?php
class icecast
{
	private $config;
	
	function required_auth_level($func)
	{
		if($func === "get")
			return "public";
		else if($func === "set")
			return "full";
	}
	
	function run()
	{
		if(get_system_function() === "get")
		{
			if(!isset($_REQUEST['query']) || empty($_REQUEST['query']))
				throw_error(412, "Precondition Failed", "No query for function 'get' was set");
			else {
				$query = $_REQUEST['query'];
				
				if($query === "nowplaying")
				{
					$nowplaying_mod = new icecast\nowplaying();
					build_response($nowplaying_mod->getNowPlayingStats());
				}
			}
		}	
	}
}
?>