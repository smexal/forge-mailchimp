<?php 

/**
 * Helping Ressources
 * -> http://stackoverflow.com/questions/30481979/adding-subscribers-to-a-list-using-mailchimps-api-v3
 **/

class MailchimpAPI {
    private $apiKey = null;

    public function __construct($key) {
        $this->apiKey = $key;
    }

    public function dataCenter() {
        return substr($this->apiKey,strpos($this->apiKey,'-')+1);
    }

    public function baseURL() {
        return 'https://' . $this->dataCenter() . '.api.mailchimp.com/3.0/';
    }

    public function addRecipient($data, $listId=false, $doubleopt=true) {
        if(!$listId) {
            echo 'Give me a list to put that recipient...';
            return;
        }
        $memberId = md5(strtolower($data['email']));
        $name = array();
        $fname = array();
        $lname = array();
        if(array_key_exists('firstname', $data)) {
            $fname = array('FNAME' => $data['firstname']);
        }
        if(array_key_exists('lastname', $data)) {
            $lname = array('LNAME' => $data['lastname']);
        }
        if(count($lname) > 0 || count($fname) > 0) {
            $name = array('merge_fields' => array_merge($fname, $lname));
        }

        $json = json_encode(array_merge(array(
                'email_address' => $data['email'],
                'status'        => $doubleopt ? "pending" : "subscribed",
                ), 
                $name
            )
        );
        return $this->get($this->baseURL().'lists/' . $listId . '/members/' . $memberId, $json, 'PUT');
    }

    public function getLists() {
        if(! $this->apiKey) {
            return array();
        }
        $lists = json_decode($this->get($this->baseURL().'lists/'));
        $l = array();
        if(is_array($lists) || is_object($lists)) {
            foreach($lists->lists as $list) {
                $l[$list->id] = $list->name;
            }
        }
        return $l;
    }

    public function get($url, $json=false, $request='GET') {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($json)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        return curl_exec($ch);
    }

}

?>