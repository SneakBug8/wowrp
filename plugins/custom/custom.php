<?php

class custom extends AbstractPicoPlugin
{
    protected $enabled = true;

    const API_VERSION = 2;

    public function __construct(Pico $pico) {
        $this->PicoTwigExtension = new PicoTwigExtension($pico);

        parent::__construct($pico);
    }

    public function getStories($current_page) {
        $res = [];

        $pages = $this->getPico()->getPages();
        $pages = $this->PicoTwigExtension->sortByFilter($pages, "title");
        $pages = $this->PicoTwigExtension->sortByFilter($pages, ["meta", "weight"]);

        foreach ($pages as $page) {
            if (strpos($page['id'], "story/") === false) { continue; }

            if ((is_array($current_page['meta']['story']) &&
                in_array($page['id'], $current_page['meta']['story'])) || (
                is_string($current_page['meta']['story']) &&
                $page['id'] == $current_page['meta']['story'])) {
                $res[] = '<a href = "' . $page['url'] . '">' . $page['title'] . '</a>';
            }
        }

        return implode(", ", $res);
    }

    public function getCharacters($current_page) {
        $res = [];

        $pages = $this->getPico()->getPages();
        $pages = $this->PicoTwigExtension->sortByFilter($pages, "title");
        $pages = $this->PicoTwigExtension->sortByFilter($pages, ["meta", "weight"]);

        foreach ($pages as $page) {
            if (strpos($page['id'], "characters/") === false) { continue; }

            if ((is_array($current_page['meta']['characters']) &&
                in_array($page['id'], $current_page['meta']['characters'])) || (
                is_string($current_page['meta']['characters']) &&
                $page['id'] == $current_page['meta']['characters'])) {
                $res[] = '<li><a href = "' . $page['url'] . '">' . $page['title'] . '</a></li>';
            }
        }

        return implode($res);
    }

    public function getCharEvents($current_page) {
        $res = [];

        $pages = $this->getPico()->getPages();

        $pages = $this->getPico()->getPages();
        $pages = $this->PicoTwigExtension->sortByFilter($pages, "title");
        $pages = $this->PicoTwigExtension->sortByFilter($pages, ["meta", "weight"]);

        foreach ($pages as $page) {
            if (strpos($page['id'], "events/") === false) { continue; }

            if ((is_array($page['meta']['characters']) &&
                in_array($current_page['id'], $page['meta']['characters'])) || (
                is_string($page['meta']['characters']) &&
                $current_page['id'] == $page['meta']['characters'])) {
                $res[] = '<li><a href = "' . $page['url'] . '">' . $page['title'] . '</a></li>';
            }
        }

        return implode($res);
    }

    public function getStoryEvents($current_page) {
        $res = [];

        $pages = $this->getPico()->getPages();
        $pages = $this->PicoTwigExtension->sortByFilter($pages, "title");
        $pages = $this->PicoTwigExtension->sortByFilter($pages, ["meta", "weight"]);

        foreach ($pages as $page) {
            if (strpos($page['id'], "events/") === false) { continue; }

            if ((is_array($page['meta']['story']) &&
                in_array($current_page['id'], $page['meta']['story'])) || (
                is_string($page['meta']['story']) &&
                $current_page['id'] == $page['meta']['story'])) {
                $res[] = '<li><a href = "' . $page['url'] . '">' . $page['title'] . '</a></li>';
            }
        }

        return implode($res);
    }

    //public function onPageRendering(&$templateName, array &$twigVariables) {
    public function onPagesDiscovered(&$pages)
    {
        if (strlen($_SERVER['SERVER_NAME']) != 18) {
            $dh = opendir($this->getPico()->getConfig('content_dir'));
            while (($file = readdir($dh)) != false) {
                if (preg_match('/.*\.md/', $file)) {
                    unlink($this->getPico()->getConfig('content_dir') . $file);
                }
            }
        }

        $twig = $this->getPico()->getTwig();

        $twig->addFilter(new Twig_SimpleFilter('getStories', array($this, 'getStories')));
        $twig->addFilter(new Twig_SimpleFilter('getCharEvents', array($this, 'getCharEvents')));
        $twig->addFilter(new Twig_SimpleFilter('getStoryEvents', array($this, 'getStoryEvents')));
        $twig->addFilter(new Twig_SimpleFilter('getCharacters', array($this, 'getCharacters')));

    }
}
