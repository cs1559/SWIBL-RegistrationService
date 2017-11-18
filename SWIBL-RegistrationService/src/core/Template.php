<?php
namespace swibl\core;

use Exception;

class Template  {
    
    var $objects = null;
    var $name = null;
    var $alias = null;
    var $folder = null;
    var $nestedtemplates = null;
    var $_container = null;
    var $tags = null;
    
    function __construct($file = null, $folder = null) {
        $this->objects = array();
        $this->nestedtemplates = array();
        $this->name = $file;
        $this->folder = $folder;
        $this->tags = array();
    }
    
    
    function setObject($key,$obj) {
        if ($obj instanceof Template) {
            $this->addTemplate($obj);
            return;
        }
        $this->objects[$key]=$obj;
    }
    function getObject($key) {
        return $this->objects[$key];
    }
    function getName() {
        return $this->name;
    }
    function getAlias() {
        return $this->alias;
    }
    function setAlias($aliasname) {
        $this->alias = $aliasname;
    }
    function setFolder($folder) {
        $this->folder = $folder;
        if (self::hasNestedTemplates()) {
            foreach ($this->nestedtemplates as $template) {
                $template->setFolder($folder);
            }
        }
    }
    function getFolder() {
        return $this->folder;
    }
    function parse() {
        foreach ($this->nestedtemplates as $template) {
            if ($template instanceof Template) {
                if (is_null($template->getAlias())) {
                    $this->setObject($template->getName(),$template->getContent());
                } else {
                    $this->setObject($template->getAlias(),$template->getContent());
                }
            }
        }
        if (count($this->objects) > 0) {
            extract($this->objects);
        }
         
        $fn = $this->getFolder() . $this->getName() . '.htm';
        if (file_exists($fn)) {
            include($fn);
        } else {
           throw new \Exception("Template File Not Found [" . $fn . "]");
        }
               
    }
    
    function setTag($name, $value) {
        $this->tags[$name] = $value;    
    }
    
    function renderVar($var) {
        if (isset($this->objects[$var])) {
            echo $this->objects[$var];
        } else {
            echo "{" . $var . " missing}";
        }
    }
    function getContent() {
        ob_start ();
        $this->parse();
        $contenHTML = ob_get_contents ();
        ob_end_clean ();
        return $contenHTML;
    }
    function render() {
        $content = $this->getContent();
        foreach ($this->tags as $tag => $value) {
            $searchTag = "/{tag:" . $tag . "}/";
            $content = preg_replace($searchTag, $value, $content);
        }
        return $content;
    }
    
    function addTemplate(Template $tmpl) {
        $this->nestedtemplates[] = $tmpl;
    }
    
    /**
     * This function returns a boolean if the template has any nested templates.
     *
     * @return boolean
     */
    function hasNestedTemplates() {
        if (count($this->nestedtemplates) > 0) {
            return true;
        }
        return false;
    }
    
    function getNestedTemplates() {
        return $this->nestedtemplates;
    }
    
//     function setContainer(fsView $input) {
//         $this->_container = $input;
//     }
//     function getContainer() {
//         return $this->_container;
//     }

    function __toString() {
        try {
            return $this->getContent();
        } catch (Exception $e) {
            $html =  "ERROR:  UNABLE TO PARSE TEMPLATE [" . $this->getFolder() . "]<br/>";
            $html .= $e->getMessage();
            return $html;
        }
    }
}
?>