<?php

namespace Forge\Modules\ForgeMailchimp;

use \Forge\Core\Abstracts\Module;
use \Forge\Core\App\API;
use \Forge\Core\App\App;
use \Forge\Core\Classes\Fields;
use \Forge\Core\Classes\Utils;
use \Forge\Core\Classes\Settings;



class ForgeMailchimp extends Module {
    private $settings = null;

    private $settings_field_api_key = 'forge_mailchimp_api_key';
    private $settings_field_default_list = 'forge_mailchimp_default_list';

    public function setup() {
        $this->version = '1.0.0';
        $this->settings = Settings::instance();
        $this->id = "forge-mailchimp";
        $this->name = i('Mailchimp Subscriptions', 'forge-mailchimp-form');
        $this->description = i('Module to add newsletter Subscription form.', 'forge-mailchimp-form');
        $this->image = $this->url().'assets/images/mailchimp-logo.png';
    }

    public function start() {

        $this->registerFields();

        App::instance()->tm->theme->addScript(CORE_WWW_ROOT."ressources/scripts/externals/jquery.js", true, 0);
        App::instance()->tm->theme->addScript(CORE_WWW_ROOT."ressources/scripts/helpers.js", true);
        App::instance()->tm->theme->addScript(CORE_WWW_ROOT."ressources/scripts/forms.js", true);
        App::instance()->tm->theme->addScript($this->url()."assets/scripts.js", true);

        API::instance()->register('forge-mailchimp', array($this, 'apiAdapter'));
    }

    public function apiAdapter($query) {
        // add recipient to the list.

        if ($query == 'add') {
            $email = $_POST['forge-mailchimp-email'];
            $component = App::instance()->com->getComponentById($_POST['componentId']);
            if(is_null($component)) {
                $list = Settings::get('forge_mailchimp_default_list');
            } else {
                $list = $component->getField('forge_mailchimp_mailchimp_list');
            }
            $apiKey = Settings::get('forge_mailchimp_api_key');

            $mailchimp = new MailchimpAPI($apiKey);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mailchimp->addRecipient(array(
                    'email' => $email
                ), $list);
                return json_encode(array(
                    'type' => 'success',
                    'message' => i('Subscription successful.', 'forge-mailchimp-form')
                ));
            } else {
                return json_encode(array(
                    'type' => 'error',
                    'message' => i('Invalid E-Mail Address', 'forge-mailchimp-form')
                ));
            }
        }
    }

    private function registerFields() {
        /**
        due to the mailchimp API this is slow...
        so we want this only to load on the correspondign page:
        /manage/settings
        **/
        $uriComponents = Utils::getUriComponents();
        if(! in_array("manage", $uriComponents) && ! in_array("settings", $uriComponents)) {
            return;
        }
        
        $this->settings->registerField(
            Fields::text(array(
            'key' => $this->settings_field_api_key,
            'label' => i('Insert your Mailchimp API key', 'forge-mailchimp-form'),
            'hint' => i('Check official Mailchimp for more information: http://goo.gl/zaUhCY', 'forge-mailchimp-form')
        ), Settings::get($this->settings_field_api_key)), $this->settings_field_api_key, 'right');

        if(Settings::get($this->settings_field_api_key)) {
            $this->settings->registerField(
                Fields::select(array(
                'key' => $this->settings_field_default_list,
                'label' => i('Default Mailchimp List', 'forge-mailchimp-form'),
                'hint' => i('Choose a Subscriber List from your Accounts Lists', 'forge-mailchimp-form'),
                "callable" => true,
                "values" => array($this, 'getMailchimpListOptionValues')
            ), Settings::get($this->settings_field_default_list)), $this->settings_field_default_list, 'right');            
        }
    }

    public function getMailchimpListOptionValues() {
        $mailchimp = new MailchimpAPI(Settings::get('forge_mailchimp_api_key'));
        return array_merge(array('0' => i('Choose one', 'forge-mailchimp-form')), $mailchimp->getLists());
    }

}

?>
