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
    
    function __construct($file = null, $folder = null) {
        $this->objects = array();
        $this->nestedtemplates = array();
        $this->name = $file;
        $this->folder = $folder;
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
            if (is_null($template->getAlias())) {
                $this->setObject($template->getName(),$template->getContent());
            } else {
                $this->setObject($template->getAlias(),$template->getContent());
            }
        }
        if (count($this->objects) > 0) {
            extract($this->objects);
        }
        if ($this->getContainer() != null) {
            $_view = $this->getContainer();
        }
//         if (defined(JPATH_COMPONENT)) {
//             if ($this->folder == null) {
//                 include(JPATH_COMPONENT  . DS. 'templates'. DS . $this->getName() . '.php');
//             } else {
//                 include($this->getFolder() . DS . $this->getName() . '.php');
//             }
//         } else {
//             
        $fn = $this->getFolder() . $this->getName() . '.php';
        if (file_exists($fn)) {
            include($fn);
        } else {
           throw new \Exception("Template File Not Found [" . $fn . "]");
        }
               
    }
    
    
    function getContent() {
        ob_start ();
        $this->parse();
        $contenHTML = ob_get_contents ();
        ob_end_clean ();
        return $contenHTML;
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