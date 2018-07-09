<?php if(!defined('APPLICATION')) die();
/**
* # Maintenance Plugin for Vanilla 2 #
* This is an update for the old plugin "Closed For Maintenance" (https://open.vanillaforums.com/addon/maintenance-plugin) made by Adrian Speyer (http://adrianspeyer.com/).
* Working on Vanilla 2.6
*/

// Define the plugin:
$PluginInfo['LHCMaintenance'] = array(
   'Name' => '[LHC]Maintenance',
   'Description' => 'Maintenance plugin for Vanilla 2, allows you to close your site to do maintenance. You can customize the image by replacing the current one (maintenance.png).',
   'Version' => '1.0.1',
   "mobileFriendly" => true,
   'Author' => "Yael Delgado",
   'AuthorUrl' => 'https://yaeldd.lifeheart.club',
   'License' => 'GNU GPL2'
);

class LHCMaintenancePlugin  extends Gdn_Plugin {

    //public function Base_Render_Before($Sender) {
    public function Base_Render_After($Sender) {
		if ( Gdn::Session()->CheckPermission('Garden.Settings.Manage')) {
		    // With Permission
		    echo '<script type="text/javascript">$("body").prepend("<div style=\"color:yellow;background-color:red;text-align:center;padding:15px;\"><b>The site is currently in Maintenance Mode</b></div>");</script>';
		} else {
		    // Without Permission
		    echo '
    		    <style>
                    .container {
                        min-height: -webkit-fill-available;
                    }
                    #maintenance {
                        zoom: 1;
                        margin: 20px 0 20px 0;
                        padding-bottom: 20px;
                        text-align: center;
                    }
                </style>
                <script type="text/javascript">
                    $(\'<div id="maintenance"><img src="./plugins/LHCMaintenance/maintenance.png" style="max-width:25%;" alt="Maintenance"></div>\').replaceAll(\'#Body\');
                </script>
		    ';
		}
    }
    
    public function Setup() {
    }
}
