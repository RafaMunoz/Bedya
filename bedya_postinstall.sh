#!/bin/sh
# PostInstall Bedya

if [ $1 = "install" ]
then
	echo "Creating directories"
	mkdir /etc/bedya
	chmod 644 /etc/bedya
	mkdir /var/log/bedya
	chmod 755 /var/log/bedya
	mkdir /usr/local/bedya
	chmod 755 /usr/local/bedya
	mkdir 755 /usr/local/bedya/modules
	chmod 755 /usr/local/bedya/modules


	echo "Copying configuration files -> /etc/bedya"
	cp ./others/bedya.conf /etc/bedya
	chmod 644 /etc/bedya/bedya.conf
	cp ./others/infobot.json /etc/bedya
	chmod 644 /etc/bedya/bedya.conf

	echo "Copying languages files -> /etc/bedya/languages"
	cp -r ./others/languages/ /etc/bedya/

	echo "Copying service -> bedya"
	SERVICE="/etc/systemd/system/bedya.service"
	cp ./others/bedya.service $SERVICE
	chmod +x $SERVICE
	echo "Recharging services"
	systemctl enable bedya.service
	systemctl daemon-reload

	echo "Copying applications -> /usr/local/bedya"
	cp ./others/bedyacontrol.py /usr/local/bedya/bedyacontrol
	chmod +x /usr/local/bedya/bedyacontrol
	cp ./others/bedyaupdate.py /usr/local/bedya/bedyaupdate
	chmod +x /usr/local/bedya/bedyaupdate
	cp ./others/bedyastart.py /usr/local/bedya/modules/
	chmod +x /usr/local/bedya/modules/bedyastart.py

	echo "Creating update task"
	crontab -l  > /tmp/tskbedya.txt
	echo "*/10 * * * * /usr/local/bedya/bedyaupdate" >> /tmp/tskbedya.txt
	crontab -u root /tmp/tskbedya.txt
	echo ""
	echo "*************************************************************************"
	echo "**                       Installation Complete!!                       **"
	echo "**                                                                     **"
	echo "** Modify '/etc/bedya/infobot.json' and run 'sudo service bedya start' **"
	echo "*************************************************************************"


elif [ $1 = "uninstall" ]
then
	echo "Stopping service Bedya"
	systemctl stop bedya
	echo "Stopping Bedya Update"
	pkill bedyaupdate
	echo "Deleting files"
	rm -r /etc/bedya/
	rm -r /usr/local/bedya
	rm /etc/systemd/system/bedya.service
	echo "Deleting update task "
	crontab -l  | grep -v "/usr/local/bedya/bedyaupdate" > /tmp/tskbedya.txt
	crontab -u root /tmp/tskbedya.txt

else
	echo "Invalid argument"
fi