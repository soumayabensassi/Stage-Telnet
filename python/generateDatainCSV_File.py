from http import client
from select import select
from turtle import delay
import mysql.connector
from random import randint
import random
import time
import datetime
from mysql.connector import Error
import csv
import pandas as pd
import numpy as np
try:
    connection = mysql.connector.connect(host='localhost',
                                         database='telnet',
                                         user='root',
                                         password='')
    if connection.is_connected():
        db_Info = connection.get_server_info()
        print("Connected to MySQL Server version ", db_Info)
        cursor = connection.cursor()
        cursor.execute("select database();")
        record = cursor.fetchone()
        print("You're connected to database: ", record)
        cur = connection.cursor()
        cursor= connection.cursor()
        #exécuter le curseur avec la méthode execute() et transmis la requête SQL
        ts = time.time()
        timestamp = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S.%M')
        T=[]
        Dataaa=[]
        id=[]
        
        cur.execute("SELECT s.id from device s JOIN domaine_application d WHERE (abonnements_id is not NULL and d.nom='agricole' AND s.domaine_application_id=d.id)")
        values = cur.fetchall()
        cur.execute("SELECT s.id from device s JOIN domaine_application d WHERE (abonnements_id is not NULL and d.nom='santé' AND s.domaine_application_id=d.id)")
        valuesSante = cur.fetchall()
        for i in range(200):
            #sql = "INSERT INTO donnee (timestamp,data,device_id) VALUES (%s,%s,%s)"
            ts = time.time()
            timestamp = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S.%f')
            #generate rondom number from list valyes (id device)
            rand_idx = random.randrange(len(values))
            dec = values[rand_idx]
            #generate rondom number in 0-->50(temperature)
            dec1=randint(0, 50)
            #generate rondom number in 0-->5000(bilan hydrique)
            P=randint(0, 10000)
            E=randint(0, 5000)
            D=randint(0, 5000)
            R=randint(0, 5000)
            #convert dec to hex
            hex_01 =hex(dec[0])
            hex_01 = hex_01[2 : : ]
            
            while(len(hex_01)!=4):
                hex_01='0'+hex_01
            hex_02=hex(dec1)
    
            hex_02 = hex_02[2 : : ]
            
            while(len(hex_02)!=4):
                hex_02='0'+hex_02

            hex_P=hex(P)
            hex_P = hex_P[2 : : ]
            while(len(hex_P)!=4):
                hex_P='0'+hex_P
            hex_E=hex(E)
            hex_E = hex_E[2 : : ]
            while(len(hex_E)!=4):
                hex_E='0'+hex_E
            hex_D=hex(D)
            hex_D = hex_D[2 : : ]
            while(len(hex_D)!=4):
                hex_D='0'+hex_D
            hex_R=hex(R)
            hex_R = hex_R[2 : : ]
            while(len(hex_R)!=4):
                hex_R='0'+hex_R  
            T.append(timestamp)
            Dataaa.append(hex_01+hex_02+hex_P+hex_E+hex_D+hex_R)
            id.append(dec[0])
            #rows.append([timestamp,hex_01+hex_02+hex_P+hex_E+hex_D+hex_R,dec[0]])
            
            #generate rondom number from list valyes (id device)
            rand_idxSante = random.randrange(len(valuesSante))
            numDev = valuesSante[rand_idxSante]
            #generate rondom number in 0-->50(temperature)
            temperatueSante=randint(0, 45)
            #generate rondom number in 0-->5000(bilan hydrique)
            Blood=randint(0, 10000)
            HeartBeat=randint(60, 180)
            #convert dec to hex
            hex_numDev =hex(numDev[0])
            hex_numDev = hex_numDev[2 : : ]
            
            while(len(hex_numDev)!=4):
                hex_numDev='0'+hex_numDev

            hex_temperatueSante=hex(temperatueSante)
    
            hex_temperatueSante = hex_temperatueSante[2 : : ]
            
            while(len(hex_temperatueSante)!=4):
                hex_temperatueSante='0'+hex_temperatueSante

            hex_Blood=hex(Blood)
            hex_Blood = hex_Blood[2 : : ]
            while(len(hex_Blood)!=4):
    
                hex_Blood='0'+hex_Blood
            hex_HeartBeat=hex(HeartBeat)
            hex_HeartBeat = hex_HeartBeat[2 : : ]
            while(len(hex_HeartBeat)!=4):
                hex_HeartBeat='0'+hex_HeartBeat
            T.append(timestamp)
            Dataaa.append(hex_numDev+hex_temperatueSante+hex_Blood+hex_HeartBeat)
            id.append(numDev[0])
            #rows1.append([timestamp,hex_numDev+hex_temperatueSante+hex_Blood+hex_HeartBeat,numDev[0]])   
        
        technologies = {
            'timestamp':T,
            'data' :Dataaa,
            'device_id':id
          }
        df = pd.DataFrame(technologies)
        df['timestamp'] = pd.to_datetime(df['timestamp'], format='%Y-%m-%d %H:%M:%S.%f')
        df.to_csv("data1.csv", sep='/', index=False,mode = 'a',header=None)





except Error as e:
    print("Error while connecting to MySQL", e)
finally:
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("MySQL connection is closed")