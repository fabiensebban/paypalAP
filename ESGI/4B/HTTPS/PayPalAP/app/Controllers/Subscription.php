<?php
/**
 * Subcription controller
 *
 * @author Scarpa Team
 * @version 3.0
 */

namespace App\Controllers;

use Core\View;
use Core\Controller;

use Language;
use Router;
use Session;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Subscription extends Controller
{
    protected $code;

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();

        // Setup the Controller's Language code.
        $this->code = Language::code();
        $this->postdata = null;
        $this->errors = null;
        $this->isErrors = false;
        $this->_users = new \App\Models\User();
    }

    /**
     * Define Index page title and load template files.
     */
    public function index()
    {
        $data['title'] = "Subscribe - Scarpa Pay";
        $data['errors'] = $this->errors;
        $data['isErrors'] = $this->isErrors; 
        
        if (isset($this->postdata))
            $data['post'] = $this->postdata;

        View::renderTemplate('header', $data);
        View::render('Subscription/index', $data);
        View::renderTemplate('footer', $data);
    }

    public function subscribe()
    {
        
        if(isset($_POST['userID']))
        {
            $USER_ID = $_POST['userID'];
            $APP_ID = $_POST['appID'];
            $API_PASS = $_POST['apiPass'];
            $API_USER =$_POST['apiUser'];
            $API_SIG =$_POST['apiSig'];
            $errors = array();
            $isError = false;
            
            /****DATA VALIDATION****/
            
            if(empty($USER_ID)){
				array_push($errors, 'Please enter the User ID');
				$isError = true;
			}
			if(empty($APP_ID)){
				array_push($errors, 'Please enter the APP ID');
				$isError = true;
			}
			if(empty($API_PASS)){
				array_push($errors, 'Please enter the API PASS');
				$isError = true;
			}
			if(empty($API_USER)){
				array_push($errors, 'Please enter the API USER');
				$isError = true;
			}
			if(empty($API_SIG)){
				array_push($errors, 'Please enter the API SIGNATURE');
				$isError = true;
			}
		    
		    $postdata = array(
					'userID' => $USER_ID,
					'appID' => $APP_ID,
					'apiPass' => $API_PASS,
					'apiUser' => $API_USER,
					'apiSig' => $API_SIG
				);
				
			if($isError){
				$this->postdata = $postdata;
				$this->errors = $errors;
				$this->isErrors = $isError;
				
				$this->index();
			}
			else {
			    $this->_users->insert($postdata);
			    
			    $data['title'] = "Congrats - Scarpa Pay";
			    $data['userId'] = $USER_ID;
			    
	            View::renderTemplate('header', $data);
                View::render('Subscription/subscribe', $data);
                View::renderTemplate('footer', $data);
			}
        }
        
    }

    /**
     * Return a translated string.
     * @return string
     */
    protected function trans($str, $code = LANGUAGE_CODE)
    {
        return $this->language->get($str, $this->code);
    }
}
