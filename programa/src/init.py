from flask import Flask,session, g, render_template,redirect, url_for, request,session ,escape, redirect,jsonify,flash

import SqlCons
import os

temporal=list()

##
app= Flask(__name__)
app.secret_key = os.urandom(24)

@app.route('/',methods=['GET', 'POST'])
def home():
    ## SI ACCIONAS EL BOTON DE LOGIN
    if request.values.get('btn-submit') == 'Login':        
        if request.method == 'GET':
            if session ==None: 
                session.pop('user', None)
            else:
                return render_template("login.html")
        if request.method =="POST":
            consulticas = SqlCons.Consultas()

            if consulticas.login(request.form['login'],request.form['password']) == True:
                RunSession(request.form['login'],request.form['password'])
                return redirect(url_for('consultarProductos'))
            else:
                print('nonas')
                return render_template("login.html")
        
    ## SI ACCIONAS EL BOTON REGISTRAR
    elif request.values.get('btn-submit') == 'Registrar':
        return redirect(url_for('registrarUsuario'))
    return render_template("login.html")

@app.route('/registrarUsuario',methods=['GET','POST'])
def registrarUsuario():
    if request.method == 'POST':
       cod_usuario=request.form['cod_usuario']
       nombre=request.form['nombre']
       cedula=request.form['cedula']
       correo=request.form['correo']
       password1=request.form['pass1']
       movil=request.form['movil']
       direccion=request.form['direccion']
       eps=request.form['eps']
       rol=request.form['selectTipo']
       tipo_id=1
       consulticas=SqlCons.Consultas()
       resultado=consulticas.registrarUsuario(cod_usuario,nombre,cedula,rol,correo,password1,movil,direccion,eps,tipo_id)
       

    return render_template("registro.html")

@app.route('/consultProductos/',methods=['GET','POST'])
def consultarProductos():
     
    resultado=list()
    if request.values.get('btn-submit')== 'buscar':
        temporal.clear()
        if request.form['buscador']:
            temporal.append(request.form['buscador'])
        if request.form['selectSearch']:
            temporal.append(request.form['selectSearch'])
        if request.form['selectCategory']:
            temporal.append(request.form['selectCategory'])

        
    
        if len(temporal)>0:
            consulticas = SqlCons.Consultas()
            dicionario = consulticas.convertirListaToDict(temporal)
            datosBase = consulticas.consultarProductos(dicionario)
            resultado= consulticas.convertirListaNormal(datosBase)
            #print(dicionario, " -- >" , datosBase)
            #print(" listas ",session['productos_temp'])
            #print(resultado)
            return render_template("consultarProductos.html",listado=resultado,dicionario1=dicionario)

    if request.values.get('btn-submit') == 'agregar-carrito':
        
        if len(temporal)>0:
            consulticas=SqlCons.Consultas()
            dicionario = consulticas.convertirListaToDict(temporal)
            datosBase = consulticas.consultarProductos(dicionario)
            resultado= consulticas.convertirListaNormal(datosBase)
             
            final=consulticas.agregarAlCarrito(session['user'],request.values.get('id_vendor'),request.values.get('id_pro'),request.values.get('cantidad_producto'))
            flash(final)
            return render_template("consultarProductos.html",listado=resultado)
    


    return render_template("consultarProductos.html")


@app.route('/agregarAlCarrito',methods=['GET','POST'])
@app.route('/agregarAlCarrito/<string:id_producto>')
def agregarAlCarrito(id_producto):
    print(id_producto)


@app.route('/updateProducto/<string:id>',methods=['GET','POST'])
def updateProducto(id):
    return render_template("updateProducto.html",ides=id)



@app.route('/carritoCompras',methods=['GET','POST'])
def carritoCompras():
    return render_template("carritoCompra.html")

@app.route('/Editproduct',methods=['GET', 'POST'])
def EditarProductos():

    if request.method =="POST":
        if request.values.get("editarP")!=None:
            #si se unde el boton editar ...
            pass
    return render_template("EditarP.html",infoP=['datas1','data2'])

