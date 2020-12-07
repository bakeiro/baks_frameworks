<?php
// phpcs:disable PSR1.Classes.ClassDeclaration

namespace Engine;

/**
 * Parses the url, and sets useful information based in that, like if it's https, the domain name
 * protocol etc
 */
class Router
{
    public $action;
    public $path;

    /**
     * Parses the url, and saves useful information
     *
     * @return void
     */
    public function __construct()
    {
        $url_action = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        if (isset($_REQUEST['route'])) {
            $path = $_REQUEST['route'];
        } else {
            $path = $this->getPathFromSeoUrl($url_action);
        }

        $this->action = $url_action;
        $this->path = $path;
    }

    /**
     * Parses the url, and sets the controller, class and method based in that
     *
     * @param string $path url to parse
     *
     * @return array
     */
    public function parsePath()
    {
        $url_split = explode('/', $this->path);

        $file = "src/" . $url_split[0] . '/controller/' . $url_split[1] . 'Controller.php';
        $class = "Controller\\" . $url_split[1] . 'Controller';
        $method = $url_split[2];

        if (!$this->isValidPath($file, $class, $file)) {
            $file = 'src/common/controller/commonController.php';
            $method = 'pageNotFound';
            $class = "Common\\commonController";
        }

        return [
            "file" => $file,
            "method" => $method,
            "class" => $class
        ];
    }

    /**
     * Checks wether the file, class and method exist, if not, uses the error controller
     *
     * @return bool
     */
    private function isValidPath($file, $class, $method)
    {
        $is_controller_ok = true;
        if (!file_exists($file)) {
            $is_controller_ok = false;
        }

        include_once $file;

        if (method_exists($class, $method) === false) {
            $is_controller_ok = false;
        }

        return $is_controller_ok;
    }

   /**
     * Search the seo url entered in the function's parameter, in the routes.php file, and sets
     * controller, class and method associated to that seo url
     *
     * @param string $url_action seo url to search
     *
     * @return string
     */
    private function getPathFromSeoUrl($url_action)
    {
        $routes = include "system/config/routes.php";

        if (in_array($url_action, $routes)) {
            return $routes[$url_action];
        } else {
            return "common/common/pageNotFound";
        }
    }
}
