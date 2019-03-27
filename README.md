![enter image description here](http://bedya.es/img/mini-bedya.png)

**Bedya** is the first application for managing IT devices for Linux distribution (Servers, Raspberry-Pi ...) via Telegram.
This way you can know the status of your devices from anywhere with Internet connection, be it a mobile or a PC.

Web: [https://www.bedya.es/](https://www.bedya.es/)

----------

Features:
--------

With Bedya you will be able to control and monitor different functionalities of your devices.

You will be able to create your configurations in a very easy way from our [web](https://www.bedya.es/), in this way the management and the control will adapt of the best possible form to your needs.

 >- Basic information (IP Privates, IP Public, Host Name, Uptime, Date)
 >- View the Status and Start/Stop the services of your choice.
 >- Consult the performance of your RAM, Hard Disk and the Network Interfaces that you want.
 >- Restart and Shutdown your devices
 >- Integration with [Latch](https://www.elevenpaths.com/es/tecnologia/latch/latch-para-usuarios/index.html) to give greater security to the control of your devices.
 >- You can give management access to the administrators of your choice.


----------

Getting Started:
-----------

### Register:

 1. You will need to create an account on [Telegram](https://web.telegram.org/#/login) if you do not have it.
 2. Find our bot [@Bedya_bot](https://telegram.me/bedya_bot) in Telegram and write /register, this will start the process to create your Bedya account.
> **Note:** This ID is very important because if you later enter a wrong id on our platform, you will not be able to control your devices.
 3. Our bot will ask you to enter a password with the following format **/pass + password**. For example "/pass Ra1234".
 4. Now you can go to [Bedya](https://www.bedya.es/login) and with your Telegram ID and the password you have previously entered you can access the Control Panel.

### Create settings:

It's time to create the configurations and bots that will help us control our IT devices.

 1. Find and talk to [BotFather](https://telegram.me/botfather) and follow a few simple steps.
> If you need help to create a bot [*click here*](https://core.telegram.org/bots#6-botfather).

 2. Once you have created your bot and have the authorization token, go to the [Bots](https://www.bedya.es/control/bots) section in our Control Panel and register a new one with the Token you obtained earlier.
 > It will be necessary to register as many bots as IT devices we will manage.

 3. Enter the [Administrators](https://www.bedya.es/control/administradores) section and register the users that can later manage your computers. For this you will need your Telegram ID.
 > To know the id of each administrator will need to go to [@Bedya_bot](https://telegram.me/bedya_bot) and press the **ID Telegram** button.

 4. In the [Services](https://www.bedya.es/control/servicios) window enter all the services that you want to manage (Apache, MySQL, FTP ...).

 5. The next thing is to complete the [Modules](http://www.bedya.es/control/modulos) that go from a bestowal to the bots of different functionalities.
 > If you are not interested in any module you do not need to fill in this section, you will always be able to activate them later.

 6. In the [Others](https://www.bedya.es/control/otros) section you can register the latest features for your devices. For example, the network interfaces (eth0, eth1, wlan0 ...)

 7. Finally in [Configuration](https://www.bedya.es/control/configuraciones) create a customized configuration for the bot that you have registered and associate the elements that best fit the administration that you are going to make.

### Installation

* Installation using GitHub:
```
$ git clone https://github.com/RafaMunoz/Bedya.git
$ cd Bedya
$ sudo python setup.py install
```

* Installation using wget:
```
$ wget https://github.com/RafaMunoz/Bedya/archive/master.zip
$ unzip master.zip
$ cd Bedya-master
$ sudo python setup.py install
```

-----------------


#### Modify default settings

The first configuration is done manually, then all changes you make from the web will be applied automatically in the bots.

    $ sudo nano /etc/bedya/infobot.json


You must complete the fields "token", "id" and "name" and then save the file using <kbd>Ctrl+O</kbd> and <kbd>Ctrl+X</kbd>.


    "token": "INTRODUCE AQUI TU TOKEN",
    "admin": [
        {
            "id": "INTRODUCE AQUI TU ID",
            "name": "INTRODUCE AQUI TU NOMBRE"
        }
    ],


If you have installed a Bedya Server you will need to modify the configuration file and change the IP addresses for your server.

    $ sudo nano /etc/bedya/bedya.conf
    
    [URL]
    updates = IP-SERVER/api/infobot/
    downloads = IP-SERVER/downloads

Once the configuration file has been edited you can start the service using either of the following two commands.

    $ sudo service bedya start

or

    $ sudo systemctl start bedya


Automatically after a few minutes the configuration you have created from the web will be downloaded.

>**Tip:** If you need to force the download you can send the *"/update"* command to your Bot from your cell phone.


----------


