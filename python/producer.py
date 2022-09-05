import websockets
import threading
import asyncio
import csv
import mysql.connector
import datetime
async def hello(websocket, path):
    file = open('/Users/souma/python/data1.csv','r')
    i=0
    #rownumbers_to_remove= [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
    rownumbers_to_remove=[]
    type(file)
    lines = []
    csvreader = csv.reader(file)

    
    for row_number,row in enumerate(csvreader, start=1):
        rownumbers_to_remove.append(row_number)        
        if(i<1000):
            await websocket.send(row) 
        else:
            #if(row_number not in rownumbers_to_remove):
            lines.append(row)           
        i=i+1      
    with open('/Users/souma/python/data1.csv', 'w', newline='') as write_file:
                writer = csv.writer(write_file)
                writer.writerows(lines)
                write_file.close    
        
    file.close
    

def between_callback():
    loop = asyncio.new_event_loop()
    asyncio.set_event_loop(loop)
    ws_server = websockets.serve(hello, 'localhost', 8899)

    loop.run_until_complete(ws_server)
    loop.run_forever() # this is missing
    loop.close()

async def send_receive_message(uri):
    async with websockets.connect(uri) as websocket:
        await websocket.send('This is some text.')
        reply = await websocket.recv()
        print(f"The reply is: '{reply}'")
async def consumer_handler(websocket)-> None:
        async for message in websocket:
            my_list = message.split('/')
            try:
                connection = mysql.connector.connect(host='localhost',
                                                    database='telnet',
                                                    user='root',
                                                    password='')
                if connection.is_connected():
                    cursor = connection.cursor()
                    date_time_obj = datetime.datetime.strptime(my_list[0], '%Y-%m-%d %H:%M:%S.%f')
                    sql = "INSERT INTO donnee (timestamp,data,device_id) VALUES (%s,%s,%s)"
                    val1 = (date_time_obj,my_list[1],my_list[2])
                    cursor.execute(sql, val1)
                    connection.commit()
            finally:
                if connection.is_connected():
                    cursor.close()
                    connection.close()
                    
                    print("MySQL connection is closed")
async def consume(uri)-> None:
        async with websockets.connect(uri) as websocket:
            await consumer_handler(websocket)
def client():
    loop = asyncio.new_event_loop()
    asyncio.set_event_loop(loop)
    loop.run_until_complete(consume('ws://localhost:8899'))
    loop.close()

if __name__ == "__main__":
    # daemon server thread:
    server = threading.Thread(target=between_callback, daemon=True)
    server.start()
    client = threading.Thread(target=client)
    client.start()
    client.join()