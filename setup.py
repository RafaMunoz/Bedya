#!/usr/bin/env python
import os
import sys
from io import open
from setuptools import setup


def readme():
    with open('README.md', encoding='utf-8') as f:
        return f.read()


def get_files(dir_name):
    """Simple directory walker"""
    return [(os.path.join('.', d), [os.path.join(d, f) for f in files]) for d, _, files in os.walk(dir_name)]


def post_install():
    post_script = 'bedya_postinstall.sh'
    if "install" in sys.argv:
        print 'Running post install'
        comand = "sh " + post_script + " install"
        os.system(comand)
    elif "uninstall" in sys.argv:
        print 'Running post uninstall'
        comand = "sh " + post_script + " uninstall"
        os.system(comand)
    else:
        print "Not running post install/uninstall"


setup(name='Bedya',
      version='1.0.0',
      description='Bedya is the first application for managing IT devices for Linux distribution via Telegram.',
      long_description=readme(),
      author='Rafa Munoz',
      author_email='rafa93m@gmail.com',
      url='http://www.bedya.es',
      packages=['bedya'],
      license='GPL2',
      install_requires=['pyTelegramBotAPI'],
      data_files=get_files('others'),
      keywords='bedya control telegram bot api tools servers raspberry bedya.es management it',
      classifiers=[
          'Programming Language :: Python',
          'Programming Language :: Python :: 2.7',
          'Programming Language :: Python :: 3',
          'Programming Language :: Python :: 3.1',
          'Programming Language :: Python :: 3.2',
          'Programming Language :: Python :: 3.3',
          'Programming Language :: Python :: 3.4',
          'Programming Language :: Python :: 3.5',
          'Environment :: Console',
          'License :: OSI Approved :: GNU General Public License v2 (GPLv2)'
      ]
      )
post_install()
