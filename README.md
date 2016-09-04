#fiki

The File based Wiki

## Description
This project aimed to be just a simple container to link "externals" html pages.
The target for us was to collect all asciidoc generated pages (using the
Raskiidoc project https://github.com/llicour/raskiidoc) and have an easy
template to collect, show and browse all the resource.

For that reason we based the "Wiki" (Fiki) on file system. It looks in the data
folder looking for arguments to display (a fiki argument is a folder); inside
each arguments you can have as many html files as you want.
Fiki will read the '<title>' tag of any page and display it on the Fiki homepage
(inside the correct argument).

## Installation and configuration
To install Fiki, you just need to copy the project sources into a php enabled
web server (PHP5 required).
Then you need to configure the installation creating a **configuration.ini**
based on the example.

```bash
mkdir /etc/fiki
cp sample.configuration.ini /etc/fiki/configuration.ini
```
and changing, inside the new file, the data directory and the authentication
method to use.

To reference the new configuration.ini file, you need to specify the path where
you stored it inside the  **config.php** file.

## Argument configuration
For any argument you can create a YAML file to add information for your
arguments.
By default the folder name is shown on the page as argument name, but, creating
inside the argument folder a file named **metadata.yaml** allow you to add:

*title*: the name of the argument to show (shown despite of folder name)
description: argument content description

Example yaml file:
```yaml
---
title: My first argument
description: my argument description
```

## Requirements
To use the Arguments description Yaml file, you should install the PHP Yaml
librery:

php-pecl-yaml : Support for YAML 1.1 serialization using the LibYAML


## FikiBootstrap Backend for Asciidoc Build
You can get Fiki template for all the asciidoc autogenerated pages, to get an
armony in your wiki. The backend project is located on:

https://github.com/mmornati/fikiboostrap

You can follow the instructions on the project readme file to install it.

