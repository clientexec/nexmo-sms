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
                "description"=>lang("Description viewable by admin in server settings"),
                "value"=>lang("Allow SMS messages via Nexmo. All requests require your API credentials, you will find them in API Settings in Nexmo Dashboard.")
             ),
            lang('Enabled')       => array(
                'type'          => 'yesno',
                'description'   => lang('When enabled sms messages will use this plugin.'),
                'value'         => '0',
            ),
            lang('API Key')       => array(
                'type'          => 'text',
                'description'   => lang('Your API Key.<br/><i>Ex: api_key=n3xm0rock</i>'),
                'value'         => '',
                'required'      => true
            ),
            lang('API Secret')       => array(
                'type'          => 'text',
                'description'   => lang('Your API Secret.<br/><i>Ex: api_secret=12ab34cd</i>'),
                'value'         => '',
                'required'      => true
            ),
            lang('Nexmo Number')       => array(
                'type'          => 'text',
                'description'   => lang('The number used as from message.  Obtained in dashboard.'),
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
