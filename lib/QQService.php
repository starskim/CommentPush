<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */

require_once 'Contract/ServiceInterface.php';

class QQService implements ServiceInterface
{
    public function __handler($active, $comment, $plugin)
    {
        $qqApiUrl = $plugin->qqApiUrl;
        $receiveQq = $plugin->receiveQq;

        if (empty($qqApiUrl) || empty($receiveQq)) return false;

        $title = $active->title;
        $author = $comment['author'];
        $link = $active->permalink;
        $context = $comment['text'];

        $template = '标题：' . $title . PHP_EOL
            . '评论人：' . $author . PHP_EOL
            . '评论内容：' . $context . PHP_EOL
            . '链接：' . $link;

        $params = http_build_query([
            'qq' => $receiveQq,
            'msg' => $template
        ]);

        $options = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $params
            )
        );

        $context = stream_context_create($options);
        return file_get_contents($qqApiUrl, false, $context);
    }
}