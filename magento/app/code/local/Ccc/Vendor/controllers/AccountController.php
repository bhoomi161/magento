<?php

class Ccc_Vendor_AccountController extends Mage_Core_Controller_Front_Action{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('vendor/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Account'));
        $this->renderLayout();
    }
    public function createAction()
    {
       $this->loadLayout();
       $this->renderLayout();
    
    }
    public function loginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
             $this->_redirect('*/*/');
             return;
        }
        $this->getResponse()->setHeader('Login-Required', 'true');
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
    public function _getModel($path, $arguments = array())
    {
        return Mage::getModel($path, $arguments);
    }
    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }
    protected function _getHelper($path)
    {
        return Mage::helper($path);
    }
    protected function _isVatValidationEnabled($store = null)
    {
        //return  $this->_getHelper('vendor/address')->isVatValidationEnabled($store);
    }
    protected function _welcomeVendor(Ccc_Vendor_Model_Vendor $vendor, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess(
            $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
        );
        // if ($this->_isVatValidationEnabled()) {
        //     // Show corresponding VAT message to Vendor
        //     $configAddressType =  $this->_getHelper('vendor/address')->getTaxCalculationAddressType();
        //     $userPrompt = '';
        //     switch ($configAddressType) {
        //         case Ccc_Vendor_Model_Address_Abstract::TYPE_SHIPPING:
        //             $userPrompt = $this->__('If you are a registered VAT vendor, please click <a href="%s">here</a> to enter you shipping address for proper VAT calculation',
        //                 $this->_getUrl('vendor/address/edit'));
        //             break;
        //         default:
        //             $userPrompt = $this->__('If you are a registered VAT Vendor, please click <a href="%s">here</a> to enter you billing address for proper VAT calculation',
        //                 $this->_getUrl('vendor/address/edit'));
        //     }
            //$this->_getSession()->addSuccess($userPrompt);
        //}

        // $vendor->sendNewAccountEmail(
        //     $isJustConfirmed ? 'confirmed' : 'registered',
        //     '',
        //     Mage::app()->getStore()->getId(),
        //     $this->getRequest()->getPost('password')
        // );

        $successUrl = $this->_getUrl('*/*/index', array('_secure' => true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }
    public function loginPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['username']) && !empty($login['password'])) {
                try {
                   
                    $session->login($login['username'], $login['password']);
                    if ($session->getVendor()->getIsJustConfirmed()) {
                       
                        $this->_welcomeVendor($session->getVendor(), true);
                    }
                } catch (Mage_Core_Exception $e) {
                    echo '<pre>';
                    print_r($e);
                    die;
                    switch ($e->getCode()) {
                        case Ccc_Vendor_Model_Vendor::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $value = $this->_getHelper('vendor')->getEmailConfirmationUrl($login['username']);
                            $message = $this->_getHelper('vendor')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
                            break;
                        case Ccc_Vendor_Model_Vendor::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setUsername($login['username']);
                } catch (Exception $e) {
                    echo '<pre>';
                    print_r($e);
                    die;
                    // Mage::logException($e); // PA DSS violation: this exception log can disclose Vendor password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }
    protected function _loginPostRedirect()
    {
        $session = $this->_getSession();

        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl()) {
            // Set default URL to redirect vendor to
            $session->setBeforeAuthUrl($this->_getHelper('vendor')->getAccountUrl());
            // Redirect vendor to the last page visited after logging in
            if ($session->isLoggedIn()) {

                if (!Mage::getStoreConfigFlag(
                    Ccc_Vendor_Helper_Data::XML_PATH_VENDOR_STARTUP_REDIRECT_TO_DASHBOARD
                )) {
                    $referer = $this->getRequest()->getParam(Ccc_Vendor_Helper_Data::REFERER_QUERY_PARAM_NAME);
                    if ($referer) {
                        // Rebuild referer URL to handle the case when SID was changed
                        $referer = $this->_getModel('core/url')
                            ->getRebuiltUrl( $this->_getHelper('core')->urlDecodeAndEscape($referer));
                            if ($this->_isUrlInternal($referer)) {
                            $session->setBeforeAuthUrl($referer);
                        }
                    }
                } else if ($session->getAfterAuthUrl()) {

                    $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
                }
            } else {
                $session->setBeforeAuthUrl( $this->_getHelper('vendor')->getLoginUrl());
            }
        } else if ($session->getBeforeAuthUrl() ==  $this->_getHelper('vendor')->getLogoutUrl()) {
            $session->setBeforeAuthUrl( $this->_getHelper('vendor')->getDashboardUrl());
        } else {
            if (!$session->getAfterAuthUrl()) {
                $session->setAfterAuthUrl($session->getBeforeAuthUrl());
            }
            if ($session->isLoggedIn()) {
                $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
            }
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }
    protected function _getUrl($url, $params = array())
    {
        return Mage::getUrl($url, $params);
    }
    protected function _getFromRegistry($path)
    {
        return Mage::registry($path);
    }
    protected function _getVendor()
    {
        $vendor = $this->_getFromRegistry('current_vendor');
        if (!$vendor) {
            $vendor = $this->_getModel('vendor/vendor')->setId(null);
        }
        // if ($this->getRequest()->getParam('is_subscribed', false)) {
        //     $vendor->setIsSubscribed(1);
        // }

        return $vendor;
    }
    protected function _getVendorForm($vendor)
    {
        $vendorForm = $this->_getModel('vendor/form');
        $vendorForm->setFormCode('vendorr_account_create');
        $vendorForm->setEntity($vendor);
        return $vendorForm;
    }
    protected function _getVendorErrors($vendor)
    {
        $errors = array();
        $request = $this->getRequest();
        if ($request->getPost('create_address')) {
            $errors = $this->_getErrorsOnVendorAddress($vendor);
        }
        $vendorForm = $this->_getVendorForm($vendor);
        // echo '<pre>';
        // print_r($vendorForm);
        // die;
        $vendorData = $vendorForm->extractData($request);
        $vendorErrors = $vendorForm->validateData($vendorData);
        if ($vendorErrors !== true) {
            $errors = array_merge($vendorErrors, $errors);
        } else {
            $vendorForm->compactData($vendorData);
            $vendor->setPassword($request->getPost('password'));
            $vendor->setPasswordConfirmation($request->getPost('confirmation'));
            $vendorErrors = $vendor->validate();
            if (is_array($vendorErrors)) {
                $errors = array_merge($vendorErrors, $errors);
            }
        }
        return $errors;
    }

    public function createPostAction()
    {
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));

        if (!$this->_validateFormKey()) {
            $this->_redirectError($errUrl);
            return;
        }
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError($errUrl);
            return;
        }

        $vendor = $this->_getVendor();
        $postData = $this->getRequest()->getPost();
        $vendor->setEmail($postData['email'])
        ->setFirstname($postData['firstname'])
        ->setMiddlename($postData['middlename'])
        ->setLastname($postData['lastname'])
        ->setPasswordHash(md5($postData['password']))
        ->save();
        //$this->_dispatchRegisterSuccess($vendor);
        $this->_successProcessRegistration($vendor);

        // try {
        //     $errors = $this->_getVendorErrors($vendor);
            
        //     if (empty($errors)) {
        //         $vendor->cleanPasswordsValidationData();
        //         $vendor->save();
        //         $this->_dispatchRegisterSuccess($vendor);
        //         $this->_successProcessRegistration($vendor);
        //         return;
        //     } else {
        //         $this->_addSessionError($errors);
        //     }
        // } catch (Mage_Core_Exception $e) {
        //     $session->setVendorFormData($this->getRequest()->getPost());
        //     if ($e->getCode() === Ccc_Vendor_Model_Vendor::EXCEPTION_EMAIL_EXISTS) {
        //         $url = $this->_getUrl('vendor/account/forgotpassword');
        //         $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
        //     } else {
        //         $message = $this->_escapeHtml($e->getMessage());
        //     }
        //     $session->addError($message);
        // } catch (Exception $e) {
        //     $session->setVendorFormData($this->getRequest()->getPost());
        //     $session->addException($e, $this->__('Cannot save the vendor.'));
        // }

        //$this->_redirectError($errUrl);
    }
    public function logoutAction()
    {
        $session = $this->_getSession();
        $session->logout()->renewSession();

        if (Mage::getStoreConfigFlag(Ccc_Vendor_Helper_Data::XML_PATH_VENDOR_STARTUP_REDIRECT_TO_DASHBOARD)) {
            $session->setBeforeAuthUrl(Mage::getBaseUrl());
        } else {
            $session->setBeforeAuthUrl($this->_getRefererUrl());
        }
        $this->_redirect('*/*/logoutSuccess');
    }

    public function logoutSuccessAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    protected function _escapeHtml($text)
    {
        return Mage::helper('core')->escapeHtml($text);
    }
    protected function _dispatchRegisterSuccess($vendor)
    {
        Mage::dispatchEvent('vendor_register_success',
            array('account_controller' => $this, 'vendor' => $vendor)
        );
    }
    protected function _successProcessRegistration(Ccc_Vendor_Model_Vendor $vendor)
    {
        $session = $this->_getSession();
        if ($vendor->isConfirmationRequired()) {
            /** @var $app Mage_Core_Model_App */
            $app = $this->_getApp();
            /** @var $store  Mage_Core_Model_Store*/
            $store = $app->getStore();
            $vendor->sendNewAccountEmail(
                'confirmation',
                $session->getBeforeAuthUrl(),
                $store->getId(),
                $this->getRequest()->getPost('password')
            );
            $vendorHelper = $this->_getHelper('vendor');
            $session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.',
                $vendorHelper->getEmailConfirmationUrl($vendor->getEmail())));
            $url = $this->_getUrl('*/*/index', array('_secure' => true));
        } else {
            $session->setVendorAsLoggedIn($vendor);
            $url = $this->_welcomeVendor($vendor);
        }
        $this->_redirectSuccess($url);
        return $this;
    }
    protected function _addSessionError($errors)
    {
        $session = $this->_getSession();
        $session->setVendorFormData($this->getRequest()->getPost());
        if (is_array($errors)) {
            foreach ($errors as $errorMessage) {
             $session->addError($this->_escapeHtml($errorMessage));
        }
        } else {
             $session->addError($this->__('Invalid vendor data'));
        }
    }
    public function editAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->_initLayoutMessages('catalog/session');

        $block = $this->getLayout()->getBlock('vendor');
        if ($block) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $data = $this->_getSession()->getVendorFormData(true);
        $vendor = $this->_getSession()->getVendor();
        if (!empty($data)) {
            $vendor->addData($data);
        }
        if ($this->getRequest()->getParam('changepass') == 1) {
            $vendor->setChangePassword(1);
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Account Information'));
        $this->getLayout()->getBlock('messages')->setEscapeMessageFlag(true);
        $this->renderLayout();
    }

    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            
            $vendor = $this->_getSession()->getVendor();
            
            $vendor->setOldEmail($vendor->getEmail());
            $vendorForm = $this->_getModel('vendor/form');
            $vendorForm->setFormCode('vendor_account_edit')
                ->setEntity($vendor);

            $vendorData = $vendorForm->extractData($this->getRequest());
            $errors = array();
            $vendorErrors = $vendorForm->validateData($vendorData);
            if ($vendorErrors !== true) {
                $errors = array_merge($vendorErrors, $errors);
            } else {
                $vendorForm->compactData($vendorData);
                $errors = array();

                if (!$vendor->validatePassword($this->getRequest()->getPost('current_password'))) {
                    $errors[] = $this->__('Invalid current password');
                }

                // If email change was requested then set flag
                $isChangeEmail = ($vendor->getOldEmail() != $vendor->getEmail()) ? true : false;
                $vendor->setIsChangeEmail($isChangeEmail);

                // If password change was requested then add it to common validation scheme
                $vendor->setIsChangePassword($this->getRequest()->getParam('change_password'));

                if ($vendor->getIsChangePassword()) {
                    $newPass    = $this->getRequest()->getPost('password');
                    $confPass   = $this->getRequest()->getPost('confirmation');

                    if (strlen($newPass)) {
                        /**
                         * Set entered password and its confirmation - they
                         * will be validated later to match each other and be of right length
                         */
                        $vendor->setPassword($newPass);
                        $vendor->setPasswordConfirmation($confPass);
                    } else {
                        $errors[] = $this->__('New password field cannot be empty.');
                    }
                }

                // Validate account and compose list of errors if any
                $vendorErrors = $vendor->validate();
                if (is_array($vendorErrors)) {
                    $errors = array_merge($errors, $vendorErrors);
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setVendorFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }

            try {
                $vendor->cleanPasswordsValidationData();

                // Reset all password reset tokens if all data was sufficient and correct on email change
                if ($vendor->getIsChangeEmail()) {
                    $vendor->setRpToken(null);
                    $vendor->setRpTokenCreatedAt(null);
                }
                $postData = $this->getRequest()->getPost();
               
                $vendor->setFirstname($postData['firstname'])
                ->setMiddlename($postData['middlename'])
                ->setLastname($postData['lastname']);
                $vendor->save();
                $this->_getSession()->setVendor($vendor)
                    ->addSuccess($this->__('The account information has been saved.'));

                if ($vendor->getIsChangeEmail() || $vendor->getIsChangePassword()) {
                    $vendor->sendChangedPasswordOrEmail();
                }
                $this->_redirect('vendor/account');
                return;
            } catch (Mage_Core_Exception $e) {
                echo '<pre>';
                print_r($e);
                die;
                $this->_getSession()->setVendorFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                echo '<pre>';
                print_r($e);
                die;
                $this->_getSession()->setVendorFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the vendor.'));
            }
        }

        $this->_redirect('*/*/edit');
    }

}