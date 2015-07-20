Setting up a complete Largo dev environment
===========================================

This recipe will walk you through setting up a fresh WordPress install on a Vagrant Virtualbox machine with INN's deploy tools and Largo installed.

We'll walk you through the overall setup of the WordPress directory, and then we'll walk you through setting up Largo and its development requirements.

Software to install first
-------------------------

From `INN's computer setup guide <https://github.com/INN/docs/blob/master/staffing/onboarding/os-x-setup.md#command-line-utilities>`_, install the following software:

- git
- wget
- curl
- phpunit
- virtualenv and virtualenvwrapper
- an SSH key
- VirtualBox
- Vagrant
- npm and grunt-cli

If you're on OSX, you will also want to install Homebrew, to assist in the installation of the above.

Once you have all that set up, you're ready to install Largo and WordPress inside a virtual machine!

Setting up Largo and WordPress
------------------------------

1. First, create a new directory. This will be version-controlled with git, to make it easier to update Largo and the deploy tools. If you're hosting WordPress someplace that allows SFTP access, our deploy tools will help you version-control your deploy, for fun and profit. ::

	mkdir umbrella
	cd umbrella
	git init

2. Add the deploy-tools and Largo repositories. ::

			git submodule add https://github.com/INN/deploy-tools.git tools
			git submodule add https://github.com/INN/Largo.git wp-content/themes/largo-dev


3. Update all the submodules ::

	git submodule update --init --recursive

4. While you're at it, copy some configuration files from the deploy-toools folders to the project root: ::

			cp -r tools/examples/ .


5. Now, on to Largo. ::

	cd wp-content/themes/largo-dev

6. You're going to have to install some things first.

7. First, install the Python dependencies.

	We use a few Python libraries for this project, including `Fabric <http://www.fabfile.org>`_ which powers the INN deploy-tools to elegantly run `many common but complex tasks <https://github.com/INN/deploy-tools/blob/master/COMMANDS.md>`_. In the `OS X setup guide <https://github.com/inn/docs/staffing/onboarding/os-x-setup.md>`_, you should have installed Python virtualenv and virtualenvwrapper.

	Make sure you tell virtualenvwrapper where the umbrella is. ::

		export WORKON_HOME=~/largo-umbrella
		mkdir -p $WORKON_HOME
		source /usr/local/bin/virtualenvwrapper.sh


	You should add that last line to your .bashrc or your .bash_profile.

	Now we can create a virtual environment and install the required Python libraries: ::

		mkvirtualenv largo-umbrella --no-site-packages
		workon largo-umbrella
		pip install -r requirements.txt

8. Our API docs/function reference uses doxphp to generate documentation based on the comments embedded in Largo's source code. You'll need to install doxphp to generate API docs.

	- Installation process with PEAR: ::

		pear channel-discover pear.avalanche123.com
		pear install avalanche123/doxphp-beta


	- Installation process with git. This requires you to know where your ``bin`` directory is ::

		git clone https://github.com/avalanche123/doxphp.git
		cd doxphp/bin
		mv doxph* /path/to/bin/


	The last step may require you to use sudo.

9. Make sure that you have the necessary prerequisites for these next steps.

	From the top level of the project, run the setup routine `as described in the deploy-tools documentation <https://github.com/INN/deploy-tools#setup>`_ ::

		fab wp.verify_prerequisites


	If the script returns any errors, it will also include (hopefully) helpful information on how to rectify the problems. You may need to reinstall curl.

	If the above command fails to run, make sure you have run the ``workon`` and ``pip install`` commands listed above in step 7.

10. Time to install WordPress.

	INN's deploy tools have a handy utility that will install any tagged release of WordPress for you.

	1. ``cd`` to the top level of your project, on the same level as the tools/ directory where INN/deploy-tools was installed.
	2. Find the version number of `the latest release of WordPress <https://github.com/WordPress/WordPress/tags>`_ and use its number in the folloiwing command ::

		fab wp.install:4.2.2


	3. In the computer setup section above, you installed Vagrant. Now, create the virtual machine: ::

		vagrant up


	5. While you're waiting, why not stand up, stretch, and make a cup of tea? Downloading the virtual machine disk image and provisioning it will take a while.In that time, it downloads the image of a Ubuntu Linux system, installs the MySQL and PHP servers, along with all of the most recent updates, and configures it just so that all the Fabric commands work.

	6. When it's done, edit your `/etc/hosts` file: ::

			sudo nano /etc/hosts


	Enter your password, use the arrow keys to position the cursor at the end of the file and add the following line:

		192.168.33.10 vagrant.dev


	Then use Ctrl-O to save your changes and Ctrl-X to exit the editor.

	This tells your system that whenever you use the address ``http://vagrant.dev``, you really mean the IP address of the virtual machine. If you're working on a multisite instance of WordPress, you can add the subdomains such as ``another.blog.at.vagrant.dev`` at the end of the line, separated by a space from ``vagrant.dev``. 

