<?php
namespace Ninja;

class EntryPoint {
	private $route;
	private $method;
	private $routes;

	public function __construct(string $route, string $method, \Ninja\Routes $routes) {
		$this->route = $route;
		$this->routes = $routes;
		$this->method = $method;
		$this->checkUrl();
	}

	private function checkUrl() {
		if ($this->route !== strtolower($this->route)) {
			http_response_code(301);
			header('location: ' . strtolower($this->route));
		}
	}

	public function run() {

		$routes = $this->routes->getRoutes();	

		$authentication = $this->routes->getAuthentication();

		if (isset($routes[$this->route]['login']) && !$authentication->isLoggedIn()) {
			header('location: login');
		}
		else if (isset($routes[$this->route]['permissions']) && !$this->routes->checkPermission($routes[$this->route]['permissions'])) {
			header('location: login-permissionserror');	
		}
		else {
			$controller = $routes[$this->route][$this->method]['controller'];
			$action = $routes[$this->route][$this->method]['action'];

			$page = $controller->$action();
			$output = '';
			
			if(isset($page['template']))
			{
				if (isset($page['variables'])) {
					$output = loadTemplate($page['template'], $page['variables']);
				}
				else {
					$output = loadTemplate($page['template']);
				}
			}

			if(isset($page['wrapper']))
			{
				
				if(!$page['wrapper'])
				{
			
					$output = $output==''?$page:$output;
					unset($output['wrapper']);

					if(isset($output["noAjax"]))
					{
						if($output["noAjax"])
						{
							echo $output[0];
							return;
						}
					}
					$output = json_encode($output);
					header('Content-Type: application/json');
					echo $output;
  
					return;
				}
			}


			$reg = false;
			if($_SESSION['reg'])
			{
				$reg = true;
			}

			

			echo loadTemplate('layout/layout.html.php', ['user' => $authentication->getUser(),
														 'reg' => $reg,
			                                             'output' => $output,
			                                            ]);

		}

	}
}