#!/usr/bin/python
# -*- coding: utf-8 -*-

import os
import sys
import json
import time
import bedya
import requests
import logging
import ConfigParser

reload(sys)
sys.setdefaultencoding('utf8')

if __name__ == '__main__':

    CONFIG_FILENAME = "/etc/bedya/bedya.conf"
    config = ConfigParser.ConfigParser()
    config.read(CONFIG_FILENAME)

    INFOBOT_FILENAME = config.get('DIRECTORY', 'infobot')
    LANGUAGE_FILENAME = config.get('DIRECTORY', 'language')
    LOG_FILENAME = config.get('DIRECTORY', 'log')
    URL = config.get('URL', 'updates')

    b = bedya.BedyaControl(INFOBOT_FILENAME, LANGUAGE_FILENAME, LOG_FILENAME)
    logging.getLogger("requests").setLevel(logging.WARNING)
    logging.getLogger("urllib3").setLevel(logging.WARNING)
    token = b.read_fconf('["token"]')
    idU = b.read_fconf('["admin"][0]["id"]')
    URL = URL + idU + "/" + token
    statusRequest = 0
    comando = "ps -ef | grep -v grep | grep bedyaupdate | wc -l"
    procesos = os.popen(comando).read()
    
    if "now" in sys.argv:
        b.loginfo("Checking Updates")
    else:
        if int(procesos) > 2:
            sys.exit(1)
        time.sleep(int(b.read_fconf('["control"]')))

    try:
        response = requests.get(URL)
        statusRequest = 1
    except:
        b.logerror("Connection refused")
        sys.exit(1)

    if statusRequest == 1:
        if response.status_code == 200:
            requestJson = json.loads(response.content)

            try:
                status = requestJson["ok"]
            except:
                b.logerror("Error reading ok parameter")
                sys.exit(1)

            if not status:
                b.logerror(response.content)
                sys.exit(1)

            else:
                update = int(requestJson["infobot"])
                updateconf = int(config.get('VERSIONS', 'infobot'))

                download = str(requestJson["downloads"])
                downloadconf = str(config.get('VERSIONS', 'downloads'))

                if update > updateconf:
                    infobot = json.dumps(requestJson["configuration"], sort_keys=True, indent=4, separators=(',', ': '))

                    statusBSold = b.mod_act("BedyaStart")

                    outfile = open(INFOBOT_FILENAME + ".json", 'w')
                    outfile.write(infobot)
                    outfile.close()

                    statusBedyaStart = b.mod_act("BedyaStart")
                    if statusBedyaStart == 1 and statusBSold == 0:
                        os.system("crontab -l  > /tmp/tskbedya.txt")
                        os.system('echo "@reboot python /usr/local/bedya/modules/bedyastart.py" >> /tmp/tskbedya.txt')
                        os.system("crontab -u root /tmp/tskbedya.txt")
                    elif statusBedyaStart == 0 and statusBSold == 1:
                        os.system("crontab -l  > /tmp/tskbedya.txt")
                        os.system('crontab -l  | grep -v "@reboot /usr/local/bedya/modules/bedyastart.py" > /tmp/tskbedya.txt')
                        os.system("crontab -u root /tmp/tskbedya.txt")

                    config.set('VERSIONS', 'infobot', update)
                    with open(CONFIG_FILENAME, 'w') as configfile:
                        config.write(configfile)

                    msj = b.read_flang('["46"]') + u" \U0001F4C4"
                    b.notification_adm(msj)
                    b.loginfo("Configuration file successfully updated")

                if download > downloadconf:
                    urlDownload = config.get('URL', 'downloads')

                    try:
                        fileupdate = "bedyaUpdate_" + str(download) + ".zip"
                        directoryDownload = "/tmp/"

                        url = urlDownload + fileupdate
                        r = requests.get(url)
                        directoryupdate = directoryDownload + fileupdate

                        with open(directoryupdate, "wb") as code:
                            code.write(r.content)

                        comando = "unzip " + directoryupdate + " -d " + directoryDownload
                        os.system(comando)
                        b.loginfo("Correct unzipped update file")

                        b.loginfo("Run file update")
                        command = "sh " + directoryDownload + "bedyaUpdate_" + str(download) + "/update.sh"
                        bedya.new_process(command)

                        config.set('VERSIONS', 'downloads', download)
                        with open(CONFIG_FILENAME, 'w') as configfile:
                            config.write(configfile)

                        b.loginfo("Finish update")

                    except:
                        b.logerror("Error updating Bedya")

        else:
            b.logerror(response.status_code)
            sys.exit(1)
