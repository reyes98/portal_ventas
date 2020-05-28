import urllib3
import json
##conexión con el URL


class Consultas():
    def __init__(self):
        self.htp= urllib3.PoolManager()
        self.url='http://localhost/portal_ventas/php/api.php'

    def detallePro(self,nombre):
        ##Sacar todos los detalles POR NOMBRE   ordenas los values() en detalles
        detalles=list()
        return detalles


    def obtenerP(self):
        # En esta metodo se llama el servicio que saca los productos
        # se debe de hacer la separacion de la cebcera de la tabla valores claves o keys de diccionario
        # y se debe ordenar en una lista de listas los valores obtenidos
        #cabecera =['key1','key2','key3','key4']
        # columnas=[ ['id','prodcuto1',valor1,'detalles','etc'],['id','prodcuto2',valor,'detalles','etc']    ]
        pass
    def login(self,user,passw):
        resp = self.htp.request(
        'POST',
        self.url+'?op=inicio',
        fields={'cod_usuario': user,
            'password': passw
            })
        datos= resp.data.decode('UTF-8')
        obj = json.loads(datos)['server_response']
        # obj[0] en posicion 0 se refiere al primer registro que tiene el json 
        if obj[0]['estado']=='1':
            return True
        else:
            return False

    def consultarProductos(self,diccionario): 
        resp = self.htp.request(
        'POST',
        self.url+'?op=select&fun=lista_productos',
        fields=diccionario)
        
        datos= resp.data.decode('UTF-8')
        obj = json.loads(datos)['server_response']
        return obj

    # SENDING DATA POST URLLIB3
    def registrarUsuario(self,cod_usuario,nombre,cedula,rol,correo,password1,movil,direccion,eps,tipo_id):
        resp = self.htp.request(
        'POST',
        self.url+'?fun=crear_usuario&op=insert',
        fields={ 'cod_usuario':cod_usuario,
        'nombre': nombre,
        'identificacion': cedula,
        'correo': correo,
        'password': password1,
        'tel_movil':  movil,
        'direccion': direccion,
        'eps': eps,
        'tipo_id': tipo_id,
        'rol':rol
        })
        datos= resp.data.decode('UTF-8')
        obj = json.loads(datos)['server_response']
        return obj[0]["msj"]
    
    def agregarAlCarrito(self,user,vendedor,producto,cantidad):
        resp = self.htp.request(
        'POST',
        self.url+'?op=insert&fun=agregar_al_carrito',
        fields={
            'cod_usuario':user,
            'vendedor': vendedor,
            'producto': producto,
            'cantidad':cantidad
            })
        datos= resp.data.decode('UTF-8')
        obj = json.loads(datos)['server_response']   
        
        return obj[0]['msj']

    def verCarrito(self,user):
        resp = self.htp.request(
        'POST',
        self.url+'?op=select&fun=select_carrito',
        fields={
            'cod_usuario':user,
            })
        datos= resp.data.decode('UTF-8')
        obj = json.loads(datos)['server_response']
        
        return obj


    def convertirStrToList(self,lista):
        listicaFinal=list()
        for i in lista:
            listTemp=list()
            numero = int(len(i))-1
            resultado=""+i[1:numero]
            mapeado = resultado.split(",")
            for j in mapeado:
                listTemp.append(j.replace("'",""))
            listicaFinal.append(listTemp)
        print(listicaFinal)
        return listicaFinal

  
                 
                 
    def convertirLidiTOLista(self,lista):
        temp_list=list()
        if len(lista)>0:
            for i in lista:
                temp_aux=self.valoresdicionario(i)
                temp_list.append(temp_aux)
        return temp_list
            
                 
             
             
           
    def convertirListaToDict(self,lista):
        diccionario={lista[1]:lista[0]}
        return diccionario


    def convertirListaNormal(self,lista):
        lista_de_listas = list()
        
        for i in range(len(lista)):
           
            for j in lista[i]:
                resultado=list()
                if i==0:
                    resultado=self.valoresdicionario(lista[i][0])
                else:
                    resultado=self.valoresdicionario(lista[i][j])
                lista_de_listas.append(resultado)
        #print(lista_de_listas)
        return lista_de_listas
 
       
    def valoresdicionario(self,dicc):
        regist_temp=list()
        for registro in dicc.values():
            regist_temp.append(registro)
        return regist_temp
    

    def rol(self,user,passw):
        resp = self.htp.request(
        'POST',
        self.url+'?op=inicio',
        fields={'cod_usuario': user,
            'password': passw
            })
        datos= resp.data.decode('UTF-8')
        obj = json.loads(datos)['server_response']   
        
        return obj[0]['rol']

prueba = Consultas()
#print(prueba.verCarrito('usuarioprueba1'))
#prueba.prueba([['[jsreyes', ' Sebastián Reyes', ' PR-1', ' Doritos', ' 2000', ' 0.058', ' fritolay]', ' [jsreyes', ' Sebastián Reyes', ' PR-2', ' Paquetón doritos', ' 8000', ' 0.326', ' fritolay]']])

#print(prueba.valoresdicionario({"vendedor":"Sebasti\u00e1n  Reyes","descripcion":"Doritos","precio":"2000","cantidad":"8","precio_parcial":"16000"}))

#datos=[[{'grabo': 'jsreyes', 'nombre': 'Sebastián Reyes', 'cod_producto': 'PR-1', 'descripcion': 'Doritos', 'precio': '2000', 'peso': '0.058', 'marca': 'fritolay'}], {'1': {'grabo': 'jsreyes', 'nombre': 'Sebastián Reyes', 'cod_producto': 'PR-2', 'descripcion': 'Paquetón doritos', 'precio': '8000', 'peso': '0.326', 'marca': 'fritolay'}}, {'2': {'grabo': 'jsreyes', 'nombre': 'Sebastián Reyes', 'cod_producto': 'PR-3', 'descripcion': 'gaseosa', 'precio': '3', 'peso': '150', 'marca': 'Cocacola'}}]
#print(prueba.convertirListaNormal(datos))

#prueba.consultarProducto(2)
