#!/bin/bash

domain="nb-test.skihire2u.com"
contact="contact@gearboxgo.com"

# this must be done in postdeploy so that nginx config doesn't get overwritten by Elastic Beanstalk

#check if certbot is already installed
if command -v certbot &> /dev/null
then
    echo "certbot already installed - reinstalling existing certificate"
    certbot -n -d ${domain} --nginx --reinstall --redirect
    exit
fi

#instructions from https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/SSL-on-amazon-linux-2.html#letsencrypt

#install prerequisites
cd /tmp
sudo wget -r --no-parent -A 'epel-release-*.rpm' https://dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/
sudo rpm -Uvh dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/epel-release-*.rpm
sudo yum-config-manager --enable epel*

#install certbot
sudo amazon-linux-extras install epel -y
sudo yum install -y certbot python2-certbot-nginx

# get certificate
# test mode
#sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect --test-cert
sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect

#add cron job
touch /etc/cron.d/certbot_renew
echo "* * * * * webapp 0 2 * * * certbot renew --no-self-upgrade
# empty line" | tee /etc/cron.d/certbot_renew
