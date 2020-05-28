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
        if cod_usuario and nombre and cedula and correo and password1 and movil and direccion and eps:
            resultado=consulticas.registrarUsuario(cod_usuario,nombre,cedula,rol,correo,password1,movil,direccion,eps,tipo_id)
            flash("Usuario registrado")
        else:
            flash("Faltan campos por llenar")
    

    return render_template("registro.html")

#CONSULTAR Y AGREGAR PRODUCTO
@app.route('/consultProductos/',methods=['GET','POST'])
def consultarProductos():
    resultado=list()
    consulticas = SqlCons.Consultas()
    categorias = consulticas.getCategoria()
    transformar=consulticas.convertirLidiTOLista(categorias)


    if request.values.get('btn-submit')== 'buscar':
        temporal.clear()
        if request.form['buscador']:
            temporal.append(request.form['buscador'])
        if request.form['selectSearch']:
            temporal.append(request.form['selectSearch'])
        if request.form['selectCategory']:
            temporal.append(request.form['selectCategory'])
        if len(temporal)>0:
           
            dicionario = consulticas.convertirListaToDict(temporal)
            datosBase = consulticas.consultarProductos(dicionario)
            resultado= consulticas.convertirListaNormal(datosBase)
            #print(dicionario, " -- >" , datosBase)
            #print(" listas ",session['productos_temp'])
            #print(resultado)
            return render_template("consultarProductos.html",categoria=transformar,listado=resultado,dicionario1=dicionario)

    if request.values.get('btn-submit') == 'agregar-carrito':
        
        if len(temporal)>0:
            consulticas=SqlCons.Consultas()
            dicionario = consulticas.convertirListaToDict(temporal)
            datosBase = consulticas.consultarProductos(dicionario)
            resultado= consulticas.convertirListaNormal(datosBase)
             
            final=consulticas.agregarAlCarrito(session['user'],request.values.get('id_vendor'),request.values.get('id_pro'),request.values.get('cantidad_producto'))
            flash(final)
            return render_template("consultarProductos.html",categoria=transformar,listado=resultado)
    


    return render_template("consultarProductos.html",categoria=transformar)



@app.route('/updateProducto/<string:id>',methods=['GET','POST'])
def updateProducto(id):
    return render_template("updateProducto.html",ides=id)

 
    

 
@app.route('/adminUser',methods=['GET', 'POST'])
def admin():
    if request.method =="POST":
         return render_template("productos.html")
    return render_template("admin.html")
    
    ## NO SE USA 
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
def CarritoCompra():
    consulticas=SqlCons.Consultas()
    resultado=consulticas.verCarrito(session['user'])
    valores=consulticas.convertirLidiTOLista(resultado)

    total=0.0
    for i in valores:
        total+=float(i[4])
    
    if request.values.get('btn-submit') == 'regresar':
        return redirect(url_for('consultarProductos'))
    elif request.values.get('btn-submit') == 'vaciar':
        resul=consulticas.vaciarCarrito(session['user'])
        flash(resul)
        return render_template("carritoCompra.html")
    elif request.values.get('msj_respuesta'):
        flash(request.values.get('msj_respuesta'))
        return render_template("carritoCompra.html",mi_carrito=valores,total=total)

    

    return render_template("carritoCompra.html",mi_carrito=valores,total=total)

@app.route('/eliminarProducto/<string:cod_producto>/<string:cod_vendor>',methods=['GET','POST'])
def eliminarProducto(cod_producto,cod_vendor):
    consulticas= SqlCons.Consultas()
    respuesta=consulticas.eliminarProductoCarrito(session['user'],cod_vendor,cod_producto)
    return redirect(url_for('CarritoCompra',msj_respuesta=respuesta))

@app.route('/confirmarPedido',methods=['GET','POST'])
def confirmarPedido():
    consulticas= SqlCons.Consultas()
    resultado=consulticas.verCarrito(session['user'])
    arreglo=consulticas.convertirLidiTOLista(resultado)
   
    dicionari=consulticas.convertirListaToDict(arreglo[0])
    print(dicionari)
    total=0.0
    for i in arreglo:
        total+=float(i[4])

    #print(arreglo)
    resultado_final=consulticas.confirmarPedido(session['user'],total,2500.0,arreglo)
    #consulticas.vaciarCarrito(session['user'])

    return redirect(url_for('CarritoCompra',msj_respuesta=resultado_final))

@app.route('/registrarProducto')
def registrarProducto():
    consulticas =SqlCons.Consultas()
    categorias = consulticas.getCategoria()
    transformar=consulticas.convertirLidiTOLista(categorias)
    if request.values.get('btn-submit') == 'Atras':
        return redirect(url_for('consultarProductos'))
    elif request.values.get('btn-submit') == 'Registrar':
        cod_producto=request.values.get('cod_producto')
        descripcion=request.values.get('descripcion')
        precio=request.values.get('precio')
        peso=request.values.get('peso')
        marca=request.values.get('marca')
        info_tecnica=request.values.get('info_tecnica')
        
        unidad=request.values.get('unidad')
        selectTipo=request.values.get('selectTipo')

        resultado=consulticas.crearProducto(cod_producto,session['user'] ,descripcion,selectTipo,precio,peso,marca,info_tecnica,unidad)
        flash(resultado)

    return render_template("agregar_producto.html",categoria=transformar)


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
