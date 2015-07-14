Setting up Largo to contribute documentation
============================================

If you just want to help us write documentation, you don't have to go through `the complete setup process. <setup.html>`_

Once you've completed this recipe, you'll be able to:

- rebuild the documentation
- preview your edits in a browser
- rebuild the translation files.
- push your edits to GitHub and request that we incorporate them in Largo

This presumes that you're familiar with the command line, and are using OSX or another UNIX-like system.

Setting up
----------

This presumes that you're familiar with the command line, and are using OSX or another UNIX-like system.

1. `Fork INN/Largo <https://github.com/INN/Largo#fork-destination-box>`_ into your own GitHub account.
2. Clone your branch: ::

	git clone git@github.com:you/Largo.git

3. Check out the `write-the-docs` branch ::

	git checkout write-the-docs

4. Install the required dependencies

	We use some Python libraries to generate our documentation. To install the requirements: ::

		cd docs

	Not required, but it's recommended to `install and use virtualenv <https://jamie.curle.io/blog/installing-pip-virtualenv-and-virtualenvwrapper-on-os-x/>`_: ::

		mkvirtualenv largo-docs
		workon largo-docs

	Then: ::

		pip install -r requirements.txt

5. Our API docs/function reference uses doxphp to generate documentation based on the comments embedded in Largo's source code. You'll need to install doxphp to generate API docs.

	- Install with PEAR: ::

		pear channel-discover pear.avalanche123.com
		pear install avalanche123/doxphp-beta

	- Install with git. This requires you to know where your ``bin`` directory is, and may require ``sudo``. ::

		git clone https://github.com/avalanche123/doxphp.git
		cd doxphp/bin
		mv doxph* /path/to/bin/

6. With all dependencies installed, running the generator is as simple as: ::

		cd docs
		make php && make html


But if you don't want to have to manually recreate the documentation every time you save a file, you can run ``grunt watch`` from the Largo directory. This command only rebuilds documentation, though, and doesn't recompile the API docs.

7. You can view the generated docs in the `docs/_build/html` directory

	There are two main ways of doing this. First, you can view the files with a browser as files. It won't be the best experience. 

	The other, better option is to run a sinple web server in the directory that the HTML documentation was output to, and then view them normally as a website in your browser: ::

		cd docs/_build/html
		python -m SimpleHTTPServer 8081


The (ideal) procedure for contributing documentation
----------------------------------------------------

1. `Choose an issue <https://github.com/INN/Largo/milestones/Write%20The%20Docs>`_
2. Comment on the issue that you're taking it.
3. Create a new branch with your contributions, named after the issue:

	git checkout -b 613-partials-sticky-posts

4. Make your changes
5. Commit and push:

	git commit
	git push -u origin 613-partials-sticky-posts

6. Create a pull request from your branch to INN/Largo
    - How to make a `PR on GitHub <https://help.github.com/articles/creating-a-pull-request/>`_
    - If it's a big PR, please `make sure it's well-documented </how-to-work-with-us/pull-requests.md>`_. Thanks!

Resources for writing documentation
-----------------------------------

- `Sphinx' PHP domain-specific markup <http://mark-story.com/posts/view/sphinx-phpdomain-released>`_
- `Sphinx reStructuredText primer and quickstart guide <http://sphinx-doc.org/rest.html>`_
