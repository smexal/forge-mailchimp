<?

class ForgeMailchimp extends Module {
    private $settings = null;

    private $settings_field_api_key = 'forge_mailchimp_api_key';

    public function setup() {
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
