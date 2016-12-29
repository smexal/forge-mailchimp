<?php

namespace Forge\Modules\ForgeMailchimp;

use \Forge\Core\Abstracts as Abstracts;

class ForgeMailchimp extends Abstracts\Module {
    private $settings = null;

    private $settings_field_api_key = 'forge_mailchimp_api_key';

    public function setup() {
        $this->version = '1.0.0';
        $this->settings = Settings::instance();
        $this->id = "forge-mailchimp";
        $this->name = i('Mailchimp for Forge', 'forge-mailchimp-form');
        $this->description = i('Module to add newsletter Subscription form.', 'forge-mailchimp-form');
        $this->image = $this->url().'assets/images/mailchimp-logo.png';
    }

    public function start() {
        require_once($this->directory()."component.php");
        require_once($this->directory()."api.php");

        $this->registerFields();

        App::instance()->tm->theme->addScript(CORE_WWW_ROOT."scripts/externals/jquery.js", true, 0);
        App::instance()->tm->theme->addScript(CORE_WWW_ROOT."scripts/helpers.js", true);
        App::instance()->tm->theme->addScript(CORE_WWW_ROOT."scripts/forms.js", true);
        App::instance()->tm->theme->addScript($this->url()."assets/scripts.js", true);

        API::instance()->register('forge-mailchimp', array($this, 'apiAdapter'));
    }

    public function apiAdapter($query) {
        // add recipient to the list.

        if($query == 'add') {
            $email = $_POST['forge-mailchimp-email'];
            $component = App::instance()->com->getComponentById($_POST['componentId']);
            $apiKey = Settings::get('forge_mailchimp_api_key');

            $mailchimp = new MailchimpAPI($apiKey);

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mailchimp->addRecipient(array(
                    'email' => $email
                ), $component->getField('forge_mailchimp_mailchimp_list'));
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
        $this->settings->registerField(
            Fields::text(array(
            'key' => $this->settings_field_api_key,
            'label' => i('Insert your Mailchimp API key', 'forge-mailchimp-form'),
            'hint' => i('Check official Mailchimp for more information: http://goo.gl/zaUhCY', 'forge-mailchimp-form')
        ), Settings::get($this->settings_field_api_key)), $this->settings_field_api_key, 'right');
    }

}

?>
