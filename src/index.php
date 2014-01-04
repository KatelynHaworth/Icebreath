<?php
/**
 * Icebreath is designed to be a quick and easy to use
 * moduler based data access system. It is main based
 * around Icecast.
 * 
 * Icebreath was written for the purpose of supplying
 * an API to access Icecast stats from a server via
 * publicly accessible API. Icebreath allows modules
 * to be locked down and ony allow set keys to access
 * that module. By default all modules are set to 'public'
 *
 *
 * Copyright (c) Liam 'Auzzie' Haworth <liam@auzzie.pw>, 2013-2014.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
 
session_start();
require_once('config.inc.php');
require_once('functions.inc.php');
require_once('database.inc.php');

$config = get_global_config();

if(!empty($config['full_api_lockdown']) && $config['full_api_lockdown'])
	if(!attempt_auth("full"))
		throw_error(403, "Forbidden", "No API key was set or set key does not have the rights to access this level");

$system_func = get_system_function();
$system_mode = get_system_module();


if(!check_module_auth($system_mode, $system_func))
	throw_error(403, "Forbidden", "API auth failed. API key is invailed or module doesn't exist");
else {
	$mod = load_module($system_mode);
	$mod->run();
}
?>