<?php

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

function human_readable_filesize($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' gb';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' mb';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' kb';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function extension_icon($ext) {
    switch ($ext) {
        case 'jpg':
        case 'png':
        case 'bmp':
        case 'svg':
            $ext = 'image';
            break;

        case 'mp4':
        case 'ogg':
        case 'avi':
        case 'webm':
            $ext = 'video';
            break;

        case 'mp3':
        case 'mp2':
        case 'wav':
            $ext = 'audio';
            break;

        case 'fla':
            $ext = 'flash';
            break;

        case 'ppt':
        case 'pptx':
            $ext = 'ppt';
            break;

        case 'xls':
        case 'xlsx':
            $ext = 'excel';
            break;

        case 'doc':
        case 'docx':
            $ext = 'word';
            break;

        case 'pdf':
        case 'psd':
        case 'ai':
        case 'txt':
        case 'html':
        case 'xml':
        case 'zip':
        case 'link':
        case 'exe':
        case 'rtf':
            $ext = $ext;
            break;

        default:
            $ext = 'unknown';
            break;
    }

    return $ext;
}
