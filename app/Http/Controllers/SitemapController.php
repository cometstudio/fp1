<?php

namespace App\Http\Controllers;

use SimpleXMLElement;

class SitemapController extends Controller
{

    /*
     * @var $xml SimpleXMLElement
     */
    private $xml;
    private $domain = '';

    public function __construct()
    {
        $this->domain = env('APP_URL');
    }

    public function get()
    {
        $settings = view()->shared('settings');

        $sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?>
                            <urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd"></urlset>';

        $this->xml = new SimpleXMLElement($sitemapHeader);

        $this->node(url()->route('index'), $this->getDateFromTime((!empty($settings->updated_at->timestamp) ? $settings->updated_at->timestamp : $settings->created_at->timestamp)), 1);
        $this->node(url()->route('videos:index'), $this->getDateFromTime((!empty($settings->updated_at->timestamp) ? $settings->updated_at->timestamp : $settings->created_at->timestamp)), 0.8);
        $this->node(url()->route('gallery:index'), $this->getDateFromTime((!empty($settings->updated_at->timestamp) ? $settings->updated_at->timestamp : $settings->created_at->timestamp)), 0.8);
        $this->node(url()->route('calendar:index'), $this->getDateFromTime((!empty($settings->updated_at->timestamp) ? $settings->updated_at->timestamp : $settings->created_at->timestamp)), 0.5);
        $this->node(url()->route('supplements:index'), $this->getDateFromTime((!empty($settings->updated_at->timestamp) ? $settings->updated_at->timestamp : $settings->created_at->timestamp)), 0.5);

        return response($this->xml->asXML(), 200, $this->headers());
    }

    private function headers()
    {
        return [
            'Content-Type' => 'application/xml'
        ];
    }

    private function getDateFromTime($time = 0)
    {
        return date('Y-m-d', $time);
    }

    private function node($loc, $lastmod = '', $priority = 0, $changefreq = '')
    {
        $node = $this->xml->addChild('url');

        $node->addChild('loc', $loc);

        if(!empty($lastmod)) $node->addChild('lastmod', $lastmod);
        if(!empty($changefreq)) $node->addChild('changefreq', $changefreq);
        if(!empty($priority)) $node->addChild('priority', $priority);
    }
}
