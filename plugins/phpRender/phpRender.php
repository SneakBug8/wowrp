<?php

class phpRender extends AbstractPicoPlugin
{
    protected $enabled = true;

    const API_VERSION = 2;
    private $file;

    //public function onPageRendering(&$templateName, array &$twigVariables) {
    public function onPageRendering(&$twigTemplate, &$twigVariables)
    {
        if ($twigTemplate == "php") {
            $file = $this->getThemesDir() . $this->getConfig('theme') . "/" . $twigVariables["file"];
        }
    }

    public function onPageRendered(&$output)
    {
        if ($file) {
            $output = "";
            include_once $file;
        }
    }
}
