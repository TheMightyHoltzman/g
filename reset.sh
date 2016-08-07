#! /bin/bash

~/dev/glog/app/console cache:clear --env=prod

chmod 666 -R annotations
chmod 666 -R vich_uploader
chmod 666 -R twig
