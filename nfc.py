#! /usr/bin/env python
# -*- coding: utf-8 -*-
"""
Referências

pyscard
(Sending APDUs to a Smart Card Obtained from Card Monitoring)
http://pyscard.sourceforge.net/user-guide.html#the-reader-centric-approach
Run pip install -r requirements.txt

socketserver
https://docs.python.org/2/library/socketserver.html

driver do leitor
http://www.acs.com.hk/en/driver/3/acr122u-usb-nfc-reader/

pdf com os comandos
http://downloads.acs.com.hk/drivers/en/API-ACR122U-2.02.pdf

"""

from __future__ import print_function
from time import sleep

from smartcard.CardConnectionObserver import ConsoleCardConnectionObserver
from smartcard.CardConnectionObserver import CardConnectionObserver
from smartcard.CardMonitoring import CardMonitor, CardObserver
from smartcard.util import toHexString

import socket
import urllib.request

from pathlib import Path

# Define the apdus used in this script
SELECT          = [0xFF, 0xCA, 0x00, 0x00, 0x00]
BUZZER_ON       = [0xFF, 0x00, 0x52, 0xFF, 0x00]
BUZZER_OFF      = [0xFF, 0x00, 0x52, 0x00, 0x00]
GET_RESPONSE    = [0XA0, 0XC0, 00, 00]
DF_TELECOM      = [0x7F, 0x10]

# Define IP and Port for UDP connection 
UDP_IP      = socket.gethostname() 
UDP_PORT    = 2331
UDP_MESSAGE = ''
UDP_RAW_MSG = ''

# API socket channel
API_URL     = ''
CHANNEL_KEY = ''

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)




class TracerAndSELECTInterpreter(CardConnectionObserver):
    """This observer will interprer SELECT and GET RESPONSE bytes
    and replace them with a human readable string."""

    def update(self, cardconnection, ccevent):
        global UDP_MESSAGE, UDP_RAW_MSG, sock

        if 'response' == ccevent.type:
            UDP_RAW_MSG = toHexString(ccevent.args[0])
            UDP_MESSAGE = UDP_RAW_MSG
            UDP_MESSAGE = UDP_MESSAGE.replace(" ","")

            if UDP_RAW_MSG != "":
                try:
                    log_file = open("log.txt","a")
                    full_url = API_URL+UDP_MESSAGE+'/'+CHANNEL_KEY
                    full_url = full_url.replace("\n", "")
                    full_url = full_url.replace("\r", "")
                    log_file.write("Received code: "+UDP_MESSAGE+" | Sending API request to: "+full_url+"\n")
                    req = urllib.request.Request(full_url) 
                    r = urllib.request.urlopen(req)
                    log_file.write("API call sent. \n")
                except Exception as e:
                    log_file.write("Exception occured: "+e+"\n")

# a simple card observer that tries to select DF_TELECOM on an inserted card
class selectDFTELECOMObserver(CardObserver):
    """A simple card observer that is notified
    when cards are inserted/removed from the system and
    prints the list of cards
    """
    def __init__(self):
        global CHANNEL_KEY, API_URL
        self.observer = ConsoleCardConnectionObserver()
        config_file = Path("nfc.config")
        log_file = Path("log.txt")
        if not log_file.is_file():
            f = open("log.txt","w+")
            f.close()

        if config_file.is_file():
            f = open("nfc.config","r")
            urlLine = f.readline()
            split = urlLine.split('=')
            API_URL = split[1]
            keyLine = f.readline()
            split = keyLine.split('=')
            CHANNEL_KEY = split[1]
            f.close()
        else:
            f = open("nfc.config","w+")
            f.write("URL=\nKEY=\n")

    def update(self, observable, actions):
        (addedcards, removedcards) = actions

        global UDP_MESSAGE, UDP_RAW_MSG, sock

        for card in addedcards:
            #print("+Inserted: ", toHexString(card.atr))
            card.connection = card.createConnection()
            card.connection.connect()
            observer2 = TracerAndSELECTInterpreter()
            card.connection.addObserver(observer2)
            apdu = BUZZER_OFF
            response, sw1, sw2 = card.connection.transmit(apdu)
            apdu = SELECT
            response, sw1, sw2 = card.connection.transmit(apdu)


        #for card in removedcards:
         #   if UDP_RAW_MSG != '':
         #       sock.sendto(bytes(UDP_MESSAGE + '_OFF', 'utf-8'), (UDP_IP, UDP_PORT))

if __name__ == '__main__':
    #print("Insert or remove a SIM card in the system.")

    cardmonitor = CardMonitor()
    selectobserver = selectDFTELECOMObserver()
    cardmonitor.addObserver(selectobserver)

    #sleep(60)

    # don't forget to remove observer, or the
    # monitor will poll forever...
    #cardmonitor.deleteObserver(selectobserver)

    #import sys
    #if 'win32' == sys.platform:
    #    #print('press Enter to continue')
    #    sys.stdin.read(1)



if __name__ == "__main__":
    sock.bind((socket.gethostname(), UDP_PORT))



while True:
    data, addr = sock.recvfrom(1024)
    print(data.strip(), addr)
