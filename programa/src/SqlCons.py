from flask import Flask


class Consultas():
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
        #url : localhost/portal_ventas/php/api.php?op=inicio
        #se envia por post cod_usuario,password
        #respuesta del servido será un diccionario
        # se llama el servivio para reconocer si el ususario es verididco
        #se espera que la respuesta sea en terminaos de binario 1 = si existe 0= NO existe 
        # La respuesta no flittra si eiste error de ususario o contraseña, solo estipula si se encuntra o no los datos ingrresaddos
        
        result=1
        if result==1:
            return True
        else:
            return False