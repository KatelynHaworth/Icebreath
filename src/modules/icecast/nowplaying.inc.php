<?php
namespace icecast;

class nowplaying
{
	public function getNowPlayingStats()
	{		
		$config = get_module_config('icecast');
		
		$mount_point = "/" . $config['RADIO_DEFAULT_MOUNT'];
		$mount_selected = false;
		$return_xml = false;

		if($config['ALLOW_MOUNT_SELECTION'] && !empty($_REQUEST['mount']))
		{
			$mount_point = "/" . $_REQUEST['mount'];
			$mount_selected = true;
		}
		
		$mounts = $this->getServerSources();
		$data = array( "not_found" => true );

		if($this->isMountOnline($this->getMount($mount_point, $mounts)))
			$data = $this->getStats($this->getMount($mount_point, $mounts));
		else if(!$this->isMountOnline($this->getMount($mount_point, $mounts)) && $mount_selected)
			$data = array( "not_found" => false, "online" => false);
		else if(!$this->isMountOnline($this->getMount($mount_point, $mounts)) && !$mount_selected && $config['RADIO_MOUNT_BACKUPS'])
		{
			foreach($config['RADIO_BACKUP_ARRAY'] as $backup_name)
			{
				$backup_mount = $this->getMount("/" . $backup_name, $mounts);
		
				if($this->isMountOnline($backup_mount))
				{
					$data = $this->getStats($backup_mount);
					break;
				}
			}
		}
		
		build_response($data);
	}
	
	private function getServerSources()
	{
		$config = get_module_config('icecast');
		$url = "http://" . $config['RADIO_SERVER_NAME'] . ":" . $config['RADIO_SERVER_PASS'] . "@" . $config['RADIO_SERVER'] . ":" . $config['RADIO_SERVER_PORT'] . "/admin/stats";
		$xml_string = file_get_contents($url);
		
		$xml = new \SimpleXMLElement($xml_string);
		return $xml->xpath('source');
	}
	
	private function getMount($name, $sources)
	{
		foreach($sources as $source)
			if((string)$source['mount'] === $name)
				return $source;	
		return NULL;
	}

	private function isMountOnline($source)
	{
		if(empty($source->source_ip))
			return false;
		
		return true;
	}
	
	private function getStats($mount)
	{
		$mount_info = array();
		$mount_info['not_found'] = false;
		$config = get_module_config('icecast');
	
		if($config['GET_ONLINE'])
			$mount_info['online'] = $this->isMountOnline($mount);
		
		if($config['GET_MOUNT'])
			$mount_info['mount'] = (string)$mount['mount'];
		
		if($config['GET_BITRATE'])
			$mount_info['bitrate'] = (string)$mount->bitrate;
		
		if($config['GET_WEBSITE'])
			$mount_info['website'] = (string)$mount->server_url;
		
		if($config['GET_NAME'])
			$mount_info['name'] = (string)$mount->server_name;
		
		if($config['GET_DESCRIPTION'])
			$mount_info['description'] = (string)$mount->server_description;
		
		if($config['GET_TYPE'])
			$mount_info['type'] = (string)$mount->server_type;
		
		if($config['GET_SOURCE'])
			$mount_info['source'] = (string)$mount->source_ip;
		
		if($config['GET_LISTENERS'])
			$mount_info['listeners'] = (string)$mount->listeners;
		
		if($config['GET_TITLE'])
			$mount_info['title'] = (string)$mount->title;
		
		if($config['GET_MAXLISTEN'])
			$mount_info['max_listeners'] = (string)$mount->max_listeners;
		
		if($config['GET_LISTENURL'])
			$mount_info['listen_url'] = (string)$mount->listenurl;
		
		if($config['GET_AGENT'])
			$mount_info['agent'] = (string)$mount->user_agent;
		
		if($config['GET_LISTENPEAK'])
			$mount_info['listener_peak'] = (string)$mount->listener_peak;
		
		if($config['GET_GENRE'])
			$mount_info['genre'] = (string)$mount->genre;
	
		if($config['DO_EXPLODE_TITLE'])
		{
			$split = explode($config['EXPLODE_TYPE'], (string)$mount->title);
		
			$mount_info['now_playing']['artist'] = trim($split[0]);
			$mount_info['now_playing']['song'] = "";
		
			for($part = 1; $part < count($split); $part++)
			{
				if($part > 1)
					$mount_info['now_playing']['song'] .= ' - ';
				
				$mount_info['now_playing']['song'] .= $split[$part];
			}
		}
	
		return $mount_info;
	}
}
?>