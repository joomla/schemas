<?php

class validateJedUpdate extends PHPUnit_Framework_TestCase
{
    protected $xml;

    protected function setUp()
    {
        $this->xml = new DOMDocument();
        libxml_use_internal_errors(true);
    }

    public function testValidate_forFileJedUpdateSample_expectValidTrue()
    {
        // Arrange
        $jedupdates = array();
		$jedupdates = dirname(__FILE__) . "/jedupdate-sample.xml";
        $schema = dirname(__FILE__) . "/jedupdate.xsd";

        // Act
        foreach ($jedupdates as $jedupdate) {
            $this->xml->load($jedupdate);
            $schemaValidate = $this->xml->schemaValidate($schema);

            // Assert
            $this->libxml_display_errors();
            $this->assertEquals(TRUE, $schemaValidate);
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

        self::$errors = self::$errors + sizeof($errors);

        foreach ($errors as $error) {
            print  $this->libxml_display_error($error);
        }
        libxml_clear_errors();
    } 

}