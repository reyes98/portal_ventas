##descargar archivo y convertirlo en diferentes formatos
import urllib3
import json
##conexión con el URL
url= "http://winterolympicsmedals.com/medals.csv" 
http= urllib3.PoolManager()
resp = http.request(
     'POST',
     'http://localhost/portal_ventas/php/api.php?op=inicio',
    fields={'cod_usuario': 'jsreyes',
            'password':'27069811'
            })

#resp= http.request('GET', url)

print("conexión exitosa!")
## codificación a UTF-8
print('->',resp)
#str_response = resp.readall().decode('utf-8')
datos= resp.data.decode('UTF-8')
obj = json.loads(datos)
print(obj)
print(type(obj['server_response']))
lista=obj['server_response']
print('---')
dicc=dict()
for k in lista:
   print(k)
   dicc=k   
   print(type(k))
print('---')

print('usuario es:',dicc['cod_usuario'])
print('contrasenna es:',dicc['password'])

#print(datos)
#print(json.loads(resp.read.decode('utf-8'))['cod_usuario'])

##separar los datos en líneas
#print('llega',datos)

"""conf=int(datos)
print(conf)
if conf==0:

   print('usuario no valido, come monda')

else:
   print('si se confirma usuario')"""
   



