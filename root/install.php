<?php

// Config
$giscuitVersion = '1.4.3';
$downloadsUrl = 'http://downloads.giscuit.com';

$GLOBALS['config']['ioncube']['downloadUrl'] = 'http://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.zip';
$GLOBALS['config']['ioncube']['installPath'] = '/usr/local';
$GLOBALS['config']['installPath'] = '/var/www';

putenv('DEBIAN_FRONTEND=noninteractive');
ini_set('memory_limit', '16M');
define('IO_REDIRECT', ' > /dev/null 2>&1');

// Install name
$GLOBALS['config']['installName'] = 'giscuit';
if(file_exists("/etc/apache2/sites-available/{$GLOBALS['config']['installName']}") ||
    file_exists("/etc/httpd/conf.d/{$GLOBALS['config']['installName']}.conf")) {
    error("Virtual host ({$GLOBALS['config']['installName']}) already exists");
}

// Install path
$GLOBALS['config']['installPath'] .= '/' . $GLOBALS['config']['installName'];
if(file_exists($GLOBALS['config']['installPath'])) {
    error("Install path ({$GLOBALS['config']['installPath']}) already exists");
}

// Virtual host
$apacheAccessLog = '/var/log/apache2/access.log';
$virtualHost = "<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    #ServerName localhost
    DocumentRoot {$GLOBALS['config']['installPath']}/public/

    <Directory {$GLOBALS['config']['installPath']}/public/>
       Options FollowSymLinks
       AllowOverride All
       Order Deny,Allow
       Allow from all
    </Directory>

    <Directory {$GLOBALS['config']['installPath']}/public/tilecache/>
       AddHandler cgi-script .cgi
       Options +ExecCGI
    </Directory>

    ErrorLog {$GLOBALS['config']['installPath']}/logs/error.log
    CustomLog $apacheAccessLog combined
</VirtualHost>";
run("mkdir -p {$GLOBALS['config']['installPath']} ");

// Utils
fwrite(STDOUT, "Downloading and installing utilities...\n");
run('apt-get -y -qq update');
run('apt-get -y -qq install unzip python-imaging ttf-freefont ttf-liberation python-software-properties');

// Apache
fwrite(STDOUT, "Downloading and installing Apache...\n");
run('apt-get -y -qq install apache2');
run('a2enmod rewrite');
run('a2enmod expires');
file_put_contents("{$GLOBALS['config']['installPath']}/apache.conf", $virtualHost);
run("ln -s {$GLOBALS['config']['installPath']}/apache.conf /etc/apache2/sites-available/{$GLOBALS['config']['installName']}.conf");
run("ln -s /etc/apache2/mods-available/cgi.load /etc/apache2/mods-enabled/cgi.load");

fwrite(STDOUT, "Disabling apache2 \"default\" site...\n");
run('a2dissite 000-default');
run("a2ensite {$GLOBALS['config']['installName']}");

// PHP
fwrite(STDOUT, "Downloading and installing PHP...\n");
run('apt-get -y -qq install php5 php5-pgsql php5-mapscript php5-gd php-apc');

// IonCube
installIonCube();

// PostgreSQL + PostGIS
fwrite(STDOUT, "Downloading and installing PostgreSQL and PostGIS...\n");
run('apt-get -y -qq install postgresql-9.3-postgis-2.1');

// GDAL
fwrite(STDOUT, "Downloading and installing GDAL...\n");
run('apt-get -y -qq install gdal-bin');

// Giscuit
fwrite(STDOUT, "Downloading and installing Giscuit...\n");
run("wget -q -O {$GLOBALS['config']['installPath']}/giscuit.zip {$downloadsUrl}/giscuit-{$giscuitVersion}-linux.zip");
run("cd {$GLOBALS['config']['installPath']} && unzip -o -d . giscuit.zip && mv -f giscuit/* . && rmdir giscuit");
unlink("{$GLOBALS['config']['installPath']}/giscuit.zip");

// Permissions
run('chown -R www-data:www-data /var/lib/php5');
run("chown -R www-data:www-data {$GLOBALS['config']['installPath']}");
run("chmod 0700 {$GLOBALS['config']['installPath']}/configs/config.xml");

// Done
fwrite(STDOUT, "\nPlease access http://YOUR_DOMAIN/install.php to proceed with the installation.\n");

function run($cmd) {
    $output = NULL;
    exec($cmd, $output);
    return $output;
}

function error($str) {
    fwrite(STDERR, "ERROR: $str\n");
    exit;
}

function installIonCube() {
    if(file_exists($GLOBALS['config']['ioncube']['installPath'] . '/ioncube')) {
        return;
    }

    fwrite(STDOUT, "Downloading and installing IonCube...\n");
    run("wget -q -O {$GLOBALS['config']['installPath']}/ioncube.zip {$GLOBALS['config']['ioncube']['downloadUrl']}");
    if(! file_exists("{$GLOBALS['config']['installPath']}/ioncube.zip")) {
        error("Could not download IonCube");
    }
    run("unzip -o -d {$GLOBALS['config']['ioncube']['installPath']} {$GLOBALS['config']['installPath']}/ioncube.zip");
    if($GLOBALS['pretend'] == false) {
        unlink("{$GLOBALS['config']['installPath']}/ioncube.zip");
    }

    // PHP version
    $phpVersion = substr(PHP_VERSION, 0, 3);

    // Thread safety
    ob_start();
    phpinfo(INFO_GENERAL);
    $phpinfo = ob_get_contents();
    ob_end_clean();
    preg_match('/thread safety => (disabled|enabled)/i', $phpinfo, $matches);
    $ts = ($matches[1] == 'enabled' ? true : false);

    $ionCubePath = $GLOBALS['config']['ioncube']['installPath'] . '/ioncube/ioncube_loader_lin_' . $phpVersion . ($ts ? '_ts' : '') . '.so';

    file_put_contents("/etc/php5/apache2/conf.d/05-ioncube.ini", "zend_extension=$ionCubePath");
}
