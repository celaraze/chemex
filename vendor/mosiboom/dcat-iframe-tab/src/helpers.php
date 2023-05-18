<?php
if (!function_exists('mosi_iframeTabBodyClass')){
    function mosi_iframeTabBodyClass($body_class){
        if (!empty($body_class)) {
            if (!is_array($body_class)) {
                $body_class = explode(' ', $body_class);
            }
            $iframe_body_class = array_reduce($body_class, function ($result, $item) {
                return $result . ' iframe-tab-' . $item;
            });
        } else {
            $iframe_body_class='';
        }
        return $iframe_body_class;
    }
}
