#!/usr/bin/python
# -*- coding: utf-8 -*-

# Emojis python http://www.fileformat.info/info/emoji/list.htm

import os
import re
import sys
import json
import logging
import time
import ConfigParser
import random
import string
import telebot
import hashlib
import time
from telebot import types
from pymongo import MongoClient

reload(sys)
sys.setdefaultencoding('utf8')


# -----------------------------------------------------------------------------------------------------

# Funcion para leer archivos de lenguages
def read_language(idioma):
    logging.info("Leemos archivo de idioma " + idioma)
    ruta_idioma = PATH_LANGUAGE + "language_" + idioma + ".json"
    try:
        with open(ruta_idioma) as f:
            language = json.loads(f.read())
        return language
    except:
        print("Error al leer archivo de idioma " + ruta_idioma)
        logging.error("Error al leer archivo de idioma " + ruta_idioma)
        sys.exit(1)


# Funcion para imprimir por pantalla y guardar datos del usuario y mensaje que recibimos
def recibido(id, nombre, texto):
    print('[RECIBIDO] ID: ' + str(id) + ' - NOMBRE: ' + nombre + ' - MENSAJE: ' + texto)
    logging.info('[RECIBIDO]  ID: ' + str(id) + ' - NOMBRE: ' + nombre + ' - MENSAJE: ' + texto)


# Funcion para imprimir por pantalla y guardar datos del usuario y mensaje que enviamos
def enviado(id, nombre, textoEnvio):
    print('[ENVIAMOS] ID: ' + str(id) + ' - NOMBRE: ' + nombre + ' - MENSAJE: ' + textoEnvio)
    logging.info('[ENVIAMOS]  ID: ' + str(id) + ' - NOMBRE: ' + nombre + ' - MENSAJE: ' + textoEnvio)


# Funcion para crear el salt
def randomSalt(size=6, chars=string.ascii_uppercase + string.digits + string.letters):
    return ''.join(random.choice(chars) for _ in range(size))


# -----------------------------------------------------------------------------------------------------

# Inicializamos el parseo del config
CONFIG_FILENAME = "/usr/local/idbedya/idbedya.conf"
config = ConfigParser.ConfigParser()
config.read(CONFIG_FILENAME)

# Conexion con la bd
conexionConfig = config.get('CONEXION', 'bd')
conexion = MongoClient(conexionConfig)
db = conexion.bedya

# Leemos el directorio de log del config y creamos el log
LOG_FILENAME = config.get('DIRECTORY', 'log')
LOG_FORMAT = "%(asctime)s - %(levelname)s - %(message)s"
logging.basicConfig(filename=LOG_FILENAME, level=logging.DEBUG, format=LOG_FORMAT)
logging.getLogger("requests").setLevel(logging.WARNING)
logging.getLogger("urllib3").setLevel(logging.WARNING)

print("---------- Iniciamos IdBedya ----------")
logging.info("---------------------------------------")
logging.info("---------- Iniciamos IdBedya ----------")
logging.info("---------------------------------------")

# Leemos el token y el directorio de los textos del config
TOKEN = config.get('CONEXION', 'token')
PATH_LANGUAGE = config.get('DIRECTORY', 'language')

print("Token: " + TOKEN)
logging.info("Token: " + TOKEN)

# Leemos archivos de idiomas
textoES = read_language("ES")
textoEN = read_language("EN")

# Creamos Bot con el token
bot = telebot.TeleBot(TOKEN)