## NO SE PARA QUE LO USARAN -- PSDT ANDUQUIA
@app.route('/Verproduct',methods=['GET', 'POST'])
def VerProductos():
    consulta= Consultas()
    consulta.detallePro('nombre')
    NombreP='Producto # 1'
    detalles=['Nombre1','ip_producto2','descripcion3','cod_barras4','catoria5']
    return render_template("VerP.html",NombreP=NombreP,detalles=detalles)
    

@app.route('/product',methods=['GET', 'POST'])
def productos():
    if request.method =="POST":
        if request.values.get("editar")!=None:
            #EDITAR UN PRODUCTO PUNTUAL
            idp=request.values.get("editar")

            return redirect(url_for('EditarProductos'))
        if request.values.get("ver")!=None:  
            #VER LOS PRODUCTOS 
            idp=request.values.get("ver")
            return redirect(url_for('VerProductos'))


        return render_template("productos.html")
    cabecera=['','','']
    columnas=[(1),(2),(3)]
    return render_template("productos.html")

@app.route('/adminUser',methods=['GET', 'POST'])
def admin():
    if request.method =="POST":
         return render_template("productos.html")
    return render_template("admin.html")
    
@app.route('/Registrar',methods=['GET', 'POST'])
def addUsu():
    if request.method =="POST":     
        print(
            request.values.get('nombre'),'\n',
        request.values.get('apellido'),'\n',
        request.values.get('pass1'),'\n',
        request.values.get('pass2'),'\n',
        request.values.get('correo'),'\n',
        request.values.get('movil'),'\n',
        request.values.get('direccion'),'\n',
        request.values.get('barrio'),'\n',
        request.values.get('telefono'),'\n',
        request.values.get('cedula')
        )
        if request.values.get('pass1')==request.values.get('pass2'):
            #validar datos
            consulticas = SqlCons.Consultas()
            salida= consulticas.agregarusuario(
                        request.values.get('nombre'),
                        request.values.get('apellido'),
                        request.values.get('pass1'),
                        request.values.get('correo'),
                        request.values.get('movil'),
                        request.values.get('direccion'),
                        request.values.get('barrio'),
                        request.values.get('telefono'),
                        request.values.get('cedula')
                    )
            if salida ==0:
                # salida erronea
                return render_template("registro.html",mensaje='Error; Usuario existente')
            elif salida==1:
                # salida correcta
                return render_template("login.html")


        else:
            #cntraseñas no coinciden
            return render_template("registro.html",mensaje='Error; contraseñas distintas')

        
        return render_template("registro.html")
    return render_template("registro.html")#--------------------------------------

@app.route("/CarritoCompra", methods=['GET','POST'])
def Carrito():
    print('Inicio Carrito')
    cabecera=['Vendedor','Nombre','Descripcion','Precio','Cantidad','Total Parcial']
    datos=[['Vendedor1','Nombre1','Descripcion1','$$$$$$$','###3','Total Parcial'],
            ['Vendedor2','Nombre2','Descripcion2','$$$$$$$','####','Total Parcial'],
            ['Vendedor3','Nombre3','Descripcion3','$$$$$$$','####','Total Parcial']]
    return render_template("shopingcar.html",cabecera=cabecera,datos0=datos)

@app.route("/ses-Cls-hhm", methods=['GET','POST'])
def dropsession():
    print('cerrar')
    #session.pop('user', None)
    return redirect(url_for('home'))
    
def buscarRol(user,passw):
    consul=SqlCons.Consultas()
    resultado=consul.rol(user,passw)
    return resultado

def RunSession(user,passw):
    print(buscarRol(user,passw))
    session['user'] = user  
    session['password']=passw 
    session['rol']=buscarRol(user,passw)
    
if __name__=="__main__":
    app.run('0.0.0.0', 5000, debug=True)
