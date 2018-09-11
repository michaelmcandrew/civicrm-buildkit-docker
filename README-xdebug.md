# How to use Xdebug

Xdebug works with Ubuntu and PhpStorm.
Big thanks to MetalArend of [phpqa.io](https://phpqa.io/phpqa.io), because
he did most of the work.

## Preparing your containers

To make this work, the following line needed to be added
to `docker-compose.yml`, under services, civicrm, environment:

```
XDEBUG_CONFIG: "default_enable=0 remote_enable=1 remote_handler=dbgp remote_port=9000 remote_autostart=0 remote_connect_back=0 idekey=PHPSTORM remote_host=host.docker.internal"
```

The value of 'idekey', `PHPSTORM`, is arbitrary. You can also choose another
port for Xdebug to connect back to your host.

As I understand, if you run docker on Mac OSX,
from within your containers, host.docker.internal will resolve to the
IP-addres of your docker host. This does not work with docker on Linux, so if
you run Linux (like me), you should enter the following:

```
docker-compose exec civicrm bash -c 'ip route | awk "/default/ {print \$3 \" host.docker.internal\"}" >> /etc/hosts'
```

This creates an entry for host.docker.internal
in your civicrm-container.

**You need to issue the command above every time after starting your containers!**

## Drill a hole in your firewall.

If you use the uncomplicated firewall (enter `sudo ufw status` to check), you
need to allow incoming traffic on port 9000 of your host machine:

```
sudo ufw allow 9000
```

This will allow anyone on your network to connect to your PhpStorm instance
when debugging, which is probably a bad thing. But I am not aware of a better
way to fix this.

## Configuring PhpStorm

(I guess you could do something similar with Netbeans.)

- Go to the PHP Debug settings in PHPStorm:

    > "Preferences/Settings" > "Languages & Frameworks" > "PHP" > "Debug"

- Change the Xdebug settings:

    - Debug port: 9000 (= remote_port mentioned in `XDEBUG_CONFIG`)
    - Can accept external connections: true

- Go to the Server settings in PHPStorm:

    > "Preferences/Settings" > "Languages & Frameworks" > "PHP" > "Servers"

- Add a new server:

    - Name: localhost
    - Host: localhost
    - Port: 8080
    - Debugger: Xdebug
    - Use path mappings: true
    - Set up a path mapping from the `build/dmaster` directory on your file
      system to `/buildkit/build/dmaster` on the server. Make sure it is
      correctly applied, because this configuration screen in PhpStorm
      sometimes shows unexpected behavior.

- Go to the Run/Debug configurations in PHPStorm:

    > "Run" > "Edit configurations..."

- Add a new configuration:

    - Select "PHP Remote Debug"
    - Name: Xdebug on Docker
    - Filter debug connection by IDE key: true
    - Server: localhost
    - IDE key (session id): `PHPSTORM` (= idekey mentioned in the `XDEBUG_CONFIG`)

## Run the solution with the debugger

- Go to the Debug dialog in PHPStorm:

    > "Run" > "Debug..."

- Select "Xdebug on Docker" and run it!

To test whether it works, set a breakpoint in index.php, and
load a page on your local instance, to see whether the breakpoint is hit.

## Debugging drush commands

You can also debug when using drush. For this to work, you have to click on
the 'Start listening for PHP Debug Connections' icon in PhpStorm first.

Then you run drush from within your container like this:

```
SERVER_NAME=localhost SERVER_PORT=8080 PHP_OPTIONS='-d xdebug.remote_connect_back=0 -d xdebug.remote_enable=1 -d xdebug.remote_handler=dbgp -d xdebug.remote_mode=req -d xdebug.remote_port=9000 -d xdebug.remote_host=host.docker.internal -d xdebug.idekey=PHPSTORM' drush cvapi Contact.get
```

(The actual drush command is at the end of the line.) This command line can
probably be simplified, suggestions are welcome.

You will notice that your debugger will break, without showing a thing. This
is because phpstorm cannot find the source of `drush.php`, which is in
`/buildkit/vendor/drush/drush/` on your container. But if you then just
resume (by clicking the icon, or pressing F9), debugging resumes.

You can test whether this specific command works, by setting a breakpoint
in `civicrm_api3_contact_get`, which is in the file
`build/dmaster/sites/all/modules/civicrm/api/v3/Contact.php`.
