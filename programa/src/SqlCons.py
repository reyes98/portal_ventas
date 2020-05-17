#from flask import Flask
#consejo esta url ponla como constante
#url local: localhost/portal_ventas/php/api.php 

#para inicio de sesión 
#url: http://localhost/portal_ventas/php/api.php?op=inicio
#POST:
# cod_usuario: JSREYES
# password: 27069811
# retorno: {"server_response":[{"cod_usuario":"jsreyes","estado":"1","msj":"inicio de sesion correcto"}]}
#------------------------------------------

#para crear usuario
#url: http://localhost/portal_ventas/php/api.php?fun=crear_usuario&op=insert
#POST:
# cod_usuario: DALEDRO
# password: 12345678
# tipo_id: 1
# identificacion: la cédula de anduqui
# nombre: Anduquia el somalí
# dirección: calle 3 #56-12
# tel_fijo: 3423052
# tel_movil: 36255434
# email: daledro@hotmail.com
# eps: coomeva
# foto: DALEDRO.jpg -> aunque de momento no
#retorno: {"server_response":[{"estado":"1","cod_usuario":"DALEDRO","msj":"usuario creado"}]}
#------------------------------------------

#para un nuevo producto
#url:http://localhost/portal_ventas/php/api.php?op=insert&fun=crear_producto
# POST:
# cod_producto=PR-4 
# cod_usuario=jsreyes
# descripcion=De Todito
# cod_barras= de momento no
# categoria=1
# precio=2000 
# peso=0.058 ->esto es en kilogramos
# marca=fritolay 
# info_tecnica=Pasabocas de maíz
# unidad=1
# modelo=2020
#retorno: {"server_response":[{"estado":"1","cod_producto":"PR-4","msj":"el producto 'PR-4' ha sido agregado"}]}
#------------------------------------------

#para listar los productos de un vendedor
#url: http://localhost/portal_ventas/php/api.php?op=select&fun=lista_productos
# POST: 
# cod_usuario=jsreyes 
# los campos de abajo se envían si se actvan los filtros
# cod_producto=PR-4
# descripcion=De Todito
# cod_barras= de momento no
# categoria=1
# marca=fritolay 
#retorna: {"server_response":[[{"grabo":"jsreyes","nombre":"Sebasti\u00e1n
# Reyes","cod_producto":"PR-1","descripcion":"Doritos","precio":"2000","peso":"0.058","marca":"fritolay"}],{"1":{"grabo":"jsreyes","nombre":"Sebasti\u00e1n
# Reyes","cod_producto":"PR-2","descripcion":"Paquet\u00f3n
# doritos","precio":"8000","peso":"0.326","marca":"fritolay"}},{"2":{"grabo":"jsreyes","nombre":"Sebasti\u00e1n
# Reyes","cod_producto":"PR-3","descripcion":"choclitos de
# limon","precio":"2000","peso":"0.058","marca":"fritolay"}},{"3":{"grabo":"jsreyes","nombre":"Sebasti\u00e1n
# Reyes","cod_producto":"PR-4","descripcion":"De Todito","precio":"2000","peso":"0.058","marca":"fritolay"}}]}
#------------------------------------------


#para editar la informaciopn de un producto del vendedor
#url: http://localhost/portal_ventas/php/api.php?op=update&fun=actualizar_producto
# POST: 
#-campos obligatorios para editar la información de un producto:
# cod_usuario=jsreyes 
# cod_producto=PR-4
#-campos que se cambiaran el valor (claramente pueden si no se selecciona editar cierto campo, no se envía por POST):
# descripcion=DeTododito criollo
# cod_barras= de momento no
# categoria=1
# precio=2500 
# peso=0.058 ->esto es en kilogramos
# marca=fritolay 
# info_tecnica=Pasabocas de maíz
# unidad=1
# modelo=2020
#retorna: {"server_response":[{"estado":"1","cod_producto":"PR-4","msj":"el producto 'PR-4' ha sido actualizado"}]}
# 
#------------------------------------------

#para eliminar un producto del vendedor
#url: http://localhost/portal_ventas/php/api.php?op=delete&fun=eliminar_producto
# POST: 
#-campos obligatorios para editar la información de un producto:
# cod_usuario=jsreyes 
# cod_producto=PR-4
#retorna: {"server_response":[{"estado":"1","cod_producto":"PR-4","msj":"el producto 'PR-4' ha sido eliminado"}]}
# 
#------------------------------------------
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
    

#prueba = Consultas()

#print(prueba.login('jsreyes','27069811'))