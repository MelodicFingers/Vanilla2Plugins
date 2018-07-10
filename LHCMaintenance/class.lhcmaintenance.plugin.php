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
   'Version' => '1.2.0',
   "mobileFriendly" => true,
   'Author' => "Yael Delgado",
   'AuthorUrl' => 'https://yaeldd.lifeheart.club',
   'License' => 'GNU GPL2'
);

class LHCMaintenancePlugin  extends Gdn_Plugin {
    public function Base_Render_Before($Sender) {
        if ( !Gdn::Session()->CheckPermission('Garden.Settings.Manage')) {
		    // Without Permission
		    echo '
		    <!DOCTYPE html>
            <html>
                <head>
                    <title>'.$Sender->Title().' - Under Maintenance</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body {
                            background: #000;
                        }
                        #wrapper {
                            width: 80%;
                            margin: auto;
                            margin-top: 50px;
                            margin-bottom: 50px;
                        }
                        #header {
                            color: #fff;
                            font-family: monospace;
                        }
                        #content {
                            background: #ffffff;
                            text-align: center;
                            padding: 50px;
                        }
                        #maintenance-text {
                            display: block;
                            margin: auto;
                            font-family: monospace;
                            font-size: 20px;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <div id="wrapper">
                        <div id="header">
                            <h1>'.$Sender->Title().' - Under Maintenance</h1>
                        </div>
                        <div id="content">
                            <img src="./plugins/LHCMaintenance/maintenance.png" style="max-width:60%;" alt="Maintenance" />
                            <span id="maintenance-text">'.$Sender->Title().' is currently under maintenance, please check back later!</span>
                        </div>
                    </div>
                </body>
		    ';
		    exit();
		}
    }
    
    //public function Base_Render_Before($Sender) {
    public function Base_Render_After($Sender) {
		if ( Gdn::Session()->CheckPermission('Garden.Settings.Manage')) {
		    // With Permission
		    echo '<script type="text/javascript">$("body").prepend("<div style=\"color:yellow;background-color:red;text-align:center;padding:15px;\"><b>The site is currently in Maintenance Mode</b></div>");</script>';
		}
    }
    
    public function Setup() {
    }
}
