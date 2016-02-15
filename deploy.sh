#!/usr/bin/env bash
sudo rm -R /var/www/*
sudo cp -R html /var/www
sudo chmod -R a+rx /var/www/html