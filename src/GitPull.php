<?php
/**
 * PHP Auto Pull Script For Composer
 * @author Darkzn98 <darkzn98@gmail.com>
 * 
 */

$date = new DateTime();

// Go Back From /public to laravel root dir
chdir('..');

echo str_pad("\n", 100, '-')."\n";
echo str_pad('DateTime', 10, ' ').": ".$date->format('d F Y, H:i:s')."\n";
echo str_pad('Directory', 10, ' ').": ".getcwd()."\n\n";

echo str_pad("", 50, '=')."\n";
echo "Running Git Pull\n";
echo shell_exec('git pull')."\n";
echo str_pad("", 50, '=')."\n";

echo str_pad("", 50, '=')."\n";
echo "Running Composer Install\n";
$home = $_SERVER["HOME"];
putenv("COMPOSER_HOME=$home/.composer");
echo shell_exec("composer install");
echo str_pad("", 50, '=')."\n";

echo str_pad('', 100, '-');

?>