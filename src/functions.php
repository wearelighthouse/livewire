<?php

    function live($fieldName, $structure)
    {

        $content = '';

        // is it empty?
        if(empty($fieldName)) {
            $content .= '<span data-forcefeed="'.$structure.'"></span>';
        }

        // add the contents of that field before the closing tag
        $content .= $fieldName;

        return $content;
    }

?>
