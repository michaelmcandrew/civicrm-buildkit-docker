<?php
/* nginx Virtual Host Template
 *
 * @var string $root - the local path to the web root
 * @var string $host - the hostname to listen for
 * @var int $port - the port to listen for
 * @var string $include_vhost_file - the local path to a related config file
 * @var string $visibility - which interfaces the vhost is available on
 */
?>
server {
  server_name <?php echo $host; ?>;
  listen <?php echo $port ?>;
  root <?php echo $root; ?>;

  <?php if (!empty($include_vhost_file)) { ?>

  include <?php echo $include_vhost_file ?>;

  <?php } else { ?>
  location ~ \..*/.*\.php$ {
    return 403;
  }

  location ~* (\.php~|\.php.bak|\.php.orig)$ {
    deny all;
  }

  location / {
  # This is cool because no php is touched for static content
      try_files $uri @rewrite;
  }
  location @rewrite {
      # Some modules enforce no slash (/) at the end of the URL
      # Else this rewrite block wouldn't be needed (GlobalRedirect)
      rewrite ^/(.*)$ /index.php?q=$1;
  }

  location ~ \.php$ {
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    #NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini
    include fastcgi.conf;
    fastcgi_pass fpm:9000;
    fastcgi_intercept_errors on;
    fastcgi_read_timeout 60;
  }
  <?php } ?>

}
