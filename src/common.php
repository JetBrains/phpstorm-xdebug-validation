<?php
class XDebugValidator
{
    public static function run() {

        error_reporting(0);

        define('HHVM_PHP_INI', "/etc/hhvm/php.ini");
        define('HHVM_SERVER_INI', "/etc/hhvm/server.ini");
        define('XDEBUG', "xdebug");
        define('ZEND_DEBUGGER', "Zend Debugger");

        function createXmlHeader() {
            return "<?xml version=\"1.0\"?>";
        }

        function createXmlElement($tagName, $attributes, $content = null)
        {
            $result = "";
            $result .= "<section";
            foreach ($attributes as $attributeName => $attributeValue) {
                $result .= " {$attributeName}=\"$attributeValue\"";
            }
            $result .= " section_name=\"$tagName\"";
            if (!empty($content)) {
                $result .= ">";
                $result .= $content;
                $result .= "</section>";
            } else {
                $result .= "/>";
            }
            return $result;
        }

        function collectConfigurationFiles() {
            $files = array(php_ini_loaded_file());
            $scannedFiles = php_ini_scanned_files();
            if ($scannedFiles) {
                foreach (explode(',', $scannedFiles) as $file) {
                    array_push($files, trim($file));
                }
            }
            return $files;
        }

        function validateXdebug() {
            $element = array();
            $element["name"] = XDEBUG;
            $element["zend_extension"] = isLoadByZendExtension($element);
            $element["version"] = htmlspecialchars(phpversion(XDEBUG));
            $element["enable"] = htmlspecialchars(ini_get("xdebug.remote_enable"));
            $element["xdebug3_debug_mode"] = htmlspecialchars(ini_get("xdebug.mode"));
            $element["host"] = htmlspecialchars(ini_get("xdebug.remote_host"));
            $element["xdebug3_host"] = htmlspecialchars(ini_get("xdebug.client_host"));
            $element["port"] = htmlspecialchars(ini_get("xdebug.remote_port"));
            $element["xdebug3_port"] = htmlspecialchars(ini_get("xdebug.client_port"));
            $element["mode"] = htmlspecialchars(ini_get("xdebug.remote_mode"));
            $element["protocol"] = htmlspecialchars(ini_get("xdebug.remote_handler"));
            $element["log"] = htmlspecialchars(ini_get("xdebug.remote_log"));
            $element["xdebug3_log"] = htmlspecialchars(ini_get("xdebug.log"));
            $element["autostart"] = htmlspecialchars(ini_get("xdebug.remote_autostart"));
            $element["xdebug3_autostart"] = htmlspecialchars(ini_get("xdebug.start_with_request"));
            $element["connect_back"] = htmlspecialchars(ini_get("xdebug.remote_connect_back"));
            $element["xdebug3_start_upon_error"] = htmlspecialchars(ini_get("xdebug.start_upon_error"));
            $element["xdebug3_discover_client_host"] = htmlspecialchars(ini_get("xdebug.discover_client_host"));
            $element["xdebug3_client_discovery_header"] = htmlspecialchars(ini_get("xdebug.client_discovery_header"));
            $element["xdebug3_cloud_userid"] = htmlspecialchars(ini_get("xdebug.cloud_id"));
            return $element;
        }

        function isLoadByZendExtension() {
            $warning = error_get_last();
            if (isset($warning) && is_array($warning) &&
                strcasecmp($warning["message"], "Xdebug MUST be loaded as a Zend extension") == 0) {
                return "0";
            }
            return "1";
        }

        function validateZendDebugger() {
            $element = array();
            $element["name"] = ZEND_DEBUGGER;
            $element["enable"] = htmlspecialchars(ini_get("zend_debugger.expose_remotely"));
            $element["host"] = htmlspecialchars(ini_get("zend_debugger.allow_hosts"));
            $element["deny_hosts"] = htmlspecialchars(ini_get("zend_debugger.deny_hosts"));
            return $element;
        }

        /**
         * @param array $config
         * @return array
         */
        function checkHostAccessibility(array $config, array $options) {
            $url = version_compare($options["version"], '3.0.0') ? $options["xdebug3_host"] : $options["host"];
            $port = version_compare($options["version"], '3.0.0') ? $options["xdebug3_port"] : $options["port"];
            $fp = fsockopen($url, 80, $errno, $errstr, 300);
            if (!$fp) {
                $config["status"] = "FAIL";
            }
            else {
                $config["status"] = "OK";
            }
            return $config;
        }

        function hhvmVersion() {
            if (defined('HHVM_VERSION')) {
                return HHVM_VERSION;
            }
            return null;
        }

        $hhvm = hhvmVersion();

        $result = createXmlHeader();
        $content = "";
        $file = php_ini_loaded_file();
        if ((is_null($file) || !$file) && !is_null($hhvm)) {
            $file = HHVM_PHP_INI;
        }
        $content .= createXmlElement(
            "Loaded php.ini",
            array(
                "path" => htmlspecialchars($file)
            ));

        $scannedFiles = php_ini_scanned_files();
        if ((is_null($scannedFiles) || !$scannedFiles) && !is_null($hhvm)) {
            $scannedFiles = HHVM_SERVER_INI;
        }

        if (!is_null($scannedFiles)) {
            $prepared = "";
            $allScannedFiles = explode(',', $scannedFiles);
            $count = count($allScannedFiles);
            if ($count > 0) {
                $prepared .= trim($allScannedFiles[0]);
                for ($i = 1; $i < $count; $i++) {
                    $prepared .= ", ";
                    $prepared .= trim($allScannedFiles[$i]);
                }
                $content .= createXmlElement("Additional php.ini",
                    array(
                        "files" => htmlspecialchars($prepared)
                    )
                );
            }
        }

        $xdebug = extension_loaded(XDEBUG);
        if ($xdebug) {
            $config = validateXdebug();
            $content .= createXmlElement("Debugger", $config);
            $content .= createXmlElement("Xdebug Connection", checkHostAccessibility(array(), $config));
        }

        $zend_debug = extension_loaded(ZEND_DEBUGGER);
        if ($zend_debug) {
            $config = validateZendDebugger();
            $content .= createXmlElement("Debugger", $config);
        }

        $serverName = $_SERVER["SERVER_NAME"];
        $remoteAddr = $_SERVER["REMOTE_ADDR"];
        if (!is_null($serverName) || !is_null($remoteAddr)) {
            $element = array();
            if (!is_null($serverName)) {
                $element["server_name"] = htmlspecialchars($serverName);
            }
            if (!is_null($remoteAddr)) {
                $element["remote_addr"] = htmlspecialchars($remoteAddr);
            }
            $content .= createXmlElement("Server", $element);
        }

        $result . createXmlElement("validation", array(), $content);
        return "<report>"  . $content . "</report>";
    }
}
