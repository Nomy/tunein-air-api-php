# TuneIn Air API PHP Script for IceCast 2.4+ with AutoDJ support
# (c) 2016: Nomy - https://hellclan.co.uk/

# TuneIn Air API Docs: http://tunein.com/broadcasters/api/

# Add this script to crontab to run every 15 seconds (php-cli dependant):
# * * * * * for i in 0 1 2 3 ; do php /home/web/domains/domain.com/public_html/tunein-cron.php & sleep 15; done
# or (using wget to run a script on the web):
# * * * * * for i in 0 1 2 3 ; do wget http://domain.com/tunein-cron.php -O /dev/null & sleep 15; done
