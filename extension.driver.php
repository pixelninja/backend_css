<?php

	class extension_backend_css extends Extension {

		public function about(){
			return array(
				'name' => 'Backend CSS',
				'version' => '0.1',
				'release-date' => '2012-06-11',
				'author' => array(
					'name' => 'Phill Gray',
					'email' => 'pixel.ninjad@gmail.com'
				)
			);
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/backend/',
					'delegate' => 'AdminPagePreGenerate',
					'callback' => 'appendMyCode'
				),
				array(
					'page' => '/backend/',
					'delegate' => 'InitaliseAdminPageHead',
					'callback' => 'appendCodeMirror'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'appendCodeBox'
				),
			);
		}

		public function appendMyCode($context){
			
			$page = Administration::instance()->Page;
			$callback = Administration::instance()->getPageCallback();

			// Include filter?
			if ($page instanceOf contentPublish && $callback['context']['classname'] != 'contentExtensionDashboardIndex') {
				// Add the custom code to the head of the page
				Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/backend_css/assets/custom.css', 'screen', 9001);
			}
		}
		
		public function appendCodeBox($context){
		
			if(isset($_POST['action']['custom_css_save'])){
				# Path to our file
				$custom_file = getcwd() . '/extensions/backend_css/assets/custom.css';
				# Open the file and reset it, to recieve the new code
				$open_file = fopen($custom_file, 'w');
				# Write it, then close
				fwrite($open_file, $_POST['custom_backend_css']);
				fclose($open_file);
				
			}

			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', __('Backend css')));


			$div = new XMLElement('div', NULL, array('class' => 'label'));
			$span = new XMLElement('span', NULL, array('class' => 'frame'));

			$span->appendChild(new XMLElement('button', __('Save'), array('name' => 'action[custom_css_save]', 'type' => 'submit')));
			
			$label = Widget::Label(__('Your JavaScript goes here:'));
			
			# Retrieve the stored code to put it inside the textarea
			$custom_css_content = file_get_contents(getcwd() . '/extensions/backend_css/assets/custom.css');

			$label->appendChild(Widget::Textarea('custom_backend_css', 10, 50, $custom_css_content, array('id' => 'backend_css_field') ));
			
			$div->appendChild($label);
			$div->appendChild($span);

			$div->appendChild(new XMLElement('p', __('Remember: The "extensions/backend_css/assets/custom.css" has to have all permissions ( chmod 777 ).'), array('class' => 'help')));

			$group->appendChild($div);
			$context['wrapper']->appendChild($group);
		}
		
		public function appendCodeMirror($context){
			#Future...
		}
		
	}
