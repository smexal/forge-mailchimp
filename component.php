<?php

class ForgeMailchimpForm extends Component {
    public $settings = array();
    private $prefix = 'forge_mailchimp_';

    public function prefs() {
        $this->settings = array(
            array(
                "label" => i('Lead text', 'forge-mailchimp-form'),
                "hint" => '',
                "key" => $this->prefix."lead_text",
                "type" => "text"
            ),
            array(
                "label" => i('E-Mail Input Label', 'forge-mailchimp-form'),
                "hint" => '',
                "key" => $this->prefix."input_label",
                "type" => "text"
            ),
            array(
                "label" => i('Signup Button Text', 'forge-mailchimp-form'),
                "hint" => '',
                "key" => $this->prefix."button_text",
                "type" => "text"
            )
        );
        return array(
            'name' => i('Mailchimp Form'),
            'description' => i('Add a mailchimp subscription form', 'forge-mailchimp-form'),
            'id' => 'forge_mailchimp_form',
            'image' => '',
            'level' => 'inner',
            'container' => false
        );
    }

    public function content() {
        return App::instance()->render(DOC_ROOT."modules/forge-mailchimp/", "form", array(
        ));
    }

    public function customBuilderContent() {
        return App::instance()->render(CORE_TEMPLATE_DIR."components/builder/", "text", array(
            'text' => i('Mailchimp Form', 'forge-mailchimp-form')
        ));
    }

}

?>
