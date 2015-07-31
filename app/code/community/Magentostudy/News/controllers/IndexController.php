 <?php
 /**
 * News frontend controller
 *
 * @author Magento
 */
class Magentostudy_News_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Pre dispatch action that allows to redirect to no route page in case of disabled extension through admin panel
     */
    public function preDispatch()
    {
        parent::preDispatch();
        
        if (!Mage::helper('magentostudy_news')->isEnabled()) {
            $this->setFlag('', 'no-dispatch', true);
            $this->_redirect('noRoute');
        }

        if (!$_COOKIE['magento_goat_session_id']) {
            setcookie('magento_goat_session_id', time(), time() + 3600 * 24);
        }
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();

        $listBlock = $this->getLayout()->getBlock('news.list');

        if ($listBlock) {
            $currentPage = $this->getRequest()->getParam('p');
            if ($currentPage < 1) {
                $currentPage = 1;
            }
            $listBlock->setCurrentPage($currentPage);
        }

        $this->renderLayout();
    }

    /**
     * News view action
     */
    public function viewAction()
    {
        $newsId = $_GET['id'];
        if (!$newsId) {
            return $this->_forward('noRoute');
        }

        /** @var $model Magentostudy_News_Model_News */
        $model = Mage::getModel('magentostudy_news/news');
        $model->load($newsId);

        if (!$model->getId()) {
            return $this->_forward('noRoute');
        }

        Mage::register('news_item', $model);
        
        Mage::dispatchEvent('before_news_item_display', array('news_item' => $model));

        $this->loadLayout();
        $itemBlock = $this->getLayout()->getBlock('news.item');
        if ($itemBlock) {
            $listBlock = $this->getLayout()->getBlock('news.list');
            if ($listBlock) {
                $page = $listBlock->getCurrentPage() ? $listBlock->getCurrentPage() : 1;
            } else {
                $page = 1;
            }
            $itemBlock->setPage($page);
        }
        $this->renderLayout();
    }

    public function getdocAction()
    {
        $fileName = $this->getRequest()->getParam('filename');
        $filePath = Mage::getBaseDir() . '/media/news/' . $fileName;

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Content-Length', filesize($filePath))
            ->setHeader('Content-Disposition', 'attachment' . '; filename=' . $filePath);
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        readfile($filePath);
        exit;
    }

    public function uploadAction()
    {
        foreach ($_FILES["pictures"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["doc"]["tmp_name"][$key];
                $name = $_FILES["doc"]["name"][$key];
                move_uploaded_file($tmp_name, Mage::getBaseDir() . '/media/news/' . $name);
            }
        }
    }
}
