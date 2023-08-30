#!/bin/bash

# ---- Configuration ----
domain="newbooking.skihire2u.com"
contact="contact@gearboxgo.com"
bucket="ssl-certificates-skihire2u"
folder=$(aws s3 ls s3://ssl-certificates-skihire2u/LetsEncrypt/newbooking.skihire2u.com)
test_mode=false
# -----------------------

# aws s3 sync /etc/letsencrypt/live/aggregate.thomannasphalt.com s3://ssl-certificates-thomann/LetsEncrypt/aggregate.thomannasphalt.com // upload to S3
# aws s3 sync s3://ssl-certificates-thomann/LetsEncrypt/aggregate.thomannasphalt.com /etc/letsencrypt/live/aggregate.thomannasphalt.com // download from S3

# Temporary immediate exit so that we don't run this until dns is set up
#exit

# this must be done in postdeploy so that nginx config doesn't get overwritten by Elastic Beanstalk

#check if certbot is already installed
if command -v certbot &>/dev/null; then
    echo "certbot already installed"

    # [CHECK IF BUCKET FOLDER EXISTS W/ CERT]
    if [ -z "$folder" ]; then
        echo "Folder does not exist."
        # [GENERATE AND UPLOAD CERT SINCE IT DOESN'T EXIST]
        if [ "$test_mode" = true ]; then
            #get a test mode cert
            sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect --test-cert
            aws s3 sync /etc/letsencrypt/live/${domain} s3://${bucket}/LetsEncrypt/${domain}
        else
            #get a production cert
            sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect
            aws s3 sync /etc/letsencrypt/live/${domain} s3://${bucket}/LetsEncrypt/${domain}
        fi
    else
        # [OR DOWNLOAD EXISTING CERT FROM AWS BUCKET]
        echo "Folder exists."
        aws s3 sync s3://${bucket}/LetsEncrypt/${domain} /etc/letsencrypt/live/${domain}
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
        aws s3 sync /etc/letsencrypt/live/${domain} s3://${bucket}/LetsEncrypt/${domain}
        exit
    else
        # Certificate is installed successfully 
        echo "Production certificate is installed"
    fi
fi

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
    echo "Folder does not exist."
    # [GENERATE CERT IF IT DOESN'T EXIST]
    if [ "$test_mode" = true ]; then
        #get a test mode cert
        sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect --test-cert
        aws s3 sync /etc/letsencrypt/live/${domain} s3://${bucket}/LetsEncrypt/${domain}
    else
        #get a production cert
        sudo certbot -n -d ${domain} --nginx --agree-tos --email ${contact} --redirect
        aws s3 sync /etc/letsencrypt/live/${domain} s3://${bucket}/LetsEncrypt/${domain}
    fi
else
    echo "Folder exists."
    # [OR DOWNLOAD EXISTING CERT FROM AWS BUCKET]
    aws s3 sync s3://${bucket}/LetsEncrypt/${domain} /etc/letsencrypt/live/${domain}
fi

#add cron job
touch /etc/cron.d/certbot_renew
echo "* * * * * webapp 0 2 * * * certbot renew --no-self-upgrade
# empty line" | tee /etc/cron.d/certbot_renew