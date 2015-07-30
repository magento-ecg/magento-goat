<?php
/**
 * News installation script
 *
 * @author Magento
 */

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$installer->run('ALTER TABLE magentostudy_news ADD visitor_ip varchar(40) NULL');
