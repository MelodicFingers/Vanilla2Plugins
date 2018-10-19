<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php echo $this->data('Title'); ?></h1>

<div class="Info">
    <?php echo t($this->Data['PluginDescription']); ?>

    <p>
        If you like the plugin you can help me keep developing through donating:
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <!-- Identify your business so that you can collect the payments. -->
            <input type="hidden" name="business"
                value="yaeldd@outlook.com">

            <!-- Specify a Donate button. -->
            <input type="hidden" name="cmd" value="_donations">

            <!-- Specify details about the contribution -->
            <input type="hidden" name="item_name" value="Vanilla Forums Developer Donation">
            <input type="hidden" name="item_number" value="LHCMaintenance Plugin">
            <input type="hidden" name="currency_code" value="USD">

            <!-- Display the payment button. -->
            <input type="image" name="submit"
            src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"
            alt="Donate">
            <img alt="" width="1" height="1"
            src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
        </form>
    </p>

    <?php
        if($_SERVER['REQUEST_URI'] != '/plugin/lhcmaintenance') {
            ?>
            <div class="padded alert alert-warning">
                <a href="/plugin/lhcmaintenance">USE THIS PAGE</a> TO BE ABLE TO CHANGE AND SAVE THE SETTINGS OF THE PLUGIN.
            </div>
            <?php
        }
    ?>

    <p>If you find any error, please visit the <a target="_blank" href="https://github.com/YaelDD/Vanilla2Plugins/issues">repository on GitHub</a>.</p>
</div>

<h3><?php echo t('Settings'); ?></h3>

<?php
    echo $this->Form->open();
    echo $this->Form->errors();

    echo $this->Form->label('Is Maintenance Time?', 'Plugin.LHCMaintenance.Enabled');
    echo $this->Form->toggle('Plugin.LHCMaintenance.Enabled');

    echo $this->Form->label('Style (CSS):', 'Plugin.LHCMaintenance.Style');
    echo $this->Form->textBox('Plugin.LHCMaintenance.Style', ['MultiLine' => true]);

    echo $this->Form->label('Maintenance Title', 'Plugin.LHCMaintenance.MaintenanceTitle');
    echo $this->Form->textBox('Plugin.LHCMaintenance.MaintenanceTitle');

    echo $this->Form->label('Maintenance Message', 'Plugin.LHCMaintenance.MaintenanceMessage');
    echo $this->Form->textBox('Plugin.LHCMaintenance.MaintenanceMessage');

    echo $this->Form->close('Save');
    echo '<br />';