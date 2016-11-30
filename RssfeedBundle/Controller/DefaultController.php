<?php

namespace Assetment\RssfeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    private $rssLink = 'http://rss.msnbc.msn.com/id/3032091/device/rss/rss.xml';

    public function indexAction() {
        $this->fetchRssAction();
        return $this->render('AssetmentRssfeedBundle:Default:index.html.twig');
    }

    public function rssReaderAction($filename = "./rss_reader.xml") {
        $xmlDoc = new \DOMDocument();
        $xmlDoc->load($filename);
        $items = $xmlDoc->getElementsByTagName('item');
        $rssItems = array();
        for($i=0; $i<= $this->getParameter('max_row'); $i++){      
            if(!is_object($items->item($i))){
                break;
            }
            
            foreach($items->item($i)->childNodes as $node){ //var_dump($node->nodeName);
                if($node->nodeName=='media:content'){
                    $rssItems[$i] = $this->getMedia($node, $rssItems[$i]);
                }else{
                    $rssItems[$i][$node->nodeName] = $node->nodeValue; 
                }
            }
            
        }
//var_dump($rssItems); die;
        return $this->render('AssetmentRssfeedBundle:Default:list.html.twig', array(
                    'items' => $rssItems,
        ));
    }
    
    private function getMedia($node, $rssItems){
        $rssItems['media']['url'] = $node->getAttribute('url');
        $rssItems['media']['type'] = $node->getAttribute('medium');
        foreach($node->childNodes as $item){
            $rssItems['media'][$item->nodeName] = $item->nodeValue;
            
        }
        return $rssItems;
    }

    public function fetchRssAction() {
        $feed = $this->get('ass_rssfeed.fetch');
        $saveXML = $this->get('ass_rssfeed.saveXML');
        $saveXML->saveRss($feed->feedNews($this->rssLink), "./rss_reader.xml");
        return;
    }

}