11. Now that the vagrant box is up and running, you can create a database for it to use: ::

			fab vagrant.create_db


Without any arguments, this command will read the defaults from the ``Fabfile.py`` in the root of your project directory.

12. Now, let's take a snapshot of the virtual machine in its new, provisioned, freshly-deployed state. ::

			vagrant plugin install vagrant-vbox-snapshot
			vagrant snapshot take default snapshot_name_goes_here


You can name the snapshot anything you want, and I would recommend describing it in a short way that describes what that state would give you if you were to revert.

13. Now you're going to set up WordPress on Vagrant. Open a browser and point it at http://vagrant.dev/. You should automatically be redirected to http://vagrant.dev/wp-admin/setup-config.php. Choose your language, then enter the details below as they are entered in your ``Fabfile.py``: ::

    * Database Name: `largoproject`
    * User Name: `root`
    * Password: `root`
    * Database Host: `localhost`
    * Table Prefix: `wp_`

14. If you are working on a multisite install, you will want to add these settings to ``wp-config.php`` at the bottom, before "Do not edit below this line." ::


		/* Make this a multisite install. */
		define('MULTISITE', true);
		define('SUBDOMAIN_INSTALL', true);
		define('DOMAIN_CURRENT_SITE', 'vagrant.dev');
		define('PATH_CURRENT_SITE', '/');
		define('SITE_ID_CURRENT_SITE', 1);
		define('BLOG_ID_CURRENT_SITE', 1);

All done? Log into WordPress and start poking around. Remember to take Vagrant snapshots when you get things working how you like the. You'll probably want to take one after you add some posts and configure your menus for testing purposes. If you want to log into the vagrant box, it's as easy as ``vagrant ssh``.

You have installed:

	- INN's deploy tools
	- the Largo theme
	- Grunt and the nodejs packages we use to handle a bunch of things
	- pip, virtualenv, a largo-docs virtualenv, sphinx, and everything needed to rebuild the documentation
	- doxphp and dpxphp2sphinx
	- WordPress on a Vagrant virtual machine

Some notes about Vagrant
------------------------

You can work on files without booting Vagrant, but if you want to view the effects of changing the files, you'll want to run ``vagrant up`` from the root folder of your project, the one that contains the ``Vagrantfile``.

If you want to turn vagrant off for a while, run ``vagrant suspend``. Suspended vagrant boxes can be brough back to life with ``vagrant up``.

When you want to shut down Vagrant, run ``vagrant halt``.

If you want to poke around in the Vagrant box, run ``vagrant ssh``. You don't have to enter any passwords or unlock any ssh keys - Vagrant controls those itself.

If you're unable to log in, try powering the Vagrant machine off through the Virtualbox graphical user interface, or by finding the VM name in ``VBoxManage list runningvms`` and using it in ``VBoxManage controlvm <name|uuid> acpipowerbutton``

Some notes about deploy-tools and Fabric
----------------------------------------

The full list of supported commands can be found in `the deploy-tools documentation <https://github.com/INN/deploy-tools/blob/master/COMMANDS.md>`_.

Most fabric commands take the form of ::

	fab <environment> <branch> <action>
	fab <action that defines its own environment>:arguments

Every command in `the list of commands <https://github.com/INN/deploy-tools/blob/master/COMMANDS.md>`_ is prefixed with ``fab``.

If you recieve an error when running your command, make sure that you have run ``workon largo-umbrella``, or the name of the Python virtualenv you are using. When run, ``workon`` will prefix your prompt: ::

	blk@oyster:~$ workon largo
	(largo)blk@oyster:~$

To exit the virtualenv, you can use the command ``deactivate``.
