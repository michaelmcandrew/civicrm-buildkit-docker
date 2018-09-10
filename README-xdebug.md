# How to use Xdebug

Xdebug works with Linux and PhpStorm.
Thank you [@MetalArend](https://github.com/MetalArend), for figuring this out.

## Preparing your containers

To make this work, the following line needed to be added
to `docker-compose.yml`, under services, civicrm, environment:

```
XDEBUG_CONFIG: "default_enable=0 remote_enable=1 remote_handler=dbgp remote_port=9000 remote_autostart=0 remote_connect_back=0 idekey=PHPSTORM remote_host=host.docker.internal"
```

The value of 'idekey', `PHPSTORM`, can be anything.

As I understand, if you run docker on Mac OSX,
from within your containers, host.docker.internal will resolve to the
IP-addres of your docker host. This does not
work with docker on Linux, so if you run
Linux (like me), you should enter the following:

```
docker-compose exec civicrm bash -c 'ip route | awk "/default/ {print \$3 \" host.docker.internal\"}" >> /etc/hosts'
```

This creates an entry for host.docker.internal
in your civicrm-container.

**You need to issue the command above every time after starting your containers!**

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
    - Set up a path mapping from the `dmaster` directory on your file systee
      to `/buildkit/build/dmaster` on the server. Make sure it is correctly applied, because this configuration screen in PhpStorm sometimes shows
      unexpected behavior.

- Go to the Run/Debug configurations in PHPStorm:

    > "Run" > "Edit configurations..."

- Add a new configuration:

    - Select "PHP Remote Debug"
    - Name: Xdebug on Docker
    - Filter debug connection by IDE key: true
    - Server: localhost
    - IDE key (session id): `PHPSTORM` (= idekey mentioned in the `XDEBUG_CONFIG`)

## Run the solution with the debugger.

- Go to the Debug dialog in PHPStorm:

    > "Run" > "Debug..."

- Select "Xdebug on Docker" and run it!
