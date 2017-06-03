#!/usr/bin/python
# -*- coding: utf-8 -*-

import sys
import time
import bedya
import logging
import ConfigParser

reload(sys)
sys.setdefaultencoding('utf8')

CONFIG_FILENAME = "/etc/bedya/bedya.conf"
config = ConfigParser.ConfigParser()
config.read(CONFIG_FILENAME)

INFOBOT_FILENAME = config.get('DIRECTORY', 'infobot')
LANGUAGE_FILENAME = config.get('DIRECTORY', 'language')
LOG_FILENAME = config.get('DIRECTORY', 'log')

LOG_FORMAT = "%(asctime)s - %(levelname)s - %(message)s"

b = bedya.BedyaControl(INFOBOT_FILENAME, LANGUAGE_FILENAME, LOG_FILENAME)
logging.getLogger("requests").setLevel(logging.WARNING)
logging.getLogger("urllib3").setLevel(logging.WARNING)

b.loginfo("Checking BedyaStart")
statusBedyaStart = b.mod_act("BedyaStart")

b.loginfo("BedyaStart = " + str(statusBedyaStart))
if statusBedyaStart == 1:
    fecha = time.strftime("%H:%M:%S")
    time.sleep(20)
    b.notification_adm_sticker("CAADBAADFgADn0npAAHZKRa4BO9moAI")
    textoEnvio = b.read_flang('["1"]') + " " + b.read_flang('["2"]') + " " + b.read_flang('["3"]') + " " + str(fecha)
    b.notification_adm(textoEnvio)
    b.loginfo("Send notification BedyaStart")