@bot.message_handler()
def main(message):
    # Almacenamos los datos referentes al usuario y al mensaje que recibimos
    idUsuario = message.chat.id
    nombreUsuario = message.chat.first_name
    apellidoUsuario = message.chat.last_name
    mensajeUsuario = message.text
    idmensaje = message.message_id

    # Buscamos el usuario en la bd
    usuarioJson = db.usuarios.find_one({"_id": str(idUsuario)})

    # Si no le encontramos en la bd le agregamos
    existe = 0
    textoID = textoES  # Por defecto idioma español

    # Si no existe el usuario en la bd le creamos y sino leemos su idioma
    if usuarioJson == None:
        print("Nuevo Usuario: " + str(idUsuario))
        logging.info("Nuevo Usuario: " + str(idUsuario))

        documentoUsuario = {
            '_id': str(idUsuario),
            'nombre': nombreUsuario,
            'apellido': apellidoUsuario,
            'idioma': 'ES',
            'password': None,
            'salt': None,
            'registrado': 0,
            'fecha_registro': None,
            'ultimo_inicio': None,
            'codigo': None,
            'tipo': 0
        }

        # Creamos el documento usuarios
        colUsuarios = db.usuarios
        colUsuarios.insert_one(documentoUsuario)

        print("Usuario añadido a la bd: " + str(idUsuario))
        logging.info("Usuario añadido a la bd: " + str(idUsuario))
        
    else:
        idioma = usuarioJson["idioma"]
        registrado = usuarioJson["registrado"]
        if idioma == "EN":
            textoID = textoEN

        existe = 1

    # Guardamos en log e imprimimos por pantalla los datos
    recibido(idUsuario, nombreUsuario, mensajeUsuario)

    # Si el mensaje es "start" damos la bienbenida
    if (mensajeUsuario == "/start"):
        sticker = "CAADBAADFwADn0npAAE0qQvbcYmFbQI"
        texto_envio = textoID["1"] + " " + nombreUsuario + "!!\n\n" + textoID["2"] + "\n\n- " + textoID["3"] + "\n\n- " + \
                     textoID["4"] + "\n\n- " + textoID["5"] + u" \U0001F447"
        bot.send_sticker(idUsuario, sticker)
        r1 = ["🇪🇸🇬🇧", "ID Telegram"]
        keyboard = [r1]
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        bot.send_message(idUsuario, texto_envio, None, None, json.dumps(news_keyboard))
        enviado(idUsuario, nombreUsuario, "Mensaje de bienvenida")

    # Si pulsa el boton de las banderas
    elif (mensajeUsuario == "🇪🇸🇬🇧"):
        texto_envio = "Please pick a language."
        r1 = [u"\U0001F310 Español", u"\U0001F310 English"]
        r2 = [u"\U0001F519"]
        keyboard = [r1, r2]
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        bot.send_message(idUsuario, texto_envio, None, None, json.dumps(news_keyboard))

    # Si seleciona el idioma español lo actualizamos en la bd
    elif (mensajeUsuario == u"\U0001F310 Español"):
        db.usuarios.update_one({"_id": str(idUsuario)}, {"$set": {"idioma": "ES"}})
        texto_envio = textoES["7"]
        r1 = ["🇪🇸🇬🇧", "ID Telegram"]
        keyboard = [r1]
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        bot.send_message(idUsuario, texto_envio, None, None, json.dumps(news_keyboard))

    # Si seleciona el idioma ingles lo actualizamos en la bd
    elif (mensajeUsuario == u"\U0001F310 English"):
        db.usuarios.update_one({"_id": str(idUsuario)}, {"$set": {"idioma": "EN"}})
        texto_envio = textoEN["7"]
        r1 = ["🇪🇸🇬🇧", "ID Telegram"]
        keyboard = [r1]
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        bot.send_message(idUsuario, texto_envio, None, None, json.dumps(news_keyboard))

    # Si pulsa el boton de atras
    elif (mensajeUsuario == u"\U0001F519"):
        texto_envio = textoID["1"] + " " + nombreUsuario + "!!\n\n" + textoID["2"] + "\n\n- " + textoID["3"] + "\n\n- " + \
                     textoID["4"] + "\n\n- " + textoID["5"] + u" \U0001F447"
        r1 = ["🇪🇸🇬🇧", "ID Telegram"]
        keyboard = [r1]
        news_keyboard = {'keyboard': keyboard, 'resize_keyboard': True}
        bot.send_message(idUsuario, texto_envio, None, None, json.dumps(news_keyboard))
        enviado(idUsuario, nombreUsuario, "Mensaje de bienvenida")

    # Si pulsa el boton de registrarse
    elif (mensajeUsuario == "/register"):
        # Si todavia no se ha registrado actualizamos setting registrado = 1 y si ya esta registrado le informamos de ello
        if (registrado == 0 or registrado == 1):
            texto_envio = textoID["10"]
            db.usuarios.update_one({"_id": str(idUsuario)},
                                   {"$set": {"registrado": 1}})  # Registrado = 1 Pendiente de meter primera password
            enviado(idUsuario, nombreUsuario, "Inicio de registro")
        else:
            texto_envio = textoID["8"] + "\n" + textoID["9"]
            enviado(idUsuario, nombreUsuario, "Ya esta registrado")
        bot.send_message(idUsuario, texto_envio)


    # Si escribe /pass + la contraseña ya ha pulsado el boton de registrar
    elif (re.match('^(/pass\s).+$', mensajeUsuario) and registrado != 0):
        password = mensajeUsuario.split(' ')  # Separamos el comando /pass de la contraseña
        passok = password[1]  # Nos quedamos con la contraseña

        # Si es la primera vez que mete la contraseña
        if (registrado == 1):
            if (re.match('(?=.*?\d)(?=.*?[a-z])(?=.*?[A-Z]).{6,15}$', passok)):

                documentoPropiedades = {
                    "_id": str(idUsuario),
                    "bots": [],
                    "admin": [
                        {"name": nombreUsuario, "id": str(idUsuario)}
                    ],
                    "services": [
                        {
                            "nb": "Apache",
                            "name": "apache2"
                        },
                        {
                            "nb": "Maria DB",
                            "name": "mariadb"
                        },
                        {
                            "nb": "FTP",
                            "name": "vsftpd"
                        },
                        {
                            "nb": "MongoDB",
                            "name": "mongodb"
                        },
                        {
                            "nb": "Nagios",
                            "name": "nagios"
                        }
                    ],
                    "interfaces": ["lo", "eth0", "eth1", "wlan0", "wlan1"],
                    "modules": [],
                    "servicios": [],
                    "sensores": []
                }

                # Creamos el documento de propiedades
                colPropiedades = db.propiedades
                colPropiedades.insert_one(documentoPropiedades)

                # Generamos el salt
                salt = randomSalt()
                db.usuarios.update_one({"_id": str(idUsuario)}, {"$set": {"salt": salt}})
                passhas = passok + salt
                passfin = hashlib.sha1(passhas).hexdigest()

                # Actualizamos en la bd salt, password y registrado = 2
                db.usuarios.update_one({"_id": str(idUsuario)}, {"$set": {"password": passfin}})
                db.usuarios.update_one({"_id": str(idUsuario)},
                                       {"$set": {"registrado": 2}})  # Registrado = 2 Registrado y password OK
                fecha = time.strftime("%A %d %b %Y %H:%M:%S")
                db.usuarios.update_one({"_id": str(idUsuario)}, {"$set": {"fecha_registro": fecha}})
                texto_envio = u"\u2705 " + textoID["15"] + "\n" + textoID["9"]
                bot.send_message(idUsuario, texto_envio)
                enviado(idUsuario, nombreUsuario, "Contraseña cambiada correctamente")

            else:
                texto_envio = u"\u26A0 " + textoID["11"] + "\n\n" + textoID["12"] + "\n- " + textoID["13"] + "\n- " + \
                             textoID["14"]
                bot.send_message(idUsuario, texto_envio)
                enviado(idUsuario, nombreUsuario, "Contraseña no cumple los requisitos")

        # Si es para cambiarla
        elif (registrado == 3):
            if (re.match('(?=.*?\d)(?=.*?[a-z])(?=.*?[A-Z]).{6,15}$', passok)):
                salt = randomSalt()
                db.usuarios.update_one({"_id": str(idUsuario)}, {"$set": {"salt": salt}})
                passhas = passok + salt
                passfin = hashlib.sha1(passhas).hexdigest()
                db.usuarios.update_one({"_id": str(idUsuario)}, {"$set": {"password": passfin}})
                db.usuarios.update_one({"_id": str(idUsuario)},
                                       {"$set": {"registrado": 2}})  # Registrado = 3 Solicitado cambio de password
                texto_envio = u"\u2705 " + textoID["15"] + "\n" + textoID["9"]
                bot.send_message(idUsuario, texto_envio)
                enviado(idUsuario, nombreUsuario, "Contraseña cambiada correctamente")

            else:
                texto_envio = u"\u26A0 " + textoID["11"] + "\n\n" + textoID["12"] + "\n- " + textoID["13"] + "\n- " + \
                             textoID["14"]
                bot.send_message(idUsuario, texto_envio)
                enviado(idUsuario, nombreUsuario, "Contraseña no cumple los requisitos")

    # Si pulsa el boton ID Telegram le devolvemos su ID
    elif (mensajeUsuario == "ID Telegram"):
        texto_envio = "Your ID:  " + str(idUsuario)
        bot.send_message(idUsuario, texto_envio)
        enviado(idUsuario, nombreUsuario, texto_envio)

    # Si no coincide con nada de lo anterior le decimos que no sabes lo que quiere
    else:
        sticker = "CAADBAADFwADn0npAAE0qQvbcYmFbQI"
        texto_envio = textoID["6"]
        bot.send_sticker(idUsuario, sticker)
        bot.reply_to(message, texto_envio)
        enviado(idUsuario, nombreUsuario, "Ni idea de lo que quiere decir")


bot.polling(none_stop=True)
