<?php
require_once 'modules/clients/models/UserGateway.php';
require_once 'modules/clients/models/Client_EventLog.php';
require_once 'library/CE/NE_Plugin.php';

class PluginNexmo extends NE_Plugin
{

    protected $featureSet = 'accounts';

    function getVariables()
    {
        $variables = array(
            'Plugin Name'       => array(
                'type'        => 'hidden',
                'description' => '',
                'value'       => 'Nexmo',
            ),
            'Description' => array (
                "type"=>"hidden",
                "description"=>/*T*/"Description viewable by admin in server settings"/*/T*/,
                "value"=>/*T*/"Allow SMS messages via Nexmo. All requests require your API credentials, you will find them in API Settings in Nexmo Dashboard."/*/T*/
             ),
            /*T*/'Enabled'/*/T*/       => array(
                'type'          => 'yesno',
                'description'   => /*T*/'When enabled sms messages will use this plugin.'/*/T*/,
                'value'         => '0',
            ),
            /*T*/'API Key'/*/T*/       => array(
                'type'          => 'text',
                'description'   => /*T*/'Your API Key.<br/><i>Ex: api_key=n3xm0rock</i>'/*/T*/,
                'value'         => '',
                'required'      => true
            ),
            /*T*/'API Secret'/*/T*/       => array(
                'type'          => 'text',
                'description'   => /*T*/'Your API Secret.<br/><i>Ex: api_secret=12ab34cd</i>'/*/T*/,
                'value'         => '',
                'required'      => true
            ),
            /*T*/'Nexmo Number'/*/T*/       => array(
                'type'          => 'text',
                'description'   => /*T*/'The number used as from message.  Obtained in dashboard.'/*/T*/,
                'value'         => '',
                'required'      => true
            )
        );
        return $variables;
    }

    function execute($data)
    {

        require_once("library/CE/RestRequest.php");
        $key = $this->settings->get('plugin_nexmo_API Key');
        $secret = $this->settings->get('plugin_nexmo_API Secret');
        $number = $this->settings->get('plugin_nexmo_Nexmo Number');

        $url = "http://rest.nexmo.com/sms/json?api_key=".$key."&api_secret=".$secret."&to=".$data['phonenumber']."&from=".$number."&text=".urlencode($data['message']);
        $request = new RestRequest($url, 'GET');
        $request->execute();
        //CE_Lib::debug($request->getResponseBody());
        $this->trackUsage('sms','execute');
    }

}