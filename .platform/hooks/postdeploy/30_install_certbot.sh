#!/bin/bash

# ---- Configuration ----
domain="newbooking.skihire2u.com"
contact="contact@gearboxgo.com"
bucket="ssl-certificates-skihire2u"
folder=$(aws s3 ls s3://ssl-certificates-skihire2u/LetsEncrypt/)
test_mode=false
# -----------------------

# Temporary immediate exit so that we don't run this until dns is set up
#exit

# this must be done in postdeploy so that nginx config doesn't get overwritten by Elastic Beanstalk

#check if certbot is already installed
if command -v certbot &>/dev/null; then
    echo "certbot already installed"

    # [CHECK IF BUCKET FOLDER EXISTS W/ CERT]
    if [ -z "$folder" ]; then
        echo "$folder does not exist."
        # [GENERATE AND UPLOAD CERT SINCE IT DOESN'T EXIST]
        if [ "$test_mode" = true ]; then
            #get a test mode cert
            sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect --test-cert
            tar -czvf backup.tar.gz /etc/letsencrypt/*
            aws s3 cp /backup.tar.gz s3://${bucket}/LetsEncrypt/
        else
            #get a production cert
            sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect
            tar -czvf backup.tar.gz /etc/letsencrypt/*
            aws s3 cp /backup.tar.gz s3://${bucket}/LetsEncrypt/
        fi
    else
        # [OR DOWNLOAD EXISTING CERT FROM AWS BUCKET]
        echo "$folder exists."
        sudo rm -rf /etc/letsencrypt/*
        sudo aws s3 cp s3://${bucket}/LetsEncrypt/backup.tar.gz /
        sudo tar -xzvf /backup.tar.gz --directory /
        sudo certbot -d ${domain} --reinstall --redirect
        systemctl restart nginx
    fi

    # check if the certificate is staging or production
    # look for the word "STAGING" in the certificate info
    # bash returns 0 if it finds a match
    openssl x509 -in /etc/letsencrypt/live/${domain}/cert.pem -text -noout | grep -q STAGING
    result=$?
    if [ "$result" = 0 ]; then
        is_staging=true
    else
        is_staging=false
    fi

    if [ "$test_mode" = false ] && [ "$is_staging" = true ]; then
        # Current certificate is from staging but we need production
        # Force install a production certificate
        echo "Staging SSL certificate is installed, but we need production"
        echo "Forcing SSL certificate renewal..."

        sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect --force-renewal
        tar -czvf backup.tar.gz /etc/letsencrypt/*
        aws s3 cp /backup.tar.gz s3://${bucket}/LetsEncrypt/
    else
        # Certificate is installed successfully 
        echo "Certificate is installed"
    fi
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

    # [CHECK IF BUCKET FOLDER EXISTS W/ CERT]
    if [ -z "$folder" ]; then
        echo "$folder does not exist."
        # [GENERATE CERT IF IT DOESN'T EXIST]
        if [ "$test_mode" = true ]; then
            #get a test mode cert
            sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect --test-cert
            tar -czvf backup.tar.gz /etc/letsencrypt/*
            aws s3 cp /backup.tar.gz s3://${bucket}/LetsEncrypt/
        else
            #get a production cert
            sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect
            tar -czvf backup.tar.gz /etc/letsencrypt/*
            aws s3 cp /backup.tar.gz s3://${bucket}/LetsEncrypt/
        fi
    else
        echo "$folder exists."
        # [OR DOWNLOAD EXISTING CERT FROM AWS BUCKET]
        sudo rm -rf /etc/letsencrypt/*
        sudo aws s3 cp s3://${bucket}/LetsEncrypt/backup.tar.gz /
        sudo tar -xzvf /backup.tar.gz --directory /
        sudo certbot -d ${domain} --reinstall --redirect
        systemctl restart nginx
    fi
fi

#add cron job
touch /etc/cron.d/certbot_renew
echo "* * * * * webapp 0 2 * * * certbot renew --no-self-upgrade
# empty line" | tee /etc/cron.d/certbot_renew
