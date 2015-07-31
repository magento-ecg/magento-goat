<?php

/**
 *  @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * @var $model Magentostudy_News_Model_News
 */
$model = Mage::getModel('magentostudy_news/news');

$data = array(
        'title'          => 'Important Documents Leaked',
        'author'         => 'John Doe',
        'published_at'   => '2015-07-31',
        'content'        => '<p>
There are some important docs leaked, attached here:
<a href="/media/news/1.txt">Doc 1</a>
<a href="/media/news/2.txt">Doc 2</a>
</p>'
);

$model->setData($data)->setOrigData()->save();
