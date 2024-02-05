#!/bin/bash
# this must be done in postdeploy so that nginx config doesn't get overwritten by Elastic Beanstalk

# ---- Configuration ----
domain="newbooking.skihire2u.com"
contact="contact@gearboxgo.com"
bucket="ssl-certificates-skihire2u"
test_mode=true
# -----------------------

sed -i 's/http {/http {\n    server_names_hash_bucket_size 128;/' /etc/nginx/nginx.conf

#add cron job
function add_cron_job {
    touch /etc/cron.d/certbot_renew
    echo "* * * * * webapp 0 2 * * * certbot renew --no-self-upgrade
    # empty line" | tee /etc/cron.d/certbot_renew
}

# Temporary immediate exit so that we don't run this until dns is set up
#exit

#check if certbot is already installed
if command -v certbot &>/dev/null; then
    echo "certbot already installed"
else

    # Install certbot since it's not installed already
    # Instructions from https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/SSL-on-amazon-linux-2.html#letsencrypt

    #install prerequisites
    cd /tmp
    sudo wget -r --no-parent -A 'epel-release-*.rpm' https://dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/
    sudo rpm -Uvh dl.fedoraproject.org/pub/epel/7/x86_64/Packages/e/epel-release-*.rpm
    sudo yum-config-manager --enable epel*

    #install certbot
    sudo amazon-linux-extras install epel -y
    sudo yum install -y certbot python2-certbot-nginx

fi

if [ "$test_mode" = true ]; then
    folder="s3://${bucket}/LetsEncrypt-Staging/"
else
    folder="s3://${bucket}/LetsEncrypt/"
fi

# check if the S3 bucket already exists with a certificate
if [ -n "$(aws s3 ls $folder)" ]; then

    # download and install certificate from existing S3 bucket
    echo "$folder exists."
    sudo rm -rf /etc/letsencrypt/*
    sudo aws s3 cp ${folder}backup.tar.gz /tmp
    sudo tar -xzvf /tmp/backup.tar.gz --directory /
    if [ "$test_mode" = true ]; then
        sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --reinstall --redirect --test-cert --expand
    else
        sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --reinstall --redirect --expand
    fi
    systemctl reload nginx

    # re-uploading the certificate in case of renewal during certbot installation
    tar -czvf /tmp/backup.tar.gz /etc/letsencrypt/*
    aws s3 cp /tmp/backup.tar.gz ${folder}

    add_cron_job
    exit
fi

# obtain, install, and upload certificate to S3 bucket since it does not exist already
if [ "$test_mode" = true ]; then
    #get a test mode cert
    sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect --test-cert
else
    #get a production cert
    sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect
fi

tar -czvf /tmp/backup.tar.gz /etc/letsencrypt/*
aws s3 cp /tmp/backup.tar.gz ${folder}

add_cron_job
