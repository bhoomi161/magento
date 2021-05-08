<?php

class Ccc_Custom_Helper_Image extends Mage_Core_Helper_Abstract
{
    const XML_NODE_CUSTOM_BASE_IMAGE_WIDTH = 'custom/custom_image/base_width';
    const XML_NODE_CUSTOM_SMALL_IMAGE_WIDTH = 'custom/custom_image/small_width';
    const XML_NODE_CUSTOM_MAX_DIMENSION = 'custom/custom_image/max_dimension';

    protected $_model;

    protected $_scheduleResize = false;

    protected $_scheduleRotate = false;

    protected $_angle;

    protected $_watermark;

    protected $_watermarkPosition;

    protected $_watermarkSize;

    protected $_watermarkImageOpacity;

    protected $_custom;

    protected $_imageFile;

    protected $_placeholder;

     protected function _reset()
    {
        $this->_model = null;
        $this->_scheduleResize = false;
        $this->_scheduleRotate = false;
        $this->_angle = null;
        $this->_watermark = null;
        $this->_watermarkPosition = null;
        $this->_watermarkSize = null;
        $this->_watermarkImageOpacity = null;
        $this->_custom = null;
        $this->_imageFile = null;
        return $this;
    }
    public function init(Ccc_Custom_Model_Custom $custom, $attributeName, $imageFile=null)
    {
        $this->_reset();
        $this->_setModel(Mage::getModel('custom/custom_image'));
        $this->_getModel()->setDestinationSubdir($attributeName);
        $this->setCustom($custom);

        $this->setWatermark(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_image")
        );
        $this->setWatermarkImageOpacity(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_imageOpacity")
        );
        $this->setWatermarkPosition(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_position")
        );
        $this->setWatermarkSize(
            Mage::getStoreConfig("design/watermark/{$this->_getModel()->getDestinationSubdir()}_size")
        );

        if ($imageFile) {
            $this->setImageFile($imageFile);
        } else {
            // add for work original size
            $this->_getModel()->setBaseFile($this->getCustom()->getData($this->_getModel()->getDestinationSubdir()));
        }
        return $this;
    }

    
    public function resize($width, $height = null)
    {
        $this->_getModel()->setWidth($width)->setHeight($height);
        $this->_scheduleResize = true;
        return $this;
    }

    public function setQuality($quality)
    {
        $this->_getModel()->setQuality($quality);
        return $this;
    }

    public function keepAspectRatio($flag)
    {
        $this->_getModel()->setKeepAspectRatio($flag);
        return $this;
    }

    
    public function keepFrame($flag, $position = array('center', 'middle'))
    {
        $this->_getModel()->setKeepFrame($flag);
        return $this;
    }

    
    public function keepTransparency($flag, $alphaOpacity = null)
    {
        $this->_getModel()->setKeepTransparency($flag);
        return $this;
    }

   
    public function constrainOnly($flag)
    {
        $this->_getModel()->setConstrainOnly($flag);
        return $this;
    }
    public function backgroundColor($colorRGB)
    {
        // assume that 3 params were given instead of array
        if (!is_array($colorRGB)) {
            $colorRGB = func_get_args();
        }
        $this->_getModel()->setBackgroundColor($colorRGB);
        return $this;
    }

   
    public function rotate($angle)
    {
        $this->setAngle($angle);
        $this->_getModel()->setAngle($angle);
        $this->_scheduleRotate = true;
        return $this;
    }

    public function watermark($fileName, $position, $size=null, $imageOpacity=null)
    {
        $this->setWatermark($fileName)
            ->setWatermarkPosition($position)
            ->setWatermarkSize($size)
            ->setWatermarkImageOpacity($imageOpacity);
        return $this;
    }

    public function placeholder($fileName)
    {
        $this->_placeholder = $fileName;
    }

  
    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $attr = $this->_getModel()->getDestinationSubdir();
            $this->_placeholder = 'images/custom/custom/placeholder/'.$attr.'.jpg';
        }
        return $this->_placeholder;
    }

    public function __toString()
    {
        try {
            $model = $this->_getModel();

            if ($this->getImageFile()) {
                $model->setBaseFile($this->getImageFile());
            } else {
                $model->setBaseFile($this->getCustom()->getData($model->getDestinationSubdir()));
            }

            if ($model->isCached()) {
                return $model->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $model->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $model->resize();
                }

                if ($this->getWatermark()) {
                    $model->setWatermark($this->getWatermark());
                }

                $url = $model->saveFile()->getUrl();
            }
        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }
        return $url;
    }

    protected function _setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    protected function _getModel()
    {
        return $this->_model;
    }

    protected function setAngle($angle)
    {
        $this->_angle = $angle;
        return $this;
    }
    protected function getAngle()
    {
        return $this->_angle;
    }

    protected function setWatermark($watermark)
    {
        $this->_watermark = $watermark;
        $this->_getModel()->setWatermarkFile($watermark);
        return $this;
    }

   
    protected function getWatermark()
    {
        return $this->_watermark;
    }

    
    protected function setWatermarkPosition($position)
    {
        $this->_watermarkPosition = $position;
        $this->_getModel()->setWatermarkPosition($position);
        return $this;
    }

   
    protected function getWatermarkPosition()
    {
        return $this->_watermarkPosition;
    }

   
    public function setWatermarkSize($size)
    {
        $this->_watermarkSize = $size;
        $this->_getModel()->setWatermarkSize($this->parseSize($size));
        return $this;
    }

   
    protected function getWatermarkSize()
    {
        return $this->_watermarkSize;
    }

    public function setWatermarkImageOpacity($imageOpacity)
    {
        $this->_watermarkImageOpacity = $imageOpacity;
        $this->_getModel()->setWatermarkImageOpacity($imageOpacity);
        return $this;
    }

   
    protected function getWatermarkImageOpacity()
    {
        if ($this->_watermarkImageOpacity) {
            return $this->_watermarkImageOpacity;
        }

        return $this->_getModel()->getWatermarkImageOpacity();
    }

   
    protected function setCustom($custom)
    {
        $this->_custom = $custom;
        return $this;
    }

    
    protected function getCustom()
    {
        return $this->_custom;
    }

    
    protected function setImageFile($file)
    {
        $this->_imageFile = $file;
        return $this;
    }

    protected function getImageFile()
    {
        return $this->_imageFile;
    }


    protected function parseSize($string)
    {
        $size = explode('x', strtolower($string));
        if (sizeof($size) == 2) {
            return array(
                'width' => ($size[0] > 0) ? $size[0] : null,
                'heigth' => ($size[1] > 0) ? $size[1] : null,
            );
        }
        return false;
    }

    public function getOriginalWidth()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalWidth();
    }

    public function getOriginalHeigh()
    {
        return $this->getOriginalHeight();
    }

   
    public function getOriginalHeight()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalHeight();
    }

   
    public function getOriginalSizeArray()
    {
        return array(
            $this->getOriginalWidth(),
            $this->getOriginalHeight()
        );
    }

    public function validateUploadFile($filePath) {
        $maxDimension = Mage::getStoreConfig(self::XML_NODE_CUSTOM_MAX_DIMENSION);
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            Mage::throwException($this->__('Disallowed file type.'));
        }

        if ($imageInfo[0] > $maxDimension || $imageInfo[1] > $maxDimension) {
            Mage::throwException($this->__('Disalollowed file format.'));
        }

        $_processor = new Varien_Image($filePath);
        return $_processor->getMimeType() !== null;
    }

}
