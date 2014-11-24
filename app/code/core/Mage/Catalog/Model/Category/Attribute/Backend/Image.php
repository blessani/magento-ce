<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog category image attribute backend model
 *
 * @category   Mage
 * @package    Mage_Catalog
 */
class Mage_Catalog_Model_Category_Attribute_Backend_Image extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    public function beforeSave($object)
    {
        $value = $object->getData($this->getAttribute()->getName());

        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }

        $path = Mage::getBaseDir('media') . '/catalog/category/';

        try {
            $uploader = new Varien_File_Uploader($this->getAttribute()->getName());
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->save($path);

            $object->setData($this->getAttribute()->getName(), $uploader->getUploadedFileName());
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (Exception $e){
            return;
        }

        /*if( $_FILES['image']['error'] > 0 ) {
            return $this;
        }

        $path = Mage::getBaseDir('media') . '/catalog/category' . '/' . $this->getScope();
        if ('default' != $this->getScope()) {
            $path .= '/' . $this->getScopeId();
        }

        die($path);

        $uploader = new Varien_File_Uploader('image');
        $uploader->save($path);

        $object->setImage($uploader->getUploadedFileName());
        return $this;*/
    }
}