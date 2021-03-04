<?php

class Andy_RegisterEmail_ControllerPublic_Register extends XFCP_Andy_RegisterEmail_ControllerPublic_Register
{
    public function actionRegister()
    {
        $parent = parent::actionRegister();	
		
		if (!isset($errors))
		{
			// get userId
			$userId = XenForo_Visitor::getUserId();
			
			// must have userId
			if ($userId > 0)
			{				
				// get visitor data
				$visitor = XenForo_Visitor::getInstance();					
				
				//########################################
				// customFields
				//########################################
				
				// define variable
				$fields = '';
				
				// get customFields
				$visitor['customFields'];
				
				// get options from Admin CP -> Options -> Register Email -> Custom User Fields    
				$fields = XenForo_Application::get('options')->registerEmailCustomUserFields;					
				
				// check if set
				if ($fields != '')
				{
					// get custom userfields data
					$customFieldsArray = XenForo_Application::arrayFilterKeys($visitor['customFields'], explode(',', $fields));
				}
				
				//########################################
				// ip
				//########################################
				
				// define variables
				$ipAddress = '';	
				$location = '';							
				
				// get ip address
				$ipAddress = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0);
				
				// get options from Admin CP -> Options -> Register Email -> Location    
				$registerEmailLocation = XenForo_Application::get('options')->registerEmailLocation;							
					
				if ($registerEmailLocation)
				{
					// get url
					$url = 'http://www.geoplugin.net/json.gp?ip=' . $ipAddress;
					
					// get ctx
					$ctx = stream_context_create(array(
						'http' => array(
							'timeout' => 3
							)
						)
					); 					
					
					// get location from IP using geoplugin.com
					$json = @file_get_contents($url,0,$ctx);
					
					if ($json)
					{
						// convert json to array
						$obj = json_decode($json);
						
						// get location data
						$country = @$obj->{'geoplugin_countryName'}; 
						$region = @$obj->{'geoplugin_region'}; 
						$city = @$obj->{'geoplugin_city'}; 
	
						// define location
						$location = $country . ', ' . $region . ', ' . $city;
					}
					else 
					{
						$location = '';
					}	
				}
				
				//########################################
				// prepare message part 1
				//########################################
				
				// get options from Admin CP -> Options -> Register Email -> Language ID
				$languageId = XenForo_Application::get('options')->registerEmailLanguageId;			
				
				// set languageId
				XenForo_Phrase::setLanguageId($languageId);				
				
				// message1
				$emailMessage1 = new XenForo_Phrase('registeremail_user_name') . ' ' . $visitor['username'] . '
				' . '<br /><br />' . '
				' . new XenForo_Phrase('registeremail_user_id') . ' ' . $userId . '<br /><br />' . '
				' . new XenForo_Phrase('registeremail_email') . ' ' . '<a href="mailto:' . $visitor['email'] . '">' . $visitor['email'] . '</a><br /><br />';

				//########################################
				// prepare message part 2
				//########################################
				
				// define variable
				$emailMessage2 = '';
				
				// set variable
				$requireLocation = false;
				
				// get options from Admin CP -> Options -> User Registration -> Require Location 
				if (!empty(XenForo_Application::get('options')->registrationSetup['requireLocation']))
				{
					$requireLocation = XenForo_Application::get('options')->registrationSetup['requireLocation'];
				}

				if ($requireLocation)
				{
					$emailMessage2 = new XenForo_Phrase('registeremail_location_entered') . ' ' . $visitor['location'] . '<br /><br />';
				}

				//########################################
				// prepare message part 3
				//########################################
				
				$emailMessage3 = '';
				
				// skip custom user fields if empty
				if ($fields != '')
				{
					$fieldName = explode(",",$fields);
					
					// count number of custom userfields to display
					$count = count($fieldName);			
				
					// message3
					for ($i=0; $i<$count; $i++) 
					{
						$emailMessage3 = $emailMessage3 . $fieldName[$i] . ': ' . $customFieldsArray[$fieldName[$i]] . '<br /><br />';			
					}
				}
				
				//########################################
				// prepare message part 4
				//########################################
				
				// get webRoot
				$webRoot = XenForo_Link::buildPublicLink('full:index');
		
				// remove index.php if not using full friendly url's
				$replace_src = 'index.php';
				$replace_str = '';
				$text = $webRoot;					
				$webRoot = str_replace($replace_src, $replace_str, $text);
				
				// get user
				$user = array(
					'user_id' => $userId,
					'username' => $visitor['username'],
				);
				
				// build link
				$link = XenForo_Link::buildPublicLink('members', $user);			
				
				// message4
				$emailMessage4 = new XenForo_Phrase('registeremail_ip_address') . ' ' . $ipAddress . '<br /><br />' . '
				' . new XenForo_Phrase('registeremail_location_determined_by_ip') . ' ' . $location . '<br /><br />' . '	
				' . new XenForo_Phrase('registeremail_search_by_ip_address') . ' ' . '<a href="' . 'http://whatismyipaddress.com/ip/' . $ipAddress . '">' . 'http://whatismyipaddress.com/ip/' . $ipAddress . '</a><br /><br />' . '	
				' . new XenForo_Phrase('registeremail_users_profile') . ' ' . '<a href="' . $webRoot . $link . '">' . $webRoot . $link . '</a><br /><br />';

				//########################################
				// create message from parts
				//########################################

				$message = $emailMessage1 . $emailMessage2 . $emailMessage3 . $emailMessage4;
				
				//########################################
				// prepare mail variables
				//########################################				
		
				// get options from Admin CP -> Options -> Register Email -> Email From Username    
				$username = XenForo_Application::get('options')->registerEmailEmailFromUsername;			
				
				// get options from Admin CP -> Options -> Register Email -> Email To    
				$emailTo = XenForo_Application::get('options')->registerEmailEmailTo;
				
				// put into array
				$email = explode(',', $emailTo);					
				
				// subject					
				$subject = new XenForo_Phrase('registeremail_new_registration_for') . ' ' . $visitor['username'];				
				
				//########################################
				// send mail
				//########################################
				
				$count = count($email);
				
				for ($i=0; $i<$count; $i++)
				{
					// define user variable
					$user = array(
						'username' => $username,
						'email' => $email[$i]
					);
					
					// prepare mailParams                    
					$mailParams = array(
						'user' => $user,
						'subject' => $subject,
						'message' => $message
					);
						
					// prepare mail variable
					$mail = XenForo_Mail::create('registeremail_contact', $mailParams);
					
					// send mail
					$mail->queue($user['email'], $user['username']);
				}
				
				// set languageId
				XenForo_Phrase::setLanguageId($visitor['language_id']);
			}	
		}
		
		// return parent
		return $parent;
	}
}