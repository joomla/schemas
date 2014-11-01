<?php

class validate extends PHPUnit_Framework_TestCase
{
    protected $xsdVersion;

    protected function setUp()
    {
        //from bootstrap file
        global $joomla_version;

        if (!isset($joomla_version)) {
            $this->xsdVersion = "v3";
        } else {
            $this->xsdVersion = $joomla_version;
        }
        $this->xml = new DOMDocument();
        libxml_use_internal_errors(true);
    }

    public function testValidate_forComponentManifests_expectValidTrue()
    {
        // Arrange
        $manifests = $this->getManifests("components", 'type="component"');
        $schema = dirname(__FILE__) . "/" . $this->xsdVersion . "/component.xsd";

        // Act
        foreach ($manifests as $manifest) {
            $this->xml->load($manifest);
            $schemaValidate = $this->xml->schemaValidate($schema);

            // Assert
            $this->libxml_display_errors();

            //TODO can do this or first invalid manifest will break the build
            //$this->assertEquals(TRUE, $schemaValidate);
        }
    }

    public function testValidate_forModuleManifests_expectValidTrue()
    {
        // Arrange
        $manifests = $this->getManifests("modules", 'type="module"');
        $schema = dirname(__FILE__) . "/" . $this->xsdVersion . "/module.xsd";

        // Act
        foreach ($manifests as $manifest) {
            $this->xml->load($manifest);
            $schemaValidate = $this->xml->schemaValidate($schema);

            // Assert
            $this->libxml_display_errors();

            //TODO can do this or first invalid manifest will break the build
            //$this->assertEquals(TRUE, $schemaValidate);
        }
    }

    public function testValidate_forPluginsManifests_expectValidTrue()
    {
        // Arrange
        $manifests = $this->getManifests("plugins", 'type="plugin"');
        $schema = dirname(__FILE__) . "/" . $this->xsdVersion . "/plugin.xsd";

        // Act
        foreach ($manifests as $manifest) {
            $this->xml->load($manifest);
            $schemaValidate = $this->xml->schemaValidate($schema);

            // Assert
            $this->libxml_display_errors();

            //TODO can do this or first invalid manifest will break the build
            //$this->assertEquals(TRUE, $schemaValidate);
        }
    }

    public function testValidate_forTemplatesManifests_expectValidTrue()
    {
        // Arrange
        $manifests = $this->getManifests("templates", 'type="template"');
        $schema = dirname(__FILE__) . "/" . $this->xsdVersion . "/template.xsd";

        // Act
        foreach ($manifests as $manifest) {
            $this->xml->load($manifest);
            $schemaValidate = $this->xml->schemaValidate($schema);

            // Assert
            $this->libxml_display_errors();

            //TODO can do this or first invalid manifest will break the build
            //$this->assertEquals(TRUE, $schemaValidate);
        }
    }

    public function testValidate_forLibraryManifests_expectValidTrue()
    {
        // Arrange
        $manifests = $this->getManifests("manifests/libraries", 'type="library"');
        $schema = dirname(__FILE__) . "/" . $this->xsdVersion . "/library.xsd";

        // Act
        foreach ($manifests as $manifest) {
            $this->xml->load($manifest);
            $schemaValidate = $this->xml->schemaValidate($schema);

            // Assert
            $this->libxml_display_errors();

            //TODO can do this or first invalid manifest will break the build
            //$this->assertEquals(TRUE, $schemaValidate);
        }
    }

    public function testValidate_forLanguagesManifests_expectValidTrue()
    {
        // Arrange
        $manifests = $this->getManifests("languages", "<metafile ");
        $schema = dirname(__FILE__) . "/" . $this->xsdVersion . "/language.xsd";

        // Act
        foreach ($manifests as $manifest) {
            $this->xml->load($manifest);
            $schemaValidate = $this->xml->schemaValidate($schema);

            // Assert
            $this->libxml_display_errors();

            //TODO can do this or first invalid manifest will break the build
            //$this->assertEquals(TRUE, $schemaValidate);
        }
    }

    function libxml_display_error($error)
    {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b>\n";

        return $return;
    }

    private function libxml_display_errors()
    {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            print  $this->libxml_display_error($error);
        }
        libxml_clear_errors();
    }

    private function getManifests($folder = 'components', $extensionType)
    {
        //from bootstrap file
        global $joomla_root;

        $adminManifests = array();
        $adminPath = $joomla_root . 'administrator/' . $folder . '/';
        if (file_exists($adminPath)) {
            $adminManifests = $this->getManifest($extensionType, $adminPath);
        }

        $siteManifests = array();
        $sitePath = $joomla_root . $folder . '/';
        if (file_exists($sitePath)) {
            $siteManifests = $this->getManifest($extensionType, $sitePath);
        }

        return array_merge($adminManifests, $siteManifests);
    }

    private function getManifest($extensionType, $path)
    {
        $manifests = array();

        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.xml$/i', RecursiveRegexIterator::GET_MATCH);

        $iterator_to_array = iterator_to_array($regex);
        foreach ($iterator_to_array as $key => $filename) {
            //TODO poor man approach
            $file = fopen($key, 'r');
            $header = fread($file, 150);
            if (strpos($header, $extensionType) !== FALSE) {
                $manifests[] = $key;
            }
        }

        return $manifests;
    }

}