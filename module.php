<?

class ForgeMailchimp extends Module {

    public function setup() {
        $this->id = "forge-mailchimp";
        $this->name = i('Mailchimp for Forge', 'forge-news');
        $this->description = i('Module to add newsletter Subscription form.');
        $this->image = $this->url().'assets/images/mailchimp-logo.png';
    }

    public function start() {
        require_once($this->directory()."component.php");
    }

}

?>
