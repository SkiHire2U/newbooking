#!/bin/bash

domain="nb-test.skihire2u.com"
contact="contact@gearboxgo.com"

#instructions from https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/SSL-on-amazon-linux-2.html#letsencrypt

#install prerequisites
sudo wget -r --no-parent -A 'epel-release-*.rpm' https://dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/
sudo rpm -Uvh dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/epel-release-*.rpm
sudo yum-config-manager --enable epel*

#install certbot
sudo amazon-linux-extras install epel -y
sudo yum install -y certbot python2-certbot-apache

#get certificate
sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --test-cert


#add cron job
touch /etc/cron.d/certbot_renew
echo "* * * * * webapp 0 1,13 * * * certbot renew --no-self-upgrade
# empty line" | tee /etc/cron.d/certbot_renew
