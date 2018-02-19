<?php

namespace common\widgets;


use yii\base\Widget;

class CommentWidget extends Widget
{
    public $dataProvider;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();
    }

    private function outTree(array $tree)
    {
        $comments = "";

        foreach ($tree as $node) {

            $comments .= '<ul>';
            $comments .= '<li>' . $node['object']->getContent() . '</li>';

            if(isset($node['child'])) {
                $comments .= $this->outTree($node['child']);
            }

            $comments .= '</ul>';
        }

        return $comments;
    }

    /**
     * @param array $comments
     * @return array
     */
    private function buildTree(array $comments)
    {
        $keys = [];

        foreach ($comments as $key => $value) {
            $keys[] = $value->getId();

            $comments[$key] = [];
            $comments[$key]['object'] = $value;
        }

        $comments = array_combine($keys, $comments);

        foreach ($comments as $key => $line) {
            if($line['object']->getCommentId() !== null) {
                $comments[$line['object']->getCommentId()]['child'][$line['object']->getId()] = & $comments[$key];
            }
        }

        foreach ($comments as $key => $value) {
            if($value['object']->getCommentId() !== null) {
                unset($comments[$key]);
            }
        }

        return $comments;
    }
}