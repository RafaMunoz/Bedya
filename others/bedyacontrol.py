#!/usr/bin/python
# -*- coding: utf-8 -*-

import ConfigParser
import logging
import os
import sys
import time

import telebot

import bedya
from bedya import latch

reload(sys)
sys.setdefaultencoding('utf8')

CONFIG_FILENAME = "/etc/bedya/bedya.conf"
config = ConfigParser.ConfigParser()
config.read(CONFIG_FILENAME)

INFOBOT_FILENAME = config.get('DIRECTORY', 'infobot')
LANGUAGE_FILENAME = config.get('DIRECTORY', 'language')
LOG_FILENAME = config.get('DIRECTORY', 'log')
UPDATE = config.get('DIRECTORY', 'update')

LOG_FORMAT = "%(asctime)s - %(levelname)s - %(message)s"

logging.basicConfig(filename=LOG_FILENAME, level=logging.DEBUG, format=LOG_FORMAT)
logging.getLogger("requests").setLevel(logging.WARNING)
logging.getLogger("urllib3").setLevel(logging.WARNING)

b = bedya.BedyaControl(INFOBOT_FILENAME, LANGUAGE_FILENAME, LOG_FILENAME)


def checklatch():
    mods = b.read_fconf('["modules"]')
    try:
        for mod in mods:
            name_mod = mod["name"]
            if name_mod == "Latch":
                actmod = mod["activated"]
                if actmod == 1:
                    appid = mod["appid"]
                    key = mod["key"]
                    accid = mod["accid"]
                    api = latch.Latch(appid, key)
                    response = api.status(accid)
                    response_data = response.get_data()
                    status = 1
                    try:
                        status = response_data['operations'][appid]['status']
                    except:
                        logging.error("Error checking latch")
                    return status
                else:
                    return actmod
    except:
        logging.error("Error checking latch")


logging.info("-------------- STARTING BEDYA --------------")
fconf = b.read_config()
logging.info("Configuration file opened successfully")
language = b.read_fconf('["language"]')
logging.info("Language = " + language)
flang = b.read_language()
logging.info("Language file opened successfully")

token = b.read_fconf('["token"]')
logging.info("Token = " + token)
bot = telebot.TeleBot(token)
idResp = 0
randReb = 0
randShut = 0


@bot.message_handler()
def main(message):
    global randReb
    global idResp
    global randShut
    id_user = message.chat.id
    msj = message.text
    name = message.chat.first_name
    idmsj = message.message_id
    auth_user = b.auth(id_user)
    logging.info("USER: " + name + " ID: " + str(id_user) + " MESSAGE: " + msj)
    if auth_user == 1:
        if msj == "/start":
            b.c_start(id_user, name)
        elif msj == "/help" or msj == b.read_flang('["14"]'):
            b.c_help(id_user)
        else:
            chckltch = checklatch()
            if (chckltch == 0) or (chckltch == "on"):
                if message.reply_to_message != None:
                    if message.reply_to_message.message_id == (idResp + 1) and msj == randReb:
                        txt_send = b.read_flang('["24"]')
                        b.keyboard_home(id_user, txt_send)
                        time.sleep(5)
                        os.system('reboot')
                    elif message.reply_to_message.message_id == (idResp + 1) and msj == randShut:
                        txt_send = b.read_flang('["27"]')
                        b.keyboard_home(id_user, txt_send)
                        time.sleep(5)
                        os.system('init 0')
                    else:
                        txt_send = b.read_flang('["25"]')
                        b.keyboard_home(id_user, txt_send)

                else:
                    if msj == b.read_flang('["18"]'):
                        txt_send = b.read_flang('["29"]') + " " + b.read_flang('["44"]') + "!"
                        b.keyboard_info(id_user, txt_send)

                    elif msj == u"\u21A9":
                        txt_send = b.read_flang('["29"]') + " " + b.read_flang('["30"]') + "!"
                        b.keyboard_home(id_user, txt_send)

                    elif msj == b.read_flang('["22"]'):
                        txt_send = b.read_flang('["29"]') + " " + b.read_flang('["45"]') + "!"
                        b.keyboard_rend(id_user, txt_send)

                    elif msj == b.read_flang('["19"]'):
                        servcs = b.read_fconf('["services"]')
                        nsrvcs = len(servcs)
                        if nsrvcs == 0:
                            txt_send = b.read_flang('["39"]')
                            b.send_message(id_user, txt_send)
                        else:
                            txt_send = b.read_flang('["29"]') + " " + b.read_flang('["38"]') + "!"
                            b.keyboard_services(id_user, txt_send)

                    elif msj == "/ipprivate" or msj == b.read_flang('["23"]'):
                        b.c_ipprivate(id_user)

                    elif msj == "/ippublic" or msj == b.read_flang('["15"]'):
                        b.c_ippublic(id_user)

                    elif msj == "/hostname" or msj == b.read_flang('["12"]'):
                        b.c_hostname(id_user)

                    elif msj == "/date" or msj == b.read_flang('["28"]'):
                        b.c_date(id_user)

                    elif msj == "/ram" or msj == b.read_flang('["31"]'):
                        b.c_ram(id_user)

                    elif msj == "/hdd" or msj == b.read_flang('["35"]'):
                        b.c_hdd(id_user)

                    elif msj == "/servicestatus" or msj == b.read_flang('["40"]'):
                        b.c_servicestatus(id_user)

                    elif msj == "/reboot" or msj == b.read_flang('["16"]'):
                        randReb = bedya.n_rand()
                        txt_send = b.read_flang('["20"]') + u" \u26A0" + "\n" + b.read_flang('["21"]') + ": " + randReb
                        b.reply(id_user, txt_send)
                        idResp = idmsj

                    elif msj == "/shutdown" or msj == b.read_flang('["17"]'):
                        randShut = bedya.n_rand()
                        txt_send = b.read_flang('["26"]') + u" \u26A0" + "\n" + b.read_flang('["21"]') + ": " + randShut
                        b.reply(id_user, txt_send)
                        idResp = idmsj

                    elif msj == "/uptime" or msj == b.read_flang('["37"]'):
                        b.c_uptime(id_user)

                    elif msj == "/rxtx" or msj == "Rx/Tx":
                        b.c_info_rxtx(id_user)

                    elif msj == "/update":
                        command = "python " + UPDATE + " now"
                        bedya.new_process(command)

                    else:
                        sof = b.c_service_onoff(id_user, msj)
                        if sof == 0:
                            txt_send = b.read_flang('["11"]') + ". " + b.read_flang('["10"]')
                            b.send_sticker(id_user, "CAADBAADFwADn0npAAE0qQvbcYmFbQI")
                            bot.reply_to(message, txt_send)
            else:
                b.send_sticker(id_user, "CAADBAADGQADn0npAAGEG6hnL_HfqQI")
                txt_send = b.read_flang('["43"]') + u" \u203C"
                b.send_message(id_user, txt_send)
                msjlog = "Connection blocked by latch. ID: " + str(id_user) + " NAME: " + name
                logging.info(msjlog)
    else:
        if b.read_fconf('["infodenied"]') == 1:
            b.send_sticker(id_user, "CAADBAADGAADn0npAAHBxdQREBS9lQI")
            msj_auth = b.read_flang('["4"]') + u" \U0001F6AB"
            b.send_message(id_user, msj_auth)
        b.warn(id_user, msj, name)
        msjlog = "User without authorization. ID: " + str(id_user) + " NAME: " + name + " MESSAGE: " + msj
        logging.info(msjlog)


bot.polling(none_stop=True)
