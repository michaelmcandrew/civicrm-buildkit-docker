<?php
/* Apache Virtual Host Template
 *
 * @var string $root - the local path to the web root
 * @var string $host - the hostname to listen for
 * @var int $port - the port to listen for
 * @var string $include_vhost_file - the local path to a related config file
 * @var string $visibility - which interfaces the vhost is available on
 */

?>

<?php if ($use_listen) { ?>
<?php if ($visibility === 'all'): ?>
Listen <?php echo $port ?>
<?php else: ?>
Listen 127.0.0.1:<?php echo $port ?>
<?php endif; ?>
<?php } ?>

<VirtualHost *:<?php echo $port ?>>
    ServerAdmin webmaster@<?php echo $host ?>

    DocumentRoot "<?php echo $root ?>"

    ServerName <?php echo $host ?>

    <Directory "<?php echo $root ?>">
        Options All
        AllowOverride All
        <IfModule mod_authz_host.c>
            Require <?php echo $visibility ?> granted
        </IfModule>
    </Directory>

    <?php if (!empty($include_vhost_file)) { ?>
    Include <?php echo $include_vhost_file ?>
    <?php } ?>

</VirtualHost>
