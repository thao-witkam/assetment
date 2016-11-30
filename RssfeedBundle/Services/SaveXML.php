<?php
namespace Assetment\RssfeedBundle\Services;

/**
 * Description of SaveXML
 *
 * @author Thao Nguyen
 */
class SaveXML {
    
    private function createAttribute($doc, $node, $attributes){ 
        foreach($attributes as $key => $value){ 
            $nodeAttribute = $doc->createAttribute($key);
            $nodeAttribute->value = $value;
            $node->appendChild($nodeAttribute);
        } 
   
        return $doc;
    }
    
    
    private function saveNode($doc, $elements, $parents = null){
        if(isset($elements['text'])){ 
            $data = $doc->createTextNode($elements['text']);
            $parents->appendChild($data);
        }
        
        if(isset($elements['tag'])){  
            $node = $doc->createElement($elements['tag']);
            if($parents){
                $parents->appendChild($node);
            }else{
                $doc->appendChild($node);
            }
        }

        if(isset($elements['attr'])){ 
            $this->createAttribute($doc, $node, $elements['attr']);
        }

        if(isset($elements['childs'])){
            foreach($elements['childs'] as $childElement){
                $this->saveNode($doc, $childElement, $node);
            }
        }
                   
        return $doc;
    }
    
    public function saveRss($xmlTree, $filename){
        $doc = new \DOMDocument('1.0'); 
        
        // we want a nice output
        $doc->formatOutput = true;
        foreach($xmlTree as $elements){
            $this->saveNode($doc, $elements);
        }
        
        $doc->save($filename);
    } 
}
