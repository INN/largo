#!/usr/bin/python

# This searches the Largo project for PHP files, excluding those third-party
# source files in /lib, /js, and /node_modules, and runs the command
# `doxphp < input.php | doxphp2sphinx > output.rst` to extract PHPDocs to
# RST file format suitable for use with Sphinx and ReadTheDocs.
import os
import subprocess
import json

class ApiDocGenerator(object):

    index_page_data = []

    def clean_dox_data(self, response):
        json_data = json.loads(response)

        items = []
        for item in json_data:
            if item['description'] == '':
                continue
            else:
                items.append(item)

        return items

    def write_rst(self, src, items):
        # A file with src path ../404.php should output to api/404.rst
        dest = src.replace('..', 'api').replace('.php', '.rst')
        proc = subprocess.Popen(
            "doxphp2sphinx", stdout=subprocess.PIPE, stdin=subprocess.PIPE, shell=True)
        output, err = proc.communicate(input=json.dumps(items))

        output = output.strip()
        if output != '':
            print "Write file: %s" % dest
            if not os.path.exists(os.path.dirname(dest)):
                os.makedirs(os.path.dirname(dest))

            with open(dest, 'w+') as f:
                filepath = src.lstrip('../')
                output = "%s\n%s\n\n%s" % (filepath, len(filepath) * '=', output)
                f.write(output)

            self.add_index_page_data(filepath, dest)
        else:
            print "Skipping: %s" % src

    def create_index_page(self):
        heading = "Function reference by file"
        tmpl = "%s\n%s\n\n" % (heading, len(heading) * '=')

        for filepath in self.index_page_data:
            tmpl += "* `%s <%s>`_\n" % (filepath, filepath.replace('php', 'html'))

        with open('api/index.rst', 'w+') as f:
            f.write(tmpl)

    def add_index_page_data(self, filepath, dest):
        self.index_page_data.append(filepath)

    def main(self):
        # Since this script lives in the /docs folder, we need to search from ../
        for dirpath, dirnames, filenames in os.walk('../'):
            # We don't care about PHP files found in 3rd-party library folders
            excludes = ['../lib', '../js', '../node_modules', '../tests']
            if any(exclude in dirpath for exclude in excludes):
                continue

            for filename in filenames:
                if os.path.splitext(filename)[-1] == '.php':
                    src = os.path.join(dirpath, filename)
                    response = subprocess.check_output(['doxphp < %s' % src], shell=True)
                    items = self.clean_dox_data(response)
                    self.write_rst(src, items)

        self.create_index_page()


if __name__ == '__main__':
    process = ApiDocGenerator()
    process.main()
