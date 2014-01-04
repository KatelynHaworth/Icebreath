<?php

function get_global_config() {
	return unserialize(constant("CMS_GLOBAL_CONFIG"));
}

function throw_error($code, $name, $status) {
	
	$message = array("response" => $name, "code" => $code, "message" => $status);
	build_response($message, $code, $name);
}

function attempt_auth($level)
{
	if($level === "public")
		return true;
	
	if(isset($_REQUEST['key']) && !empty($_REQUEST['key']))
	{
		$config = get_global_config();
		$keys = $config['keys'];
		$key = $_REQUEST['key'];
		
		foreach($keys as $key_id => $key_modes)
			if($key_id === $key)
				foreach($key_modes as $id => $key_mode)
					if($key_mode === "full" || $key_mode === $level)
						return true;
	}
	else {
		throw_error(403, "Forbidden", "No API key was set or set key does not have the rights to access this level");
	}
}

function array_to_xml($data, &$xml) {
    foreach($data as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                $subnode = $xml->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }
        else {
            $xml->addChild("$key","$value");
        }
    }
}

function array_to_table($data)
{
	$html = "<table border=\"1\">";
	
	foreach($data as $key => $value) {
        if(is_array($value))
			$html .= "<tr><td>" . $key . "</td><td>" . array_to_table($value) . "</td></tr>";
		else if(is_bool($value))
			$html .= "<tr><td>" . $key . "</td><td>" . ($value ? "true" : "false") . "</td></tr>";
		else
			$html .= "<tr><td>" . $key . "</td><td>" . $value . "</td></tr>";
    }
	
	return $html;
}

function build_response($message, $code = 200, $name = "OK")
{
	$response_type = "";
	if(isset($_REQUEST['format']) && !empty($_REQUEST['format']))
		$response_type = $_REQUEST['format'];
	else {
		$config = get_global_config();
		$response_type = $config['default_return_type'];
	}
	
	if($response_type === "HTML")
	{
		header("HTTP/1.1 " . $code . " " . $name);
		header("Content-type: text/html");
		echo array_to_table($message);
		exit;
	}
	else if($response_type === "JSON")
	{
		
		header("HTTP/1.1 " . $code . " " . $name);
		header("Content-type: application/json");
		echo json_encode($message);
		exit;
	}
	else if($response_type === "XML")
	{
		$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><icebreath></icebreath>");
		array_to_xml($message, $xml);
		
		header("HTTP/1.1 " . $code . " " . $name);
		header("Content-type: text/xml");
		echo $xml->asXML();
		exit;
	}
}

function check_for_module($name)
{
	$mod = "modules/" . $name . ".mod.php";
	
	 if((file_exists($mod) === false) || (is_readable($mod) === false))
            return false;
	 else
		 return true;
}

function load_module($name)
{
	$ref;

	try {
		$mod = "modules/" . $name . ".mod.php";
		require_once($mod);
		
		if(is_dir("modules/" . $name))
			foreach (glob("modules/$name/*.php") as $filename)
    			require_once $filename;
		
		$ref = new ReflectionClass($name);
	} catch(Exception $ex) {
		throw_error(404, "Not Found", "That Module Does Not Exist!");
	}	

	$mod = $ref->newInstance();
	return $mod;
}

function get_module_config($name)
{
	$config = get_global_config();
	return $config[$name];
}

function get_system_function()
{
	if(isset($_REQUEST['func']) && !empty($_REQUEST['func']))
		return $_REQUEST['func'];
	else
		throw_error(404, "Not Found", "A System Function Was Not Selected");
}

function get_system_module()
{
	if(isset($_REQUEST['mod']) && !empty($_REQUEST['mod']))
		return $_REQUEST['mod'];
	else
		throw_error(404, "Not Found", "A System Module Was Not Selected");
}

function check_module_auth($name, $system_function)
{
	if(check_for_module($name))
	{
		$mod = load_module($name);
	
		if(attempt_auth($mod->required_auth_level($system_function)))
			return true;
		
		return false;
	}
	else
		return false;
}
?>