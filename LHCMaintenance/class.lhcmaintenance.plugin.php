<?php if(!defined('APPLICATION')) die();
/**
* # Maintenance Plugin for Vanilla 2 #
* This is an update for the old plugin "Closed For Maintenance" (https://open.vanillaforums.com/addon/maintenance-plugin) made by Adrian Speyer (http://adrianspeyer.com/).
* Working on Vanilla 2.6
*/

// Define the plugin:
$PluginInfo['LHCMaintenance'] = array(
   'Name' => 'LHCMaintenance',
   'Description' => 'Maintenance plugin for Vanilla 2, allows you to close your site to do maintenance. You can customize the image by replacing the current one (maintenance.png).',
   'Version' => '2.0.0',
   "mobileFriendly" => true,
   'Author' => "YaelDD",
   'AuthorUrl' => 'https://yaeldd.lifeheart.club',
   'License' => 'GNU GPL2',
   'SettingsUrl' => '/plugin/lhcmaintenance',
   'SettingsPermission' => 'Garden.Settings.Manage'
);

class LHCMaintenancePlugin  extends Gdn_Plugin {
    /**
     * Plugin setup
     *
     * This method is fired only once, immediately after the plugin has been enabled in the /plugins/ screen,
     * and is a great place to perform one-time setup tasks, such as database structure changes,
     * addition/modification of config file settings, filesystem changes, etc.
     */
    public function setup() {
        // Set up the plugin's default values
        saveToConfig('Plugin.LHCMaintenance.Enabled', false);
        saveToConfig('Plugin.LHCMaintenance.Style', "
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
}");
        saveToConfig('Plugin.LHCMaintenance.MaintenanceTitle', "Under Maintenance");
        saveToConfig('Plugin.LHCMaintenance.MaintenanceMessage', c('Garden.Title')." is currently under maintenance, please check back later!");

        // Trigger database changes
        $this->structure();
    }

    /**
     * Plugin cleanup
     *
     * This method is fired only once, immediately before the plugin is disabled, and is a great place to
     * perform cleanup tasks such as deletion of unsued files and folders.
     */
    public function onDisable() {
        removeFromConfig('Plugin.LHCMaintenance.Enabled');
        removeFromConfig('Plugin.LHCMaintenance.Style');
        removeFromConfig('Plugin.LHCMaintenance.MaintenanceTitle');
        removeFromConfig('Plugin.LHCMaintenance.MaintenanceMessage');

        // Never delete from the database OnDisable.
        // Usually, you want re-enabling a plugin to be as if it was never off.
    }

    /**
     * This is a special method name that will automatically trigger when a forum owner runs /utility/update.
     * It must be manually triggered if you want it to run on Setup().
     */
    public function structure() {
        /*
        // Create table GDN_Example, if it doesn't already exist
        Gdn::Structure()
            ->Table('Example')
            ->PrimaryKey('ExampleID')
            ->Column('Name', 'varchar(255)')
            ->Column('Type', 'varchar(128)')
            ->Column('Size', 'int(11)')
            ->Column('InsertUserID', 'int(11)')
            ->Column('DateInserted', 'datetime')
            ->Column('ForeignID', 'int(11)', TRUE)
            ->Column('ForeignTable', 'varchar(24)', TRUE)
            ->Set(FALSE, FALSE);
        */
    }

    public function pluginController_lhcmaintenance_create($Sender) {
        /*
         * If you build your views properly, this will be used as the <title> for your page, and for the header
         * in the dashboard. Something like this works well: <h1><?php echo T($this->Data['Title']); ?></h1>
         */
        $Sender->title('LHCMaintenance Plugin');
        $Sender->addSideMenu('plugin/lhcmaintenance');

        // If your sub-pages use forms, this is a good place to get it ready
        $Sender->Form = new Gdn_Form();

        $this->dispatch($Sender, $Sender->RequestArgs);
    }

    public function controller_index($Sender) {
        // Prevent non-admins from accessing this page
        $Sender->permission('Garden.Settings.Manage');
        $Sender->setData('PluginDescription',$this->getPluginKey('Description'));

		$Validation = new Gdn_Validation();
        $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
        $ConfigurationModel->setField(array(
            'Plugin.LHCMaintenance.Enabled'     => false,
            'Plugin.LHCMaintenance.Style'   => "
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
}",
            'Plugin.LHCMaintenance.MaintenanceTitle'   => "Under Maintenance",
            'Plugin.LHCMaintenance.MaintenanceMessage'      => c('Garden.Title')." is currently under maintenance, please check back later!"
        ));

        // Set the model on the form.
        $Sender->Form->setModel($ConfigurationModel);

        // If seeing the form for the first time...
        if ($Sender->Form->authenticatedPostBack() === false) {
            // Apply the config settings to the form.
            $Sender->Form->setData($ConfigurationModel->Data);
		} else {
            $ConfigurationModel->Validation->applyRule('Plugin.LHCMaintenance.Style', 'Required');
            $ConfigurationModel->Validation->applyRule('Plugin.LHCMaintenance.MaintenanceTitle', 'Required');
            $ConfigurationModel->Validation->applyRule('Plugin.LHCMaintenance.MaintenanceMessage', 'Required');
            $Saved = $Sender->Form->save();
            if ($Saved) {
                $Sender->StatusMessage = t("Changes has been saved!.");
                header('Location: '.$_SERVER['REQUEST_URI']);
            }
        }

        // GetView() looks for files inside plugins/PluginFolderName/views/ and returns their full path. Useful!
        $Sender->render($this->getView('settings.php'));
    }

    public function base_getAppSettingsMenuItems_handler($Sender) {
        $Menu = &$Sender->EventArguments['SideMenu'];
        $Menu->addLink('Add-ons', 'LHCMaintenance', 'plugin/lhcmaintenance', 'Garden.Settings.Manage');
    }

    public function Base_Render_Before($Sender) {
        if(c('Plugin.LHCMaintenance.Enabled') == true) {
            if(!Gdn::Session()->CheckPermission('Garden.Settings.Manage')) {
                // Without Permission
                echo '
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>'.c('Garden.Title').' - Under Maintenance</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <style>
                            '.c('Plugin.LHCMaintenance.Style').'
                        </style>
                    </head>
                    <body>
                        <div id="wrapper">
                            <div id="header">
                                <h1>'.c('Garden.Title').' - '.c('Plugin.LHCMaintenance.MaintenanceTitle').'</h1>
                            </div>
                            <div id="content">
                                <img src="./plugins/LHCMaintenance/maintenance.png" style="max-width:60%;" alt="Maintenance" />
                                <span id="maintenance-text">'.c('Plugin.LHCMaintenance.MaintenanceMessage').'</span>
                            </div>
                        </div>
                    </body>
                ';
                exit();
            }
        }
    }

    //public function Base_Render_Before($Sender) {
    public function Base_Render_After($Sender) {
        if(c('Plugin.LHCMaintenance.Enabled') == true) {
            if ( Gdn::Session()->CheckPermission('Garden.Settings.Manage')) {
                // With Permission
                echo '<script type="text/javascript">$("body").prepend("<div style=\"position:sticky;top:0px;left:0px;width:100%;min-height:50px;color:yellow;background-color:red;text-align:center;padding:15px;z-index:1;\"><b>The site is currently in Maintenance Mode</b></div>");</script>';
            }
        }
    }
}
