<?php

namespace Forge\Modules\ForgeMailchimp;

use \Forge\Core\Abstracts\Component;
use \Forge\Core\App\App;
use \Forge\Core\Classes\Settings;
use \Forge\Core\Classes\Fields;
use \Forge\Core\Classes\Utils;



class MailchimpComponent extends Component {
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
            ),
            array(
                "label" => i('Choose list to add subscriber', 'forge-mailchimp-form'),
                "hint" => i('If you can\'t see a list, check you api key in the global settings.', 'forge-mailchimp-form') ,
                "key" => $this->prefix."mailchimp_list",
                "type" => "select",
                "callable" => true,
                "values" => array($this, 'getMailchimpListOptionValues')
            )
            // TODO: make a value "callable"
            // make a static calable method to be called, when "values" is required for performance.
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

    public function getMailchimpListOptionValues() {
        $mailchimp = new MailchimpAPI(Settings::get('forge_mailchimp_api_key'));
        return array_merge(array('0' => i('Choose one', 'forge-mailchimp-form')), $mailchimp->getLists());
    }

    public function content() {
        return App::instance()->render(DOC_ROOT."modules/forge-mailchimp/", "form", array(
            'before' => $this->getField($this->prefix."lead_text"),
            'action' => Utils::getUrl(array('api', 'forge-mailchimp', 'add')),
            'form' => $this->form()
        ));
    }

    public function form() {
        $form = '';
        $form.= Fields::text(array(
            'key' => 'forge-mailchimp-email',
            'label' => $this->getField($this->prefix."input_label"),
            'hint' => ''
        ));
        $form.= Fields::hidden(array(
            'name' => 'componentId',
            'value' => $this->getId()
        ));
        $form.= Fields::button($this->getField($this->prefix."button_text"), 'discreet');
        return $form;
    }

    public function customBuilderContent() {
        return App::instance()->render(CORE_TEMPLATE_DIR."components/builder/", "text", array(
            'text' => i('Mailchimp Form', 'forge-mailchimp-form')
        ));
    }

}

?>
