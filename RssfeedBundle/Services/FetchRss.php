<?php
namespace Assetment\RssfeedBundle\Services;

/**
 * Description of FetchRss
 *
 * @author Thao Nguyen
 */
class FetchRss {
   
    public function feedNews($rssLink) {
        $xml = new \XMLReader();
        $xml->open($rssLink);
        $tree = $this->xmlTree($xml, "root");
        $xml->close();

        return $tree;
    }

    private function addAttribute($xml) {
        $node = array();
        $node['tag'] = $xml->name;
        if ($xml->hasAttributes) {
            $attributes = array();
            while ($xml->moveToNextAttribute()) {
                $attributes[$xml->name] = $xml->value;
            }
            $node['attr'] = $attributes;
        }

        if (!$xml->isEmptyElement) {
            $childs = $this->xmlTree($xml);
            $node['childs'] = $childs;
        }
        return $node;
    }

    private function xmlTree($xml) {
        $tree = null;

        while ($xml->read()) {
            switch ($xml->nodeType) {
                case \XMLReader::ELEMENT :
                    $node = $this->addAttribute($xml);
                    $tree[] = $node;
                    break;
                case \XMLReader::TEXT :
                    $node['text'] = $xml->value;
                    $tree[] = $node;
                    break;
                case \XMLReader::END_ELEMENT :
                    return $tree;
            }
                     
        }

        return $tree;
    }

}
