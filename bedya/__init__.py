#!/usr/bin/python
# -*- coding: utf-8 -*-

import os
import sys
import json
import logging
import socket
import time
import random
import subprocess
import telebot
from telebot import types

LOG_FORMAT = '%(asctime)s - %(levelname)s - %(message)s'


# Function to randomly create a restart/shutdown code
def n_rand():
    rand = ""
    for x in range(4):
        rand += str(random.randint(0, 9))
    return rand


# Launch a new independent process without waiting for it to end.
def new_process(command):
    subprocess.Popen(command, shell=True)


class BedyaControl(object):

    def __init__(self, c_name, l_name, l_file):
        self.infobotFile = c_name
        self.languageFile = l_name
        self.logFile = l_file
        logging.basicConfig(filename=self.logFile, level=logging.DEBUG, format=LOG_FORMAT)
        self.token = self.read_fconf('["token"]')
        self.b = telebot.TeleBot(self.token)

    # Opens and reads the bot configuration file.
    def read_config(self):
        try:
            with open(self.infobotFile + ".json") as f:
                config = json.loads(f.read())
            return config
        except:
            logging.error("Error opening configuration file")
            sys.exit(1)

    # Search in the configuration for the index that we parse.
    def read_fconf(self, index):
        try:
            fconf = self.read_config()
            txt = eval("fconf" + index)
            return txt
        except:
            logging.error("Error reading configuration file")
            sys.exit(1)

    # Search and reads the file with the corresponding language.
    def read_language(self):
        try:
            text = self.read_fconf('["language"]')
            with open(self.languageFile + text + ".json") as f:
                language = json.loads(f.read())
            return language
        except:
            logging.error("Error opening language file")
            sys.exit(1)

    # Look in the language file for the index that we parse
    def read_flang(self, index):
        try:
            flang = self.read_language()
            txt = eval("flang" + index)
            return txt
        except:
            logging.error("Error reading language file")
            sys.exit(1)

    # Saves an error log
    def logerror(self, text):
        logging.error(text)

    # Saves an info log
    def loginfo(self, text):
        logging.info(text)

    # Check if the id that we passed belongs to an administrator, returns 1 if it is an administrator.
    def auth(self, id_user):
        admins = self.read_fconf('["admin"]')
        access = 0
        try:
            for admin in admins:
                id_admin = admin["id"]
                if id_admin == str(id_user):
                    access = 1
                    return access
            return access
        except:
            logging.error("Error reading configuration file")
            sys.exit(1)

    # --------------------------------------------------------------

    def send_message(self, id_user, msj):
        self.b.send_message(id_user, msj)
        text = "Send Message: " + str(id_user)
        self.loginfo(text)

    def send_sticker(self, id_user, stck):
        self.b.send_sticker(id_user, stck)
        text = "Send Sticker: " + str(id_user)
        self.loginfo(text)

    def send_keyboard(self, id_user, txt, jsn):
        self.b.send_message(id_user, txt, None, None, jsn)

    # Message to respond. Reset and shutdown.
    def reply(self, id_user, txt):
        markup = types.ForceReply(selective=False)
        self.b.send_message(id_user, txt, reply_markup=markup)
        text = "Send Reply Message: " + str(id_user)
        self.loginfo(text)

    # Keyboard Home
    def keyboard_home(self, id_user, txt):
        try:
            json_lng = self.read_language()
            r1 = [json_lng["18"], json_lng["19"]]
            r2 = [json_lng["22"]]
            r3 = [json_lng["16"], json_lng["17"], json_lng["14"]]
            keyboard = [r1, r2, r3]
            news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
            self.send_keyboard(id_user, txt, json.dumps(news_keyboard))
        except:
            logging.error("Error send Keyboard Home")

    # Keyboard information
    def keyboard_info(self, id_user, txt):
        r1 = [self.read_flang('["23"]'), self.read_flang('["15"]')]
        r2 = [self.read_flang('["12"]'), self.read_flang('["37"]'), self.read_flang('["28"]')]
        r3 = [u"\u21A9"]
        keyboard = [r1, r2, r3]
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        self.send_keyboard(id_user, txt, json.dumps(news_keyboard))

    # Keyboard performance
    def keyboard_rend(self, id_user, txt):
        r1 = ["RAM", "HDD", "Rx/Tx"]
        r2 = [u"\u21A9"]
        keyboard = [r1, r2]
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        self.send_keyboard(id_user, txt, json.dumps(news_keyboard))

    # Keyboard services
    def keyboard_services(self, id_user, txt):
        servcs = self.read_fconf('["services"]')
        nsrvcs = len(servcs)
        if nsrvcs >= 9:
            nsrvcs = 9
        r1 = []
        r2 = []
        r3 = []
        keyboard = []
        r4 = [self.read_flang('["40"]'), u"\u21A9"]
        for i in range(nsrvcs):
            if i <= 2:
                r1.append(servcs[i]["nb"])
            elif 3 <= i <= 5:
                r2.append(servcs[i]["nb"])
            else:
                r3.append(servcs[i]["nb"])
        if len(r1) > 0:
            keyboard.append(r1)
        if len(r2) > 0:
            keyboard.append(r2)
        if len(r3) > 0:
            keyboard.append(r3)
        keyboard.append(r4)
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        self.send_keyboard(id_user, txt, json.dumps(news_keyboard))

    # --------------------------------------------------------------
    # Sending welcome message
    def c_start(self, id_user, name):
        self.send_sticker(id_user, "CAADBAADFwADn0npAAE0qQvbcYmFbQI")
        hostname = socket.gethostname()
        txt_send = self.read_flang('["8"]') + " " + name + "!!\n" + self.read_flang(
            '["9"]') + " '" + hostname + "'. " + self.read_flang('["10"]')
        self.keyboard_home(id_user, txt_send)

    # Sending hostname
    def c_hostname(self, id_user):
        try:
            hostname = socket.gethostname()
            txt_send = u"\U0001F4BB " + hostname
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error read Hostname")

    # Sending date
    def c_date(self, id_user):
        try:
            date = time.strftime("%A %d %b %Y %H:%M:%S")
            txt_send = u"\U0001F4C5 " + date
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error read Date")

    # Sending all available commands.
    def c_help(self, id_user):
        comds = self.read_flang('["comandos"]')
        txt_send = self.read_flang('["13"]') + "\n\n"
        try:
            for com in comds:
                c = com["c"]
                d = com["d"]
                txt_send += "/" + c + ": " + d + "\n"
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error reading language file")
            sys.exit(1)

    # Sending all the ip addresses of the network cards in the config.
    def c_ipprivate(self, id_user):
        txt_send = u"\u303D " + self.read_flang('["23"]') + "\n"
        try:
            interfaces = self.read_fconf("['interfaces']")
            for interface in interfaces:
                comando = "ifconfig " + interface
                try:
                    ipv4 = os.popen(comando).read().split("inet ")[1].split(" ")[0]
                    txt_send += " - " + interface + " " + ipv4 + "\n"
                except IndexError:
                    txt_send += " - " + interface + " ----------\n"
                    logging.error("Error checking " + interface)

            self.send_message(id_user, txt_send)
        except:
            logging.error("Error checking IPPrivate")

    # Sending the ip address publishes output to the internet.
    def c_ippublic(self, id_user):
        try:
            ip = os.popen('curl icanhazip.com').read()
            txt_send = self.read_flang('["15"]') + ": " + ip
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error checking IPPublic")

    # Reading the ram data.
    def c_ram(self, id_user):
        try:
            mem_total = int(os.popen("cat /proc/meminfo | grep MemTotal |awk '{ print $2 }'").read()) / 1024
            mem_free = int(os.popen("cat /proc/meminfo | grep MemFree |awk '{ print $2 }'").read()) / 1024
            mem_avail = int(os.popen("cat /proc/meminfo | grep MemAvailable |awk '{ print $2 }'").read()) / 1024
            txt_send = u"\U0001F4C8 " + self.read_flang('["31"]') + " - MB\n" + self.read_flang('["32"]') + ": " + str(
                mem_total) + "\n" + self.read_flang('["33"]') + ": " + str(mem_avail) + "\n" + self.read_flang(
                '["34"]') + ": " + str(mem_free)
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error read memory RAM")

    # Reading the hdd data.
    def c_hdd(self, id_user):
        try:
            fila = os.popen("df -h / | tail -n 1 |awk '{print $2,$3,$4,$5}'|tr -d '\n'").read()
            lista = fila.split(' ')
            txt_send = u"\U0001F4BE " + self.read_flang('["35"]') + " - GB\n" + self.read_flang('["32"]') + ": " + \
                       lista[
                           0] + "\n" + self.read_flang('["36"]') + ": " + lista[1] + " - " + lista[
                           3] + "\n" + self.read_flang(
                '["34"]') + ": " + lista[2]
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error read info hdd")

    # The status of all services is checked.
    def c_servicestatus(self, id_user):
        try:
            servcs = self.read_fconf('["services"]')
            nsrvcs = len(servcs)
            if nsrvcs >= 9:
                nsrvcs = 9
            txt_send = u"\u2699 " + self.read_flang('["40"]') + "\n"
            for i in range(nsrvcs):
                nb = servcs[i]["nb"]
                name = servcs[i]["name"]
                comando = "ps -ef | grep -v grep | grep " + name
                check = os.popen(comando).read()
                if check == "":
                    txt_send += " - " + nb + " -> STOP " + u"\u274C\n"
                else:
                    txt_send += " - " + nb + " -> OK " + u"\u2705\n"
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error checking Status Services")

    # Starts or stops the service.
    def c_service_onoff(self, id_user, msj):
        servcs = self.read_fconf('["services"]')
        nsrvcs = len(servcs)
        if nsrvcs >= 9:
            nsrvcs = 9
        if nsrvcs > 0:
            for i in range(nsrvcs):
                nb = servcs[i]["nb"]
                name = servcs[i]["name"]
                if msj == nb:
                    comando = "ps -ef | grep -v grep | grep " + name
                    check = os.popen(comando).read()
                    if check == "":
                        comando2 = "service " + name + " start"
                        os.system(comando2)
                        status = 1
                    else:
                        comando2 = "service " + name + " stop"
                        os.system(comando2)
                        status = 0
                    time.sleep(0.5)
                    check = os.popen(comando).read()
                    if check == "" and status == 1:
                        txt_send = self.read_flang('["41"]') + " " + nb + u" \u26A0"
                    elif check != "" and status == 0:
                        txt_send = self.read_flang('["42"]') + " " + nb + u" \u26A0"
                    else:
                        if check == "":
                            txt_send = nb + " -> STOP " + u"\u274C\n"
                        else:
                            txt_send = nb + " -> OK " + u"\u2705\n"

                    self.send_message(id_user, txt_send)
                    return 1
        return 0

    # Time reading on the device.
    def c_uptime(self, id_user):
        try:
            uptime = os.popen(
                "awk '{printf(\"%d days %02d:%02d:%02d\",($1/60/60/24),($1/60/60%24),($1/60%60),($1%60))}' /proc/uptime").read()
            txt_send = u"\U0001F51B  " + uptime
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error read uptime")

    # Reading of data received and transmitted by one network interface.
    def c_rxtx(self, intr):
        jsnrt = []
        try:
            comandorx = "cat /sys/class/net/" + intr + "/statistics/rx_bytes"
            comandotx = "cat /sys/class/net/" + intr + "/statistics/tx_bytes"
            rx = round(float(os.popen(comandorx).read()) / 1048576, 2)
            tx = round(float(os.popen(comandotx).read()) / 1048576, 2)
            total = rx + tx
            jsnrt.append(intr)
            jsnrt.append(rx)
            jsnrt.append(tx)
            jsnrt.append(total)
        except:
            logging.error("Error read Rx/Tx " + intr)
            jsnrt.append(intr)
            for i in range(3):
                jsnrt.append(0)
        return jsnrt

    # Reading of data received and transmitted by each network interface.
    def c_info_rxtx(self, id_user):
        txt_send = ""
        try:
            interfaces = self.read_fconf("['interfaces']")
            for interface in interfaces:
                info = self.c_rxtx(interface)
                if info[3] >= 1024:
                    txt_send += u"\U0001F53A " + str(info[0]) + " - GB\n"
                    txt_send += " - Rx: " + str(round(info[1] / 1024, 2)) + "\n"
                    txt_send += " - Tx: " + str(round(info[2] / 1024, 2)) + "\n"
                    txt_send += " - Total: " + str(round(info[3] / 1024, 2)) + "\n\n"
                else:
                    txt_send += u"\U0001F53A " + str(info[0]) + " - MB\n"
                    txt_send += " - Rx: " + str(info[1]) + "\n"
                    txt_send += " - Tx: " + str(info[2]) + "\n"
                    txt_send += " - Total: " + str(info[3]) + "\n\n"
            self.send_message(id_user, txt_send)
        except:
            logging.error("Error checking IPPrivate")

            # --------------------------------------------------------------

    # Notification to administrators of access not allowed.
    def warn(self, id_user, msj, name):
        txt_send = u"\u26A0 " + self.read_flang('["6"]') + u" \u26A0\nID: " + str(id_user) + "\n" + self.read_flang(
            '["5"]') + ": " + name + "\n" + self.read_flang('["7"]') + ": " + msj
        admins = self.read_fconf('["admin"]')
        for admin in admins:
            try:
                id_admin = admin["id_user"]
                self.send_message(id_admin, txt_send)
            except:
                logging.error("Error sending message to admin " + id_user)

    # Send message to all administrators.
    def notification_adm(self, msj):
        txt_send = msj
        admins = self.read_fconf('["admin"]')
        for admin in admins:
            try:
                id_admin = admin["id"]
                self.send_message(id_admin, txt_send)
            except:
                logging.error("Error sending message to admin " + id_admin)

    # Send sticker to all administrators.
    def notification_adm_sticker(self, msj):
        txt_send = msj
        admins = self.read_fconf('["admin"]')
        for admin in admins:
            try:
                id_admin = admin["id"]
                self.send_sticker(id_admin, txt_send)
            except:
                logging.error("Error sending sticker to admin " + id_admin)

    # Checking the status of a module.
    def mod_act(self, name_module):
        mods = self.read_fconf('["modules"]')
        actmod = 0
        try:
            for mod in mods:
                name_mod = mod["name"]
                if name_mod == name_module:
                    actmod = mod["activated"]
                    break
            return actmod
        except:
            logging.error("Error reading configuration file")
            sys.exit(1)

    # Reading device distribution.
    def distributor(self):
        try:
            check = os.popen("lsb_release -i|awk '{ print $3 }'|tr -d '\n'").read()
            return check
        except:
            return 0
