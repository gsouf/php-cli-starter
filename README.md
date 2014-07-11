CLI-STARTER
===========

**ALPHA TESTING** => all feature are not implemented yet. Doc i not complete and some feature may change or disapear.

Cli starter is a way to start your php scripts in the background.
You keep full controller over them, you know how they run, what step they run...

You can interact with them while they are running, stop them at any moment.

Cli starter also offers you an easy way to configure and create your commands.


Usage
=====

````sh

    // call "commandname" command with option "username" for option "u"
    bootstrap.php commandname -u "username"

```



Install
=======

Cli Starter is delivered as a library. You can install it via composer : ``sneakybobito/cli-starter``

You will need a boostrap file. For instance : https://github.com/SneakyBobito/php-cli-starter/blob/master/bootstrap-example.php




Incoming Features
==================

* Steps
* Checkpoint
* mongo adapter
* interaction while running

* monitor run via cli or web 
* start script from web
* stop script from cli or web
* default test quiet and help mods
* delay between script run
* configurable cron mod


Reserved Args
=============

* quiet
* help
* conf-file

Thanks
======

Thanks to pwfisher for it's command line script that was usefull to me : https://github.com/pwfisher/CommandLine.php
