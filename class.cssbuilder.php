<?php

class CssBuilder
{
    const path = "css/";

    public static function build($filenames)
    {
        $cssData = '<style>';
        foreach ($filenames as $file) {
            $cssFileName = self::path . $file . '.css';
            $fileHandle = fopen($cssFileName, 'r');
            $cssData .= "\n" . fread($fileHandle, filesize($cssFileName));
            fclose($fileHandle);
        }

        $cssData .= '</style>';
        echo $cssData;
    }
}

?>